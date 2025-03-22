<?php
$title = "FastRoute - Ricerca Consegne";
require 'Config/DBConnection.php';
$conf = require 'Config/db_conf.php';
$db = DbConnection::getDb($conf);
require './Template/header.php';

// Verifica se il numero di giorni è stato fornito
$days = isset($_POST['days']) ? (int)$_POST['days'] : 0;

// Variabile per i risultati
$consegneTotali = 0;
$consegnePerGiorno = [];

if ($days > 0) {
    $query = "
        SELECT 
            DATE(con.data) AS giorno_consegna,
            COUNT(con.codice_plico) AS numero_consegne
        FROM consegne con
        WHERE con.data >= CURDATE() - INTERVAL :days DAY
        GROUP BY giorno_consegna
        ORDER BY giorno_consegna DESC
    ";

    try {
        // Preparazione e esecuzione della query
        $stmt = $db->prepare($query);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();

        // Calcolo delle consegne totali e per giorno utilizzando FETCH_OBJ
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $giorno = $row->giorno_consegna;
            $numeroConsegne = $row->numero_consegne;
            $consegneTotali += $numeroConsegne;
            $consegnePerGiorno[$giorno] = $numeroConsegne;
        }
    } catch (Exception $e) {
        $errorMessage = "Errore durante il caricamento dei dati: " . $e->getMessage();
    }
}

?>

<div class="container mt-5">
    <h1 class="text-primary"><strong>Ricerca Consegne Recenti</strong></h1>
    <p class="lead">Inserisci il numero di giorni per visualizzare il numero di plichi consegnati.</p>

    <!-- Form per l'inserimento dei giorni -->
    <form method="POST" class="mb-4">
        <div class="form-group">
            <label for="days">Numero di giorni:</label>
            <input type="number" name="days" id="days" class="form-control" value="<?= $days ?>" min="1" required>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Cerca</button>
    </form>

    <?php if (isset($errorMessage)) { ?>
        <div class="alert alert-danger"><?= $errorMessage ?></div>
    <?php } ?>

    <?php if ($days > 0) { ?>
        <div class="mt-4">
            <h3>Numero totale di consegne negli ultimi <?= $days ?> giorni: <?= $consegneTotali ?></h3>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                <tr>
                    <th>Giorno di Consegna</th>
                    <th>Numero Consegne</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($consegnePerGiorno as $giorno => $numeroConsegne) { ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($giorno)) ?></td>
                        <td><?= $numeroConsegne ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>

<?php require './Template/footer.php'; ?>
