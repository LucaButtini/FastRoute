<?php
session_start();

$bgColor = isset($_COOKIE['bg-color']) ? $_COOKIE['bg-color'] : 'white';
$page = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
?>

<!doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="Styles/style.css">

    <title><?= htmlspecialchars($title) ?></title>
</head>
<body style="background-color: <?= $bgColor ?>;">

<nav class="navbar navbar-expand-lg bg-primary navbar-dark sticky-top py-3">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><strong>Trasporti FastRoute</strong></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $page == 'index.php' ? 'active' : '' ?>" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Pricing</a>
                </li>
            </ul>

            <?php
            if (isset($_SESSION['user_nome'])) {
                // Se l'utente è loggato, mostra il suo nome e il menu utente
                ?>
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['user_nome']) ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                        <li><a class="dropdown-item" href="Login/impostazioni.php"><i class="bi bi-gear-fill"></i> Impostazioni</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="Login/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                    </ul>
                </div>
                <?php
            } else {
                // Se non è loggato, mostra il pulsante di login
                ?>
                <a class="nav-link text-light" href="Login/login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                <?php
            }
            ?>
        </div>
    </div>
</nav>
