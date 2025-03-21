<?php
$title = 'Inserimento Destinatario';
require 'Config/DBConnection.php';
$conf= require 'Config/db_conf.php';
$db = DbConnection::getDb($conf);
require './Template/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Recupero dei dati dal form
    $codice_fiscale = $_POST['codice_fiscale'];
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];

    // Verifica se il destinatario esiste già (controllo per codice_fiscale)
    $checkQuery = "SELECT * FROM destinatari WHERE codice_fiscale = :codice_fiscale";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindValue(':codice_fiscale', $codice_fiscale);
    $checkStmt->execute();
    $existingRecipient = $checkStmt->fetch();

    if ($existingRecipient) {
        // Se il destinatario è già registrato, mostra un messaggio informativo
        echo "<div class='container mt-5'><h3 class='text-danger'>Destinatario con questo codice fiscale già registrato!</h3></div>";
    } else {
        // Inserimento del nuovo destinatario
        $query = "INSERT INTO destinatari (codice_fiscale, nome, cognome) 
                  VALUES (:codice_fiscale, :nome, :cognome)";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':codice_fiscale', $codice_fiscale);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':cognome', $cognome);

        $stmt->execute();

        header('Location: confirm.html');
        exit();
    }
}
?>

<!-- Form di inserimento dati destinatario -->

    <div class="text-center">
        <h1 class="text-primary"><strong>Inserisci Nuovo Destinatario</strong></h1>
        <p class="lead">Compila il form per registrare i dati del destinatario.</p>
    </div>

    <form method="post" action="insert_destinatari.php">
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

        <button type="submit" class="btn btn-dark"><i class="bi bi-plus-circle"></i> Aggiungi Destinatario</button>
    </form>


<?php
require './Template/footer.php';
?>
