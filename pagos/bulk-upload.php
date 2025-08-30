<?php
require '../seguridad/seguridad.php';
require_once '../clases/DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    require_once __DIR__ . '/BulkPaymentsController.php';
    $controller = new BulkPaymentsController($pdo);
    $controller->uploadAndProcess();
    exit;
}
?>
<?php $modo = getenv('BULK_PAYMENTS_MODE') ?: 'preview'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carga masiva de pagos</title>
</head>
<body>
    <h1>Carga masiva de pagos</h1>
    <p>Modo actual: <strong><?php echo htmlspecialchars($modo, ENT_QUOTES, 'UTF-8'); ?></strong></p>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="file" accept=".csv" required>
        <button type="submit">Procesar</button>
    </form>
    <p>El archivo debe contener al menos la columna <code>case_id</code>.</p>
</body>
</html>
