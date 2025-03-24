<?php

$title = 'Elenco Clienti';
require './Template/header.php';
require 'Config/DBConnection.php';

$conf = require 'Config/db_conf.php';
$db = DbConnection::getDb($conf);

// Query per ottenere i dati dalla tabella "clienti"
$query = 'SELECT * FROM clienti';

$content = '';

try {
    $stm = $db->prepare($query);
    $stm->execute();

    while ($cliente = $stm->fetch()) {
        $content .= "<tr>";
        $content .= "<td>" . $cliente->codice_fiscale . "</td>";
        $content .= "<td>" . $cliente->nome . "</td>";
        $content .= "<td>" . $cliente->cognome . "</td>";
        $content .= "<td>" . $cliente->indirizzo . "</td>";
        $content .= "<td>" .$cliente->mail . "</td>";
        $content .= "<td>" . $cliente->punteggio . "</td>";
        $content .= "</tr>";
    }

    $stm->closeCursor();
} catch (Exception $e) {
    // Se c'è un errore, mostra un messaggio nella tabella
    $content = '<tr><td colspan="6" class="text-danger">Errore durante il caricamento dei dati.</td></tr>';
}
?>

    <div class="text-center pt-3">
        <h1 class="text-primary"><strong>Elenco Clienti</strong></h1>
        <p class="lead">Visualizza l'elenco completo dei clienti registrati:</p>
    </div>

    <div class="table-responsive mt-4">
        <table class="table table-striped table-bordered text-center table-hover">
            <thead class="table-dark">
            <tr>
                <th>Codice Fiscale</th>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Indirizzo</th>
                <th>E-mail</th>
                <th>Punteggio</th>
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
