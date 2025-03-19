<?php
$title = 'Inserimento Cliente';
require './Template/header.php';


require 'Config/DBConnection.php';

$conf= require 'Config/db_conf.php';

$db = DbConnection::getDb($conf);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Recupero dei dati dal form
    $codice_fiscale = $_POST['codice_fiscale'];
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $indirizzo = $_POST['indirizzo'];
    $mail = $_POST['mail'];
    $punteggio = $_POST['punteggio'];

    // Verifica se il cliente esiste già (controllo per codice_fiscale)
    $checkQuery = "SELECT * FROM clienti WHERE codice_fiscale = :codice_fiscale";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindValue(':codice_fiscale', $codice_fiscale);
    $checkStmt->execute();
    $existingClient = $checkStmt->fetch();

    if ($existingClient) {
        // Se il cliente è già registrato, mostra un messaggio informativo
        echo "<div class='container mt-5'><h3 class='text-danger'>Cliente con questo codice fiscale già registrato!</h3></div>";
    } else {
        // Inserimento del nuovo cliente
        $query = "INSERT INTO clienti (codice_fiscale, nome, cognome, indirizzo, mail, punteggio) 
                  VALUES (:codice_fiscale, :nome, :cognome, :indirizzo, :mail, :punteggio)";
        $stm = $db->prepare($query);
        $stm->bindValue(':codice_fiscale', $codice_fiscale);
        $stm->bindValue(':nome', $nome);
        $stm->bindValue(':cognome', $cognome);
        $stm->bindValue(':indirizzo', $indirizzo);
        $stm->bindValue(':mail', $mail);
        $stm->bindValue(':punteggio', $punteggio);

        $stm->execute();

        header('Location: confirm.html');
        exit();
    }
}
?>
<!-- Form di inserimento dati cliente -->
    <div class="container mt-5 text-center bg-body-tertiary rounded-4">
    <div class="text-center">
        <h1 class="text-primary"><strong>Inserisci Nuovo Cliente</strong></h1>
        <p class="lead">Compila il form per registrare i dati del cliente.</p>
    </div>

    <form method="post" action="insert_clienti.php">
        <div class="mb-4">
            <label for="codice_fiscale" class="form-label">Codice Fiscale:</label>
            <input type="text" class="form-control" id="codice_fiscale" name="codice_fiscale" placeholder="Codice Fiscale" required>
        </div>
        <div class="mb-4">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" required>
        </div>
        <div class="mb-4">
            <label for="cognome" class="form-label">Cognome:</label>
            <input type="text" class="form-control" id="cognome" name="cognome" placeholder="Cognome" required>
        </div>
        <div class="mb-4">
            <label for="indirizzo" class="form-label">Indirizzo:</label>
            <input type="text" class="form-control" id="indirizzo" name="indirizzo" placeholder="Indirizzo" required>
        </div>
        <div class="mb-4">
            <label for="mail" class="form-label">E-mail:</label>
            <input type="email" class="form-control" id="mail" name="mail" placeholder="Email" required>
        </div>
        <div class="mb-4">
            <label for="punteggio" class="form-label">Punteggio:</label>
            <input type="number" class="form-control" id="punteggio" name="punteggio" placeholder="Punteggio" min="0" required>
        </div>

        <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Aggiungi Cliente</button>
    </form>
</div>

<?php
require './Template/footer.php';