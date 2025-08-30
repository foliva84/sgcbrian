<?php
class BulkPaymentsService
{
    private $pdo;
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    // Retorna: ['status' => 'updated'|'no_change'|'error', 'would_update' => 1|0, 'message' => '...']
    public function processOne(int $caseId, ?string $providerCode, ?string $paidDate, string $mode, string $runId, string $executedBy): array
    {
        $current = $this->getCaseInfo($caseId);
        if ($current === null) { $this->maybeStage($mode, $runId, $caseId, $providerCode, $paidDate, null, null, 0, 'case_not_found', $executedBy); return ['status'=>'error','would_update'=>0,'message'=>'Caso no encontrado']; }

        if ($providerCode !== null && !$this->providerExists($providerCode)) {
            $this->maybeStage($mode, $runId, $caseId, $providerCode, $paidDate, $current['status'], $current['status'], 0, 'provider_not_found', $executedBy);
            return ['status'=>'error','would_update'=>0,'message'=>'Proveedor no encontrado'];
        }

        if ($this->isAlreadyPaid($current)) {
            $this->maybeStage($mode, $runId, $caseId, $providerCode, $paidDate, $current['status'], $current['status'], 0, 'already_paid', $executedBy);
            return ['status'=>'no_change','would_update'=>0,'message'=>'Ya estaba pagado'];
        }

        if ($mode === 'preview') { $this->maybeStage($mode, $runId, $caseId, $providerCode, $paidDate, $current['status'], 'pagado', 1, 'preview_only', $executedBy); return ['status'=>'no_change','would_update'=>1,'message'=>'Se actualizaría (preview)']; }
        if ($mode === 'staging') { $this->maybeStage($mode, $runId, $caseId, $providerCode, $paidDate, $current['status'], 'pagado', 1, 'staging_only', $executedBy); return ['status'=>'no_change','would_update'=>1,'message'=>'Se registró en staging']; }

        // LIVE: invocar el MISMO flujo/manual existente que hoy marca pagado (reemplazar por tu servicio real).
        $ok = $this->markAsPaidUsingExistingFlow($caseId, $providerCode, $paidDate);
        if ($ok) { return ['status'=>'updated','would_update'=>1,'message'=>'Actualizado a pagado']; }
        return ['status'=>'error','would_update'=>0,'message'=>'Fallo en flujo de pago'];
    }

    private function getCaseInfo(int $caseId): ?array
    {
        $sql = "SELECT id AS case_id, estado AS status FROM casos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql); $stmt->execute([':id'=>$caseId]); $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null; return ['case_id'=>(int)$row['case_id'],'status'=>$row['status']];
    }

    private function providerExists(string $providerCode): bool
    {
        $sql = "SELECT 1 FROM prestadores WHERE prestador_nombre = :name LIMIT 1";
        $stmt = $this->pdo->prepare($sql); $stmt->execute([':name'=>$providerCode]); return (bool)$stmt->fetchColumn();
    }

    private function isAlreadyPaid(array $current): bool { return strtolower((string)$current['status']) === 'pagado'; }

    private function markAsPaidUsingExistingFlow(int $caseId, ?string $providerCode, ?string $paidDate): bool
    {
        // IMPORTANTE: reemplazar por la invocación al servicio/acción actual del flujo manual.
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare("UPDATE casos SET estado = 'pagado' WHERE id = :id");
            $stmt->execute([':id'=>$caseId]);
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            return false;
        }
    }

    private function maybeStage(string $mode, string $runId, int $caseId, ?string $providerCode, ?string $paidDate,
                                ?string $statusBefore, ?string $statusAfter, int $wouldUpdate, string $reason, string $executedBy): void
    {
        if ($mode !== 'staging' && $mode !== 'preview') return;
        $sql = "INSERT INTO bulk_payments_staging
                (run_id, case_id, provider_code, paid_date, status_before, status_after, would_update, reason, executed_at, executed_by)
                VALUES (:run_id, :case_id, :provider_code, :paid_date, :status_before, :status_after, :would_update, :reason, NOW(), :executed_by)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':run_id'=>$runId, ':case_id'=>$caseId, ':provider_code'=>$providerCode, ':paid_date'=>$paidDate,
            ':status_before'=>$statusBefore, ':status_after'=>$statusAfter, ':would_update'=>$wouldUpdate,
            ':reason'=>$reason, ':executed_by'=>$executedBy,
        ]);
    }
}
