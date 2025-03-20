<?php
$title = 'Inserimento Plico';
require './Template/header.php';

require 'Config/DBConnection.php';
$conf = require 'Config/db_conf.php';
$db = DbConnection::getDb($conf);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recupero dei dati dal form
    $codice = $_POST['codice'];
    $stato = $_POST['stato'];

    // Verifica se il plico esiste già (controllo per codice)
    $checkQuery = "SELECT * FROM plichi WHERE codice = :codice";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindValue(':codice', $codice);
    $checkStmt->execute();
    $existingPlico = $checkStmt->fetch();

    if ($existingPlico) {
        // Se il plico è già registrato, mostra un messaggio informativo
        echo "<div class='container mt-5'><h3 class='text-danger'>Plico con questo codice già registrato!</h3></div>";
    } else {
        // Inserimento del nuovo plico
        $query = "INSERT INTO plichi (codice, stato) VALUES (:codice, :stato)";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':codice', $codice);
        $stmt->bindValue(':stato', $stato);

        if ($stmt->execute()) {
            header('Location: confirm.html');
            exit();
        } else {
            $error = "Errore durante l'inserimento del plico.";
        }
    }
}
?>

<div class="container mt-5 text-center bg-body-tertiary rounded-4">
    <div class="text-center">
        <h1 class="text-primary"><strong>Inserisci Nuovo Plico</strong></h1>
        <p class="lead">Compila il form per registrare i dati del plico.</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" action="insert_plichi.php">
        <div class="mb-4">
            <label for="codice" class="form-label">Codice Plico:</label>
            <input type="number" class="form-control" id="codice" name="codice" placeholder="Inserisci il codice del plico" required>
        </div>
        <div class="mb-4">
            <label for="stato" class="form-label">Stato del Plico:</label>
            <select class="form-select" id="stato" name="stato" required>
                <option value="in partenza">In Partenza</option>
                <option value="in transito">In Transito</option>
                <option value="consegnato">Consegnato</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Aggiungi Plico</button>
    </form>
</div>

<?php
require './Template/footer.php';
?>
