<?php
$title = 'Dettagli Plico';
require './Template/header.php';
require 'Config/DBConnection.php';

$conf = require 'Config/db_conf.php';
$db = DbConnection::getDb($conf);

$plicoInfo = null;
$error = '';

if (isset($_GET['codice_plico']) && !empty($_GET['codice_plico'])) {
    $search = trim($_GET['codice_plico']);
    $query = "
        SELECT p.codice, p.stato, ps.nome_sede, s.citta 
        FROM plichi p
        LEFT JOIN plichi_sedi ps ON p.codice = ps.codice_plico
        LEFT JOIN sedi s ON ps.nome_sede = s.nome
        WHERE p.codice = :codice_plico
        ORDER BY p.codice
    ";
    try {
        $stm = $db->prepare($query);
        $stm->bindValue(':codice_plico', $search);
        $stm->execute();
        $plicoInfo = $stm->fetch();
        $stm->closeCursor();
        if (!$plicoInfo) {
            $error = "Nessun plico trovato con il codice specificato.";
        }
    } catch (Exception $e) {
        $error = "Errore durante il caricamento dei dati.";
    }
}
?>

<div class="container mt-5 rounded-4 p-5 bg-white">
    <div class="text-center pt-3">
        <h1 class="text-primary"><strong>Dettagli Plico Ricercato</strong></h1>
        <p class="lead">Inserisci il codice del plico per visualizzare le sue informazioni:</p>
    </div>

    <!-- Form di ricerca per codice plico -->
    <div class="mb-4">
        <form method="get" action="elenco_plichi.php" class="d-flex justify-content-center">
            <input type="number" name="codice_plico" class="form-control w-50" placeholder="Inserisci il codice plico" min="0" value="<?= isset($search) ? $search : '' ?>">
            <button type="submit" class="btn btn-primary ms-2"><i class="bi bi-search"></i> Cerca</button>
        </form>
    </div>

    <?php if ($error){ ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php }elseif ($plicoInfo){ ?>
        <div class="card mx-auto" style="max-width: 500px;">
            <div class="card-header bg-dark text-white">
                Informazioni Plico
            </div>
            <div class="card-body">
                <p><strong>Codice:</strong> <?= $plicoInfo->codice ?></p>
                <p><strong>Stato:</strong> <?= $plicoInfo->stato ?></p>
                <p><strong>Sede:</strong> <?= $plicoInfo->nome_sede ?></p>
                <p><strong>Città:</strong> <?= $plicoInfo->citta ?></p>
            </div>
        </div>
    <?php } ?>
</div>

<?php
require './Template/footer.php';
?>
