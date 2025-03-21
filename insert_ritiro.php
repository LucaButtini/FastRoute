<?php
$title = 'Inserimento Ritiro';
require 'Config/DBConnection.php';
$conf= require 'Config/db_conf.php';
$db = DbConnection::getDb($conf);
require './Template/header.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recupero dei dati dal form
    $destinatario = $_POST['destinatario'];   // Codice fiscale del destinatario
    $codice_plico = $_POST['codice_plico'];
    $data_ritiro = $_POST['data_ritiro'];       // formato datetime-local

    // Verifica se il ritiro esiste già (facoltativo)
    $checkQuery = "SELECT * FROM ritiri WHERE destinatario = :destinatario AND codice_plico = :codice_plico";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindValue(':destinatario', $destinatario);
    $checkStmt->bindValue(':codice_plico', $codice_plico);
    $checkStmt->execute();
    $existingRitiro = $checkStmt->fetch();

    if ($existingRitiro) {
        $error = "Ritiro per questo plico e destinatario è già stato registrato!";
    } else {
        $query = "INSERT INTO ritiri (destinatario, codice_plico, data) 
                  VALUES (:destinatario, :codice_plico, :data_ritiro)";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':destinatario', $destinatario);
        $stmt->bindValue(':codice_plico', $codice_plico);
        $stmt->bindValue(':data_ritiro', $data_ritiro);

        if ($stmt->execute()) {
            $success = "Ritiro registrato correttamente!";
            // Qui, eventualmente, potresti aggiungere la logica per l'invio dell'email di conferma
            header('Location: confirm.html');
            exit();
        } else {
            $error = "Errore durante la registrazione del ritiro.";
        }
    }
}

function sendMail(){
    $mail = new PHPMailer(true);
    /*try{
        $mail->SMTPDebug=2;
        $mail->isSMTP();
        $mail->Host='smtp.gmail.com'; //gmail smtp server
        $mail->SMTPAuth=true;
        $mail->Username='luca.buttini@iisviolamarchesini.edu.it';
        $mail->Password= '';
        $mail->SMTPSecure=PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port=587;
        $mail->setFrom('fastroute@mail.com');
        $mail->addAddress('luca.buttini@iisviolamarchesini.edu.it');
        $mail->Subject='Conferma ritiro plico';
        $mail->Body='Il tuo plico è stato ritirato';
        $mail->CharSet='UTF-8';
        $mail->Encoding='base64';
        $mail->send();
        echo 'Email sent successfuly';
    }catch (\Exception $e){
        echo "Error: {$mail->ErrorInfo}";
    }*/

}
?>

<div class="container mt-5 text-center bg-body-tertiary rounded-4">
    <h1 class="text-primary"><strong>Inserisci Ritiro</strong></h1>
    <p class="lead">Registra il ritiro del plico effettuato dal destinatario.</p>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

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
</div>

<?php
require './Template/footer.php';
?>
