<?php
$title = "FastRoute - Dashboard";
require 'Config/DBConnection.php';
$conf = require 'Config/db_conf.php';
$db = DbConnection::getDb($conf);
require './Template/header.php';

$query = "
    SELECT 
        con.codice_plico,
        cli.nome AS mittente,
        dest.nome AS destinatario,
        p.stato AS stato_plico,
        con.data AS data_consegna,
        sp.data AS data_spedizione,
        rt.data AS data_ritiro
    FROM consegne con
    JOIN clienti cli ON con.cliente = cli.codice_fiscale
    LEFT JOIN plichi p ON con.codice_plico = p.codice
    LEFT JOIN spedizioni sp ON con.codice_plico = sp.codice_plico
    LEFT JOIN ritiri rt ON con.codice_plico = rt.codice_plico
    LEFT JOIN destinatari dest ON rt.destinatario = dest.codice_fiscale
    ORDER BY con.codice_plico
";

try {
    $stmt = $db->prepare($query);
    $stmt->execute();
} catch (Exception $e) {
    $errorMessage = "Errore durante il caricamento dei dati: " . $e->getMessage();
}
?>

<div class="container mt-5">
    <h1 class="text-primary"><strong>Dashboard Spedizioni</strong></h1>
    <p class="lead">Visualizza tutte le spedizioni registrate nel sistema.</p>

    <?php if (isset($errorMessage)) { ?>
        <div class="alert alert-danger"><?= $errorMessage ?></div>
    <?php } ?>

    <div class="table-responsive mt-4">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
            <tr>
                <th>Codice Plico</th>
                <th>Mittente</th>
                <th>Destinatario</th>
                <th>Stato</th>
                <th>Data Consegna</th>
                <th>Data Spedizione</th>
                <th>Data Ritiro</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($spedizione = $stmt->fetch()) { ?>
                <tr>
                    <td><?= $spedizione->codice_plico ?></td>
                    <td><?= $spedizione->mittente ?></td>
                    <td><?= $spedizione->destinatario ?? '--' ?></td>
                    <td><?= $spedizione->stato_plico ?? '--' ?></td>
                    <td><?= $spedizione->data_consegna ? date('d/m/Y H:i', strtotime($spedizione->data_consegna)) : '--' ?></td>
                    <td><?= $spedizione->data_spedizione ? date('d/m/Y H:i', strtotime($spedizione->data_spedizione)) : '--' ?></td>
                    <td><?= $spedizione->data_ritiro ? date('d/m/Y H:i', strtotime($spedizione->data_ritiro)) : '--' ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php require './Template/footer.php'; ?>
