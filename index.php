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
        $mail->addAddress($to); // Invia la mail al mittente (cliente)

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
                            Gestisci facilmente le spedizioni dei tuoi plichi con il nostro sistema avanzato di tracciamento e registrazione.
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
                            Monitora il percorso del tuo plico in tempo reale dal momento della consegna fino al ritiro da parte del destinatario.
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
                            Accedi a una dashboard intuitiva per monitorare lo stato delle spedizioni e gestire tutte le operazioni in modo efficiente.
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
                            Inserisci il codice del tuo plico e scopri immediatamente se è in partenza, in transito o consegnato.
                        </div>
                    </div>
                </div>

                <!-- Servizio 5: Ricerca Consegne -->
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                            <i class="bi bi-search me-2"></i> Ricerca Consegne per Periodo
                        </button>
                    </h2>
                    <div id="flush-collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body fs-5 lh-lg text-muted">
                            Controlla quante consegne sono state effettuate negli ultimi N giorni con statistiche dettagliate.
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
            <p class="lead">Per rimanere aggiornato sulle novità offerte dalla nostra applicazione inserisci i tuoi dati.</p>
            <form action="richiesta_info.php" method="post" class="w-50 mx-auto">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary">Invia Richiesta</button>
            </form>
        </div>
    </div>


<?php require './Template/footer.php'; ?>
