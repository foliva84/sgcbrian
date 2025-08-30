<?php
require_once __DIR__ . '/BulkPaymentsService.php';

class BulkPaymentsController
{
    private $pdo;
    private $service;

    public function __construct(PDO $pdo)
    {
        $this->pdo     = $pdo;
        $this->service = new BulkPaymentsService($pdo);
    }

    public function uploadAndProcess(): void
    {
        $mode         = $this->env('BULK_PAYMENTS_MODE', 'preview'); // preview|staging|live
        $batchSize    = (int)$this->env('BULK_BATCH_SIZE', '200');
        $previewLimit = (int)$this->env('BULK_PREVIEW_LIMIT', '200');
        $executedBy   = $this->currentUser();
        $runId        = $this->generateRunId($executedBy);

        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $this->json(400, ['error' => 'Archivo no recibido o con error']); return;
        }

        $fh = fopen($_FILES['file']['tmp_name'], 'r');
        if ($fh === false) { $this->json(400, ['error' => 'No se pudo abrir el archivo']); return; }

        $headers = fgetcsv($fh);
        if ($headers === false) { fclose($fh); $this->json(400, ['error' => 'Archivo vacío o inválido']); return; }

        $required = ['case_id']; // provider_code y paid_date opcionales
        if (!$this->hasRequiredHeaders($headers, $required)) {
            fclose($fh); $this->json(400, ['error' => 'Cabeceras inválidas. Se requiere al menos: case_id']); return;
        }

        $idx = $this->indexHeaders($headers);

        $summary = [
            'run_id'        => $runId, 'mode' => $mode,
            'total_rows'    => 0, 'processed' => 0, 'would_update' => 0,
            'updated'       => 0, 'no_change' => 0, 'errors' => 0,
            'errors_sample' => []
        ];

        $buffer = [];
        while (($row = fgetcsv($fh)) !== false) {
            $summary['total_rows']++;

            $buffer[] = [
                'case_id'       => $this->getField($row, $idx, 'case_id'),
                'provider_code' => $this->getField($row, $idx, 'provider_code'),
                'paid_date'     => $this->getField($row, $idx, 'paid_date'),
            ];

            if (count($buffer) >= $batchSize) {
                $this->flushBatch($buffer, $mode, $runId, $executedBy, $summary, $previewLimit);
                $buffer = [];
            }
        }
        fclose($fh);

        if (!empty($buffer)) {
            $this->flushBatch($buffer, $mode, $runId, $executedBy, $summary, $previewLimit);
        }

        $this->json(200, ['summary' => $summary]);
    }

    private function flushBatch(array $batch, string $mode, string $runId, string $executedBy, array &$summary, int $previewLimit): void
    {
        foreach ($batch as $item) {
            $caseId = $item['case_id'];
            $providerCode = $item['provider_code'];
            $paidDate = $item['paid_date'];

            if (!is_numeric($caseId)) {
                $summary['errors']++; $this->pushError($summary, 'case_id inválido: ' . (string)$caseId, $previewLimit); continue;
            }
            try {
                $result = $this->service->processOne((int)$caseId, $providerCode, $paidDate, $mode, $runId, $executedBy);
                $summary['processed']++;

                if ($mode === 'live') {
                    if ($result['status'] === 'updated') $summary['updated']++;
                    if ($result['status'] === 'no_change') $summary['no_change']++;
                } else {
                    if (isset($result['would_update']) && (int)$result['would_update'] === 1) $summary['would_update']++;
                    if ($result['status'] === 'no_change') $summary['no_change']++;
                }
            } catch (Exception $ex) {
                $summary['errors']++; $this->pushError($summary, 'Error case_id ' . $caseId . ': ' . $ex->getMessage(), $previewLimit);
            }
        }
    }

    private function getField(array $row, array $idx, string $name) { if (!isset($idx[$name])) return null; $i = $idx[$name]; return isset($row[$i]) ? trim($row[$i]) : null; }
    private function hasRequiredHeaders(array $headers, array $required): bool { $map = []; foreach ($headers as $h) { $map[strtolower(trim($h))]=true; } foreach ($required as $r) { if (!isset($map[strtolower($r)])) return false; } return true; }
    private function indexHeaders(array $headers): array { $idx = []; foreach ($headers as $i=>$h) { $idx[strtolower(trim($h))]=$i; } return $idx; }
    private function pushError(array &$summary, string $msg, int $limit): void { if (count($summary['errors_sample']) < $limit) { $summary['errors_sample'][] = $msg; } }
    private function json(int $code, array $payload): void { http_response_code($code); header('Content-Type: application/json; charset=utf-8'); echo json_encode($payload); }
    private function currentUser(): string { return 'codex-agent'; } // Integrar con Auth real
    private function generateRunId(string $user): string { return date('YmdHis') . '-' . preg_replace('/[^a-zA-Z0-9_\-]/', '_', $user); }
    private function env(string $key, string $default): string { $val = getenv($key); if ($val === false || $val === '') return $default; return $val; }
}
