<?php
$title = 'Inserimento Ritiro';
require 'Config/DBConnection.php';
$conf = require 'Config/db_conf.php';
$db = DbConnection::getDb($conf);
require './Template/header.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recupero dati dal form
    $destinatario = $_POST['destinatario']; // Codice fiscale del destinatario
    $codice_plico = $_POST['codice_plico'];
    $data_ritiro = $_POST['data_ritiro']; // Formato datetime-local

    // Verifica se il ritiro esiste già
    $checkQuery = "SELECT * FROM ritiri WHERE destinatario = :destinatario AND codice_plico = :codice_plico";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindValue(':destinatario', $destinatario);
    $checkStmt->bindValue(':codice_plico', $codice_plico);
    $checkStmt->execute();
    $existingRitiro = $checkStmt->fetch();

    if ($existingRitiro) {
        $error = "Ritiro già registrato per questo plico e destinatario!";
    } else {
        // Recupera l'email del mittente (cliente)
        $mittenteQuery = "SELECT c.mail AS email_mittente 
                          FROM consegne co
                          JOIN clienti c ON co.cliente = c.codice_fiscale
                          WHERE co.codice_plico = :codice_plico";
        $mittenteStmt = $db->prepare($mittenteQuery);
        $mittenteStmt->bindValue(':codice_plico', $codice_plico);
        $mittenteStmt->execute();
        $mittente = $mittenteStmt->fetch();

        if (!$mittente) {
            $error = "Errore: impossibile trovare il mittente di questo plico.";
        } else {
            $email_mittente = $mittente->email_mittente;

            // Recupera il nome e il cognome del destinatario dalla tabella 'destinatari'
            $destQuery = "SELECT nome, cognome FROM destinatari WHERE codice_fiscale = :destinatario";
            $destStmt = $db->prepare($destQuery);
            $destStmt->bindValue(':destinatario', $destinatario);
            $destStmt->execute();
            $destData = $destStmt->fetch();

            if ($destData) {
                $nomeDestinatario = $destData->nome . " " . $destData->cognome;
            } else {
                $nomeDestinatario = "Destinatario Sconosciuto";
            }

            // Inserisci il ritiro nel database
            $query = "INSERT INTO ritiri (destinatario, codice_plico, data) 
                      VALUES (:destinatario, :codice_plico, :data_ritiro)";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':destinatario', $destinatario);
            $stmt->bindValue(':codice_plico', $codice_plico);
            $stmt->bindValue(':data_ritiro', $data_ritiro);

            if ($stmt->execute()) {
                $success = "Ritiro registrato correttamente!";

                // Costruzione del corpo della mail
                $mailBody = "Gentile cliente,\n\n" .
                    "Il plico con codice: $codice_plico è stato ritirato il $data_ritiro dal destinatario $nomeDestinatario.\n\n" .
                    "Grazie per aver scelto FastRoute!";

                // Invia email di conferma al mittente (cliente)
                sendMail($email_mittente, "Conferma Ritiro Plico", $mailBody);

                header('Location: confirm.html');
                exit();
            } else {
                $error = "Errore durante la registrazione del ritiro.";
            }
        }
    }
}

function sendMail($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Gmail SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'luca.buttini@iisviolamarchesini.edu.it';
        $mail->Password = ''; // Inserisci qui la password corretta
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('fastroute@mail.com', 'FastRoute');
        $mail->addAddress('luca.buttini@iisviolamarchesini.edu.it'); // Invia la mail al mittente (cliente)

        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        $mail->send();
    } catch (Exception $e) {
        echo "Errore nell'invio della mail a $to: {$mail->ErrorInfo}";
    }
}
?>


    <h1 class="text-primary"><strong>Inserisci Ritiro</strong></h1>
    <p class="lead">Registra il ritiro del plico effettuato dal destinatario.</p>

    <?php if ($error){ ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php } elseif ($success){ ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php } ?>

    <form method="post" action="insert_ritiro.php">
        <div class="mb-4">
            <label for="destinatario" class="form-label">Codice Fiscale Destinatario:</label>
            <input type="text" class="form-control" id="destinatario" name="destinatario" placeholder="Codice Fiscale" required>
        </div>
        <div class="mb-4">
            <label for="codice_plico" class="form-label">Codice Plico:</label>
            <input type="number" class="form-control" id="codice_plico" name="codice_plico" placeholder="Codice Plico" required>
        </div>
        <div class="mb-4">
            <label for="data_ritiro" class="form-label">Data e Ora Ritiro:</label>
            <input type="datetime-local" class="form-control" id="data_ritiro" name="data_ritiro" required>
        </div>
        <button type="submit" class="btn btn-dark"><i class="bi bi-plus-circle"></i> Registra Ritiro</button>
    </form>


<?php
require './Template/footer.php';
?>
