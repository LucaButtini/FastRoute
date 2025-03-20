<?php

$title = 'Elenco Clienti';
require './Template/header.php';
require 'Config/DBConnection.php';

$conf = require 'Config/db_conf.php';
$db = DbConnection::getDb($conf);

// Query per ottenere i dati dalla tabella "clienti"
$query = 'SELECT * FROM clienti';

$content = ''; // Inizializza la variabile per contenere le righe della tabella

try {
    $stm = $db->prepare($query);
    $stm->execute();

    while ($cliente = $stm->fetch(PDO::FETCH_OBJ)) {
        $content .= "<tr>";
        $content .= "<td>" . htmlspecialchars($cliente->codice_fiscale) . "</td>";
        $content .= "<td>" . htmlspecialchars($cliente->nome) . "</td>";
        $content .= "<td>" . htmlspecialchars($cliente->cognome) . "</td>";
        $content .= "<td>" . htmlspecialchars($cliente->indirizzo) . "</td>";
        $content .= "<td>" . htmlspecialchars($cliente->mail) . "</td>";
        $content .= "<td>" . htmlspecialchars($cliente->punteggio) . "</td>";
        $content .= "</tr>";
    }

    $stm->closeCursor();
} catch (Exception $e) {
    // Se c'è un errore, mostra un messaggio nella tabella
    $content = '<tr><td colspan="6" class="text-danger">Errore durante il caricamento dei dati.</td></tr>';
}
?>

<div class="container mt-5 rounded-4 p-5 bg-white">
    <div class="text-center pt-3">
        <h1 class="text-primary"><strong>Elenco Clienti</strong></h1>
        <p class="lead">Visualizza l'elenco completo dei clienti registrati:</p>
    </div>

    <div class="table-responsive mt-4">
        <table class="table table-striped table-bordered text-center">
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
</div>

<?php
require './Template/footer.php';
?>
