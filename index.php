<?php
$title = "FastRoute - Home";
require './Template/header.php';
?>

    <div class="container mt-5 text-center bg-white rounded-4">
        <div class="row">
            <div class="col-12 p-3">
                <h1 class="text-danger"><strong>Portale Trasporti FastRoute</strong></h1>
                <p class="lead mt-3">Il portale per la gestione e il tracciamento delle tue spedizioni.</p>
                <img src="Immagini/sitoImm.jpeg" alt="Corriere FastRoute" class="img-fluid rounded" id="img-home">
            </div>
        </div>

        <!-- Sezione Servizi con Accordion -->
        <div class="row"> <!-- Ridotto da mt-5 a mt-3 -->
            <div class="col-12">
                <h2>I nostri Servizi</h2>
                <div class="accordion accordion-flush mt-3" id="accordionFlushExample">

                    <!-- Servizio 1: Gestione delle spedizioni -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                📦 Gestione delle Spedizioni
                            </button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                Gestisci facilmente le spedizioni dei tuoi plichi con il nostro sistema avanzato di tracciamento e registrazione.
                            </div>
                        </div>
                    </div>

                    <!-- Servizio 2: Tracciamento dei Plichi -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                🚛 Tracciamento dei Plichi
                            </button>
                        </h2>
                        <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                Monitora il percorso del tuo plico in tempo reale dal momento della consegna fino al ritiro da parte del destinatario.
                            </div>
                        </div>
                    </div>

                    <!-- Servizio 3: Dashboard di Controllo -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                📊 Dashboard di Controllo
                            </button>
                        </h2>
                        <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                Accedi a una dashboard intuitiva per monitorare lo stato delle spedizioni e gestire tutte le operazioni in modo efficiente.
                            </div>
                        </div>
                    </div>

                    <!-- Servizio 4: Verifica Stato Plico -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                                🔍 Verifica Stato Plico
                            </button>
                        </h2>
                        <div id="flush-collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                Inserisci il codice del tuo plico e scopri immediatamente se è in partenza, in transito o consegnato.
                            </div>
                        </div>
                    </div>

                    <!-- Servizio 5: Ricerca Consegne -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                                📅 Ricerca Consegne per Periodo
                            </button>
                        </h2>
                        <div id="flush-collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                Controlla quante consegne sono state effettuate negli ultimi N giorni con statistiche dettagliate.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Sezione Form di Contatto -->
        <div class="row mt-5">
            <div class="col-12">
                <h2>Richiedi Informazioni</h2>
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
    </div>

<?php require './Template/footer.php'; ?>