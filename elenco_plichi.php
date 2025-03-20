<?php

$title = 'Elenco Plichi';
require './Template/header.php';
require 'Config/DBConnection.php';

$conf = require 'Config/db_conf.php';
$db = DbConnection::getDb($conf);

// Query per ottenere i dati dalla tabella "plichi"
$query = 'SELECT * FROM plichi';

$content = ''; // Inizializza la variabile per contenere le righe della tabella

try {
    $stm = $db->prepare($query);
    $stm->execute();

    while ($plico = $stm->fetch(PDO::FETCH_OBJ)) {
        $content .= "<tr>";
        $content .= "<td>" . htmlspecialchars($plico->codice) . "</td>";
        $content .= "<td>" . htmlspecialchars($plico->stato) . "</td>";
        $content .= "</tr>";
    }

    $stm->closeCursor();
} catch (Exception $e) {
    $content = '<tr><td colspan="2" class="text-danger">Errore durante il caricamento dei dati.</td></tr>';
}
?>

<div class="container mt-5 rounded-4 p-5 bg-white">
    <div class="text-center pt-3">
        <h1 class="text-primary"><strong>Elenco Plichi</strong></h1>
        <p class="lead">Visualizza l'elenco completo dei plichi registrati:</p>
    </div>

    <div class="table-responsive mt-4">
        <table class="table table-striped table-bordered text-center">
            <thead class="table-dark">
            <tr>
                <th>Codice</th>
                <th>Stato</th>
            </tr>
            </thead>
            <tbody>
            <?= $content; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require './Template/footer.php';
?>
