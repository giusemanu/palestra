<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('coach');
    $id_coach = (int)$_SESSION['id_coach'];
    $titolo_pagina = "Dashboard Coach";
    $num_utenti = 0;
    $num_esercizi = 0;
    $num_corsi = 0;
    try{
        $query = "SELECT COUNT(*)
                  FROM utente
                  WHERE coach = :id_coach";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id_coach' => $id_coach]);
        $num_utenti = $stmt->fetchColumn();

        $stmt = $pdo->query("SELECT COUNT(*) FROM esercizio");
        $num_esercizi = $stmt->fetchColumn();

        $stmt = $pdo->query("SELECT COUNT(*) FROM corso");
        $num_corsi = $stmt->fetchColumn();
    }catch(PDOException $e){
        redirect("/palestra/coach/dashboard.php?error=errore_db");
    }
    $titolo_pagina = "Dashboard - Coach";
    require_once __DIR__."/../includes/header.php";
?>

<div class="container">

    <?php mostraFeedback(); ?>
    
    <div class="welcome-header">
        <h1>Bentornato, Coach <?=($_SESSION['nome'])?>!</h1>
        <p>Pannello di controllo rapido.</p>
    </div>

    <div class="dashboard-grid">
        <div class="col-left">
            <div class="card-box">
                <div class="card-icon">👥</div>
                <h3>Gestione Utenti</h3>
                <p>Hai <strong><?= $num_utenti ?></strong> atleti iscritti in totale.</p>
                <a href="utenti.php" class="btn">Vai alla Lista Utenti</a>
            </div>
        </div>

        <div class="col-right">
            <div class="card-box">
                <div class="card-icon">💪</div>
                <h3>Esercizi</h3>
                <p>Database: <strong><?= $num_esercizi ?></strong> esercizi.</p>
                <a href="gestisci_esercizi.php" class="btn btn-outline">Gestisci Esercizi</a>
            </div>

            <div class="card-box">
                <div class="card-icon">📅</div>
                <h3>Corsi</h3>
                <p>Attivi: <strong><?= $num_corsi ?></strong> corsi.</p>
                <a href="visualizza_corsi.php" class="btn btn-outline">Vedi Elenco</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__."/../includes/footer.php"; ?>