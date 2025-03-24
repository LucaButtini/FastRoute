<?php
$title = 'Inserimento Spedizione';
require 'Config/DBConnection.php';
$conf= require 'Config/db_conf.php';
$db = DbConnection::getDb($conf);
require './Template/header.php';

// Assumiamo che il personale responsabile sia loggato e il suo codice fiscale sia memorizzato in $_SESSION['user_id']
if (!isset($_SESSION['user_id'])) {
    header('Location: Login/login.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $personale = $_SESSION['user_id'];  // Responsabile che registra la spedizione
    $codice_plico = $_POST['codice_plico'];
    $data_spedizione = $_POST['data_spedizione']; // formato datetime-local

    // Verifica se la spedizione esiste già (facoltativo)
    $checkQuery = "SELECT * FROM spedizioni WHERE personale = :personale AND codice_plico = :codice_plico";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindValue(':personale', $personale);
    $checkStmt->bindValue(':codice_plico', $codice_plico);
    $checkStmt->execute();
    $existingSpedizione = $checkStmt->fetch();

    if ($existingSpedizione) {
        $error = "Spedizione per questo plico è già stata registrata dal responsabile!";
    } else {
        $query = "INSERT INTO spedizioni (personale, codice_plico, data) 
                  VALUES (:personale, :codice_plico, :data_spedizione)";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':personale', $personale);
        $stmt->bindValue(':codice_plico', $codice_plico);
        $stmt->bindValue(':data_spedizione', $data_spedizione);

        if ($stmt->execute()) {
            $success = "Spedizione registrata correttamente!";
            header('Location: confirm.html');
            exit();
        } else {
            $error = "Errore durante la registrazione della spedizione.";
        }
    }
}
?>


    <h1 class="text-primary"><strong>Inserisci Spedizione</strong></h1>
    <p class="lead">Registra la spedizione del plico effettuata dal responsabile.</p>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="post" action="insert_spedizione.php">
        <div class="mb-4">
            <label for="codice_plico" class="form-label">Codice Plico:</label>
            <input type="number" class="form-control" id="codice_plico" name="codice_plico" placeholder="Codice Plico" required>
        </div>
        <div class="mb-4">
            <label for="data_spedizione" class="form-label">Data e Ora Spedizione:</label>
            <input type="datetime-local" class="form-control" id="data_spedizione" name="data_spedizione" required>
        </div>
        <button type="submit" class="btn btn-dark"><i class="bi bi-plus-circle"></i> Registra Spedizione</button>
    </form>


<?php
require './Template/footer.php';
?>
