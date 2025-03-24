<?php
$title = 'Inserimento Plico';
require './Template/header.php';

require 'Config/DBConnection.php';
$conf = require 'Config/db_conf.php';
$db = DbConnection::getDb($conf);

$error = '';

// Carica gli stati dalla tabella "stati"
$statesOptions = '';
$queryStates = "SELECT nome FROM stati ORDER BY nome";
try {
    $stmStates = $db->prepare($queryStates);
    $stmStates->execute();
    while ($state = $stmStates->fetch()) {
        $statesOptions .= "<option value='" . $state->nome . "'>" . $state->nome . "</option>";
    }
} catch (Exception $e) {
    $statesOptions = "<option value=''>Errore nel caricamento degli stati</option>";
}

// Carica le sedi dalla tabella "sedi"
$sediOptions = '';
$querySedi = "SELECT nome FROM sedi ORDER BY nome";
try {
    $stmSedi = $db->prepare($querySedi);
    $stmSedi->execute();
    while ($sede = $stmSedi->fetch()) {
        $sediOptions .= "<option value='" . $sede->nome . "'>" . $sede->nome . "</option>";
    }
} catch (Exception $e) {
    $sediOptions = "<option value=''>Errore nel caricamento delle sedi</option>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $codice = $_POST['codice'];
    $stato = $_POST['stato'];
    $nome_sede = $_POST['nome_sede'];

    // Inserimento del nuovo plico
    $query = "INSERT INTO plichi (codice, stato) VALUES (:codice, :stato)";
    try {
        $stmt = $db->prepare($query);
        $stmt->bindValue(':codice', $codice);
        $stmt->bindValue(':stato', $stato);
        $stmt->execute();
    } catch (PDOException $e) {
        $error = "Plico con questo codice già registrato!";
    }

    if (!$error) { // Solo se non c'è errore, continua con l'inserimento della sede
        $queryPlichiSedi = "INSERT INTO plichi_sedi (nome_sede, codice_plico) VALUES (:nome_sede, :codice)";
        $stmtPlichiSedi = $db->prepare($queryPlichiSedi);
        $stmtPlichiSedi->bindValue(':nome_sede', $nome_sede);
        $stmtPlichiSedi->bindValue(':codice', $codice);
        $stmtPlichiSedi->execute();

        header('Location: confirm.html');
        exit();
    }
}
?>


    <div class="text-center">
        <h1 class="text-primary"><strong>Inserisci Nuovo Plico</strong></h1>
        <p class="lead">Compila il form per registrare i dati del plico.</p>
    </div>

    <?php if ($error){ ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php } ?>

    <form method="post" action="insert_plichi.php">
        <div class="mb-4">
            <label for="codice" class="form-label">Codice Plico:</label>
            <input type="number" class="form-control" id="codice" name="codice" placeholder="Inserisci il codice del plico" required>
        </div>
        <div class="mb-4">
            <label for="stato" class="form-label">Stato del Plico:</label>
            <select class="form-select" id="stato" name="stato" required>
                <?= $statesOptions; ?>
            </select>
        </div>
        <div class="mb-4">
            <label for="nome_sede" class="form-label">Sede:</label>
            <select class="form-select" id="nome_sede" name="nome_sede" required>
                <?= $sediOptions; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-dark"><i class="bi bi-plus-circle"></i> Aggiungi Plico</button>
    </form>


<?php
require './Template/footer.php';
?>
