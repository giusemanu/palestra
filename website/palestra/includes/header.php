<?php
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    require_once __DIR__."/../config/db_conn.php";
    if(!isset($titolo_pagina)){
        $titolo_pagina = "Gestione Palestra";
    }
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titolo_pagina);?></title>
    <link rel="stylesheet" href="/palestra/assets/css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="container navbar-content">
                <a href="/palestra/index.php">Home</a>
                
                <div class="nav-links">
                    <?php if(!isset($_SESSION['ruolo'])){?>
                        <a href="/palestra/index.php">Login</a>
                    
                    <?php }else{?>
                        <?php if($_SESSION['ruolo']=='coach'){?>
                            <a href="/palestra/coach/dashboard.php">Dashboard</a>
                            <a href="/palestra/coach/nuova_scheda.php">Crea scheda</a>
                        <?php }?>

                        <?php if($_SESSION['ruolo']=='utente'){?>
                            <a href="/palestra/utente/dashboard.php">Dashboard</a>
                            <a href="/palestra/utente/storico.php">Archivio Schede</a>
                        <?php }?>

                        <a href="/palestra/actions/logout.php">Logout</a>
                    <?php }?>
                </div>
            </div>
        </nav>
    </header>
    
    <main class="container">