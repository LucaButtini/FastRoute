<?php
session_start(); // Avvia la sessione qui!
require_once '../Config/DbConnection.php';
$conf = require __DIR__ . '/../Config/db_conf.php';
$db = DbConnection::getDb($conf);

//  Gestione del tema
// Se è stato inviato il form per il tema (campo "color" presente)
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['form_type']) && $_POST['form_type'] === 'theme') {
    $selectedColor = $_POST['color'];
    // Imposta il cookie per 30 giorni
    setcookie('bg-color', $selectedColor, time() + (86400 * 30), "/");
    $bgColor = $selectedColor;
} else {
    // Recupera il colore dal cookie, altrimenti usa il valore predefinito
    $bgColor = isset($_COOKIE['bg-color']) ? $_COOKIE['bg-color'] : 'white';
}

// Gestione del cambio password
$error = "";
$success = "";
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['form_type']) && $_POST['form_type'] === 'password') {
    $current_password = $_POST['current_password'];
    $new_password     = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verifica che la nuova password e la conferma siano uguali
    if ($new_password !== $confirm_password) {
        $error = "Le nuove password non corrispondono.";
    } else {
        // Recupera la password attuale dell'utente nel database
        $query_password = "SELECT password FROM personale WHERE codice_fiscale = ?";
        try {
            $stmt = $db->prepare($query_password);
            $stmt->execute([$_SESSION['user_id']]);
            $user_data = $stmt->fetch();
            $stmt->closeCursor();

            // Verifica la password attuale
            if ($user_data && password_verify($current_password, $user_data->password)) {
                // Aggiorna la password
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $update_query = "UPDATE personale SET password = ? WHERE codice_fiscale = ?";
                try {
                    $updateStmt = $db->prepare($update_query);
                    //quadre per passare i dati alla query col "?"
                    $updateStmt->execute([$new_password_hash, $_SESSION['user_id']]);
                    $success = "La tua password è stata cambiata con successo!";
                    $updateStmt->closeCursor();
                } catch (PDOException $exception) {
                    logError($exception);
                    $error = "Errore durante l'aggiornamento della password.";
                }
            } else {
                $error = "La password attuale non è corretta.";
            }
        } catch (PDOException $exception) {
            logError($exception);
            $error = "Errore durante la verifica della password.";
        }
    }
}

// Recupera i dati dell'utente per la visualizzazione
$user_id = $_SESSION['user_id'];
$query = "SELECT codice_fiscale, nome, mail, sede FROM personale WHERE codice_fiscale = ?";
try {
    $stmt = $db->prepare($query);
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    $stmt->closeCursor();
} catch (PDOException $exception) {
    logError($exception);
    $error = "Errore nel recupero dei dati utente.";
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Impostazioni - Cambia Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: <?= $bgColor ?>; color: <?= ($bgColor == 'black') ? '#fff' : '#000' ?>;">
<!-- Bottone per tornare alla home -->
<div class="text-start mb-4">
    <a href="../index.php" class="btn btn-outline-primary">Torna alla Home</a>
</div>
<div class="container mt-5">
    <h1 class="text-center mb-4" style="color: <?= ($bgColor == 'black') ? 'red' : 'darkblue' ?>;">
        <strong>Cambia la tua Password</strong>
    </h1>

    <!-- Form per la scelta del tema -->
    <div class="card mb-4" style="background-color: <?= ($bgColor == 'black') ? '#333' : (($bgColor == 'grey') ? '#ccc' : '#f8f9fa') ?>; color: <?= ($bgColor == 'black') ? '#fff' : '#000' ?>;">
        <div class="card-body">
            <h5 class="card-title"><strong>Personalizza Interfaccia</strong></h5>
            <form action="impostazioni.php" method="post">
                <input type="hidden" name="form_type" value="theme">
                <div class="mb-3">
                    <label for="color" class="form-label">Scegli il tema di colore:</label>
                    <select class="form-select" id="color" name="color" required>
                        <option value="white" <?= ($bgColor == 'white') ? 'selected' : '' ?>>Light</option>
                        <option value="black" <?= ($bgColor == 'black') ? 'selected' : '' ?>>Dark</option>
                        <option value="grey" <?= ($bgColor == 'grey') ? 'selected' : '' ?>>Grey</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-secondary w-100">Salva Tema</button>
            </form>
        </div>
    </div>

    <!-- Visualizzazione delle credenziali dell'utente -->
    <div class="card mb-4" style="background-color: <?= ($bgColor == 'black') ? '#333' : (($bgColor == 'grey') ? '#eee' : '#f8f9fa') ?>; color: <?= ($bgColor == 'black') ? '#fff' : '#000' ?>;">
        <div class="card-body">
            <h5 class="card-title"><strong>Dati utente</strong></h5>
            <p><strong>Codice Fiscale:</strong> <?= $user->codice_fiscale ?></p>
            <p><strong>Nome:</strong> <?= $user->nome ?></p>
            <p><strong>Email:</strong> <?= $user->mail ?></p>
            <p><strong>Sede:</strong> <?=$user->sede ?></p>
        </div>
    </div>

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
            <input type="hidden" name="form_type" value="password">
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

            <button type="submit" class="btn btn-primary w-100 mb-5">Cambia Password</button>
        </form>
    <?php } ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
