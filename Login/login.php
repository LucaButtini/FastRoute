<?php
require __DIR__ . '/../Config/DbConnection.php';
$conf = require __DIR__ . '/../Config/db_conf.php';

$db = DbConnection::getDb($conf);

// 🔍 Controlla se la tabella "personale" è vuota; se sì, inserisce gli utenti di default con password hashata
$stmt = $db->query("SELECT COUNT(*) as count FROM personale");
$countRow = $stmt->fetch(PDO::FETCH_OBJ);

if ($countRow->count == 0) {
    $users = [
        ['CF123456789', 'Giovanni Rossi', 'giovanni.rossi@email.com', 'Sede Milano'],
        ['CF987654321', 'Maria Bianchi', 'maria.bianchi@email.com', 'Sede Roma'],
        ['CF112233445', 'Luca Verdi', 'luca.verdi@email.com', 'Sede Napoli'],
        ['CF998877665', 'Anna Gialli', 'anna.gialli@email.com', 'Sede Torino'],
        ['CF667788991', 'Marco Blu', 'marco.blu@email.com', 'Sede Bologna'],
        ['CF223344556', 'Elena Rosa', 'elena.rosa@email.com', 'Sede Firenze'],
        ['CF334455667', 'Stefano Azzurri', 'stefano.azzurri@email.com', 'Sede Venezia'],
        ['CF445566778', 'Paola Neri', 'paola.neri@email.com', 'Sede Palermo'],
        ['CF556677889', 'Giulia Verde', 'giulia.verde@email.com', 'Sede Genova'],
        ['CF667788992', 'Francesco Arancio', 'francesco.arancio@email.com', 'Sede Bari'],
    ];

    // Hash della password "admin123"
    $password_hash = password_hash("admin123", PASSWORD_DEFAULT);

    $stmtInsert = $db->prepare("INSERT INTO personale (codice_fiscale, nome, mail, password, sede) VALUES (?, ?, ?, ?, ?)");
    foreach ($users as $user) {
        $stmtInsert->execute([$user[0], $user[1], $user[2], $password_hash, $user[3]]);
    }
    //echo "✅ Utenti di default inseriti!<br>";
}

$error = "";

// Precompila l'email dal cookie "remember_me", se presente
$email_salvata = isset($_COOKIE['remember_me']) ? $_COOKIE['remember_me'] : '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Query per ottenere i dati dell'utente, usando fetch(PDO::FETCH_OBJ)
    $stmt = $db->prepare("SELECT codice_fiscale, password, nome FROM personale WHERE mail = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_OBJ);

    if ($user) {
        // Verifica la password hashata
        if (password_verify($password, $user->password)) {
            $_SESSION['user_id'] = $user->codice_fiscale;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_nome'] = $user->nome;

            // Se "Ricordami" è selezionato, salva il cookie per 30 giorni
            if (isset($_POST['remember'])) {
                setcookie("remember_me", $email, time() + (86400 * 30), "/");
            } else {
                // Se non selezionato, elimina il cookie esistente
                setcookie("remember_me", "", time() - 3600, "/");
            }

            header("Location: ../index.php");
            exit();
        } else {
            $error = "⚠️ Password errata.";
        }
    } else {
        $error = "⚠️ Utente non trovato.";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login - FastRoute</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Login - FastRoute</h1>

    <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>

    <form action="login.php" method="post" class="w-50 mx-auto">
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= $email_salvata ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" id="remember" class="form-check-input">
            <label for="remember" class="form-check-label">Ricordami</label>
        </div>
        <button type="submit" class="btn btn-primary w-100">Accedi</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
