<?php

$title = 'Elenco Destinatari';
require './Template/header.php';
require 'Config/DBConnection.php';

$conf = require 'Config/db_conf.php';
$db = DbConnection::getDb($conf);

// Query per ottenere i dati dalla tabella "destinatari"
$query = 'SELECT * FROM destinatari';

$content = '';

try {
    $stm = $db->prepare($query);
    $stm->execute();

    while ($destinatario = $stm->fetch()) {
        $content .= "<tr>";
        $content .= "<td>" . $destinatario->codice_fiscale . "</td>";
        $content .= "<td>" . $destinatario->nome . "</td>";
        $content .= "<td>" . $destinatario->cognome . "</td>";
        $content .= "</tr>";
    }

    $stm->closeCursor();
} catch (Exception $e) {
    $content = '<tr><td colspan="3" class="text-danger">Errore durante il caricamento dei dati.</td></tr>';
}
?>

    <div class="text-center pt-3">
        <h1 class="text-primary"><strong>Elenco Destinatari</strong></h1>
        <p class="lead">Visualizza l'elenco completo dei destinatari registrati:</p>
    </div>

    <div class="table-responsive mt-4">
        <table class="table table-striped table-bordered text-center table-hover">
            <thead class="table-dark">
            <tr>
                <th>Codice Fiscale</th>
                <th>Nome</th>
                <th>Cognome</th>
            </tr>
            </thead>
            <tbody>
            <?= $content; ?>
            </tbody>
        </table>
    </div>

<?php
require './Template/footer.php';
?>
