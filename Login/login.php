<?php
session_start();
$title = 'Login';

require_once __DIR__ . '/../Config/DbConnection.php';
$config = require_once __DIR__ . '/../Config/db_conf.php';

// Connessione al database
$db = DbConnection::getDb($config);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Query per cercare l'utente nel database in base all'email
    $stmt = $db->prepare("SELECT codice_fiscale, password, nome FROM personale WHERE mail = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verifica della password con password_verify()
        if (password_verify($password, $user['password'])) {
            // Imposta le variabili di sessione per l'utente autenticato
            $_SESSION['user_id'] = $user['codice_fiscale'];
            $_SESSION['user_nome'] = $user['nome'];
            $_SESSION['user_email'] = $email;

            // Se l'utente ha selezionato "Ricordami", imposta un cookie per 30 giorni
            if (isset($_POST['remember'])) {
                setcookie("remember_me", $email, time() + (86400 * 30), "/");
            }

            // Reindirizza alla dashboard o ad un'altra pagina protetta
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Password errata.";
        }
    } else {
        $error = "Utente non trovato.";
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?> - FastRoute</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Login - FastRoute</h1>
    <?php if ($error): ?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    <form action="login.php" method="post" class="w-50 mx-auto">
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" id="email" class="form-control" required>
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
