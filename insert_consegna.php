<?php
$title = 'Inserimento Consegna';
require 'Config/DBConnection.php';
$conf= require 'Config/db_conf.php';
$db = DbConnection::getDb($conf);
require './Template/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recupero dei dati dal form
    $cliente = $_POST['cliente'];
    $codice_plico = $_POST['codice_plico'];
    $data_consegna = $_POST['data_consegna'];

    // Verifica se la consegna esiste già
    $checkQuery = "SELECT * FROM consegne WHERE cliente = :cliente AND codice_plico = :codice_plico";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindValue(':cliente', $cliente);
    $checkStmt->bindValue(':codice_plico', $codice_plico);
    $checkStmt->execute();
    $existingConsegna = $checkStmt->fetch();

    if ($existingConsegna) {
        $error = "Consegna per questo plico e cliente è già stata registrata!";
    } else {
        $query = "INSERT INTO consegne (cliente, codice_plico, data) 
                  VALUES (:cliente, :codice_plico, :data_consegna)";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':cliente', $cliente);
        $stmt->bindValue(':codice_plico', $codice_plico);
        $stmt->bindValue(':data_consegna', $data_consegna);

        if ($stmt->execute()) {
            $success = "Consegna registrata correttamente!";
            header('Location: confirm.html');
            exit();
        } else {
            $error = "Errore durante la registrazione della consegna.";
        }
    }
}
?>

<div class="container mt-5 text-center bg-body-tertiary rounded-4">
    <h1 class="text-primary"><strong>Inserisci Consegna</strong></h1>
    <p class="lead">Registra la consegna del plico effettuata dal cliente mittente.</p>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="post" action="insert_consegna.php">
        <div class="mb-4">
            <label for="cliente" class="form-label">Codice Fiscale Cliente (Mittente):</label>
            <input type="text" class="form-control" id="cliente" name="cliente" placeholder="Codice Fiscale" required>
        </div>
        <div class="mb-4">
            <label for="codice_plico" class="form-label">Codice Plico:</label>
            <input type="number" class="form-control" id="codice_plico" name="codice_plico" placeholder="Codice Plico" required>
        </div>
        <div class="mb-4">
            <label for="data_consegna" class="form-label">Data e Ora Consegna:</label>
            <input type="datetime-local" class="form-control" id="data_consegna" name="data_consegna" required>
        </div>
        <button type="submit" class="btn btn-dark"><i class="bi bi-plus-circle"></i> Registra Consegna</button>
    </form>
</div>

<?php
require './Template/footer.php';
?>
