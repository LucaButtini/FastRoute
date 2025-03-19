<?php
session_start(); // Avvia la sessione qui!
require_once '../Config/DbConnection.php';
$conf = require __DIR__ . '/../Config/db_conf.php';

$db = DbConnection::getDb($conf);

$error = "";
$success = "";

// Se il form è stato inviato
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_id = $_SESSION['user_id']; // Il codice fiscale dell'utente loggato
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verifica che la nuova password e la conferma siano uguali
    if ($new_password !== $confirm_password) {
        $error = "⚠️ Le nuove password non corrispondono.";
    } else {
        // Recupera la password attuale dell'utente nel database
        $stmt = $db->prepare("SELECT password FROM personale WHERE codice_fiscale = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        // Verifica la password attuale
        if ($user && password_verify($current_password, $user->password)) {
            // Se la password attuale è corretta, aggiorna la password
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $updateStmt = $db->prepare("UPDATE personale SET password = ? WHERE codice_fiscale = ?");
            $updateStmt->execute([$new_password_hash, $user_id]);
            $success = "✅ La tua password è stata cambiata con successo!";
        } else {
            $error = "⚠️ La password attuale non è corretta.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Impostazioni - Cambia Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Cambia la tua Password</h1>

    <!-- Se c'è un errore -->
    <?php if ($error) { ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php } ?>

    <!-- Se la password è stata cambiata con successo -->
    <?php if ($success) { ?>
        <div class="alert alert-success text-center"><?= $success ?></div>
        <div class="text-center mt-4">
            <a href="../index.php" class="btn btn-primary">Torna alla Home</a>
        </div>
    <?php } else { ?>
        <!-- Form di cambiamento password -->
        <form action="impostazioni.php" method="post" class="w-50 mx-auto">
            <div class="mb-3">
                <label for="current_password" class="form-label">Password Attuale</label>
                <input type="password" name="current_password" id="current_password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="new_password" class="form-label">Nuova Password</label>
                <input type="password" name="new_password" id="new_password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Conferma Nuova Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Cambia Password</button>
        </form>
    <?php } ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
