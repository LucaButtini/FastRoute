<?php
$title = "FastRoute - Home";
require 'Config/DBConnection.php';
$conf = require 'Config/db_conf.php';
$db = DbConnection::getDb($conf);
require './Template/header.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recupera i dati inviati dal form "Richiedi Informazioni"
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);

    // Costruisci il corpo della mail con informazioni di esempio
    $body = "Gentile $nome,\n\n" .
        "Grazie per aver richiesto informazioni sui nostri servizi.\n\n" .
        "Ecco alcune informazioni aggiuntive:\n" .
        "- Orari di apertura: dal lunedì al venerdì, 8:00 - 18:00\n" .
        "- Costi di spedizione: a partire da €5 (variabili in base alla distanza)\n" .
        "- Modalità di spedizione: express, standard ed economica\n\n" .
        "Per ulteriori dettagli, non esitare a contattarci.\n\n" .
        "Cordiali saluti,\n" .
        "Il team FastRoute";

    // Invia la mail di informazioni
    sendMailInfo($email, "Richiesta Informazioni - FastRoute", $body);

    header('Location: confirm.html');


}

function sendMailInfo($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Gmail SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'luca.buttini@iisviolamarchesini.edu.it';
        $mail->Password = '';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('fastroute@mail.com', 'FastRoute');
        $mail->addAddress($to);

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

<div class="row">
    <div class="col-12 p-3">
        <h1 class="text-danger"><strong>Portale Trasporti FastRoute</strong></h1>
        <p class="lead mt-3">Il portale per la gestione e il tracciamento delle tue spedizioni.</p>
        <img src="Immagini/sitoImm.jpeg" alt="Corriere FastRoute" class="img-fluid rounded" id="img-home">
    </div>
</div>

<div class="row">
    <div class="col-12">
        <h2><strong>I nostri Servizi</strong></h2>
        <p class="lead">Consulta tutti i nostri servizi.</p>
        <div class="accordion accordion-flush mt-3" id="accordionFlushExample">

            <!-- Servizio 1: Gestione delle spedizioni -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                        <i class="bi bi-box-seam me-2"></i> Gestione delle Spedizioni
                    </button>
                </h2>
                <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body fs-5 lh-lg text-muted">
                        Utilizza la nostra interfaccia intuitiva per inserire, modificare e monitorare ogni fase della spedizione, dalla registrazione iniziale alla consegna finale, con notifiche in tempo reale e aggiornamenti costanti.
                    </div>
                </div>
            </div>

            <!-- Servizio 2: Tracciamento dei Plichi -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                        <i class="bi bi-truck me-2"></i> Tracciamento dei Plichi
                    </button>
                </h2>
                <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body fs-5 lh-lg text-muted">
                        Segui in tempo reale il percorso del tuo plico, dal momento in cui lascia la nostra sede fino al ritiro da parte del destinatario, grazie a un sistema di tracking che fornisce aggiornamenti dettagliati su ogni tappa del viaggio.
                    </div>
                </div>
            </div>

            <!-- Servizio 3: Dashboard di Controllo -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                        <i class="bi bi-table me-2"></i> Dashboard di Controllo
                    </button>
                </h2>
                <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body fs-5 lh-lg text-muted">
                        Visualizza una dashboard centralizzata e interattiva che offre statistiche, grafici e report sullo stato di tutte le spedizioni, facilitando la gestione operativa e la risoluzione tempestiva di eventuali criticità.
                    </div>
                </div>
            </div>

            <!-- Servizio 4: Verifica Stato Plico -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                        <i class="bi bi-check-circle me-2"></i> Verifica Stato Plico
                    </button>
                </h2>
                <div id="flush-collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body fs-5 lh-lg text-muted">
                        Inserisci il codice identificativo del tuo plico e ricevi in pochi secondi informazioni dettagliate sullo stato attuale, che ti indicherà se il plico è in partenza, in transito oppure già consegnato, mostrando anche le tappe già completate.
                    </div>
                </div>
            </div>

            <!-- Servizio 5: Ricerca Consegne per Periodo -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                        <i class="bi bi-search me-2"></i> Ricerca Consegne per Periodo
                    </button>
                </h2>
                <div id="flush-collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body fs-5 lh-lg text-muted">
                        Consulta il numero di consegne effettuate in un intervallo di tempo specificato (ultimi N giorni), visualizzando il totale e la distribuzione giornaliera per analizzare l'andamento delle operazioni e pianificare al meglio le attività future.
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- Sezione Form di Contatto -->
<div class="row mt-5">
    <div class="col-12 mb-4">
        <h2><strong>Richiedi Informazioni</strong></h2>
        <p class="lead">Per rimanere aggiornato sulle novità offerte dalla nostra applicazione, inserisci i tuoi dati.</p>
        <form action="index.php" method="post" class="w-50 mx-auto">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-dark ms-2"><i class="bi bi-envelope"></i> Invia richiesta</button>
        </form>
    </div>
</div>

<?php require './Template/footer.php'; ?>
