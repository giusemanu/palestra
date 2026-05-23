<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('admin');
    $titolo_pagina = "Dashboard Admin";
    
    try{
        $stmt = $pdo->query("SELECT COUNT(*) FROM coach");
        $num_coach = $stmt->fetchColumn();

        $stmt = $pdo->query("SELECT COUNT(*) FROM utente");
        $num_utenti = $stmt->fetchColumn();

        $stmt = $pdo->query("SELECT COUNT(*) FROM abbonamento WHERE stato = 'Attivo'");
        $num_abbonamenti_attivi = $stmt->fetchColumn();

        $stmt = $pdo->query("SELECT COUNT(*) FROM corso");
        $num_corsi = $stmt->fetchColumn();

    }catch(PDOException $e){
        redirect("/palestra/index.php?error=errore_db");
    }
    
    $titolo_pagina = "Dashboard - Amministratore";
    require_once __DIR__."/../includes/header.php";
    mostraFeedback();    
?>

<div class="container">
    <header class="welcome-header">
        <h1>Ciao, <?=htmlspecialchars($_SESSION['nome'])?>!</h1>
        <p>Pannello di controllo dell'Amministratore. Gestisci lo staff, gli atleti e i piani della palestra.</p>
    </header>

    <div class="dashboard-grid">
        <div class="col-left">
            
            <div class="card-box card-highlight">
                <div class="card-icon">👥</div>
                <h3>Gestione Coach</h3>
                <p>Ci sono <strong><?=$num_coach?></strong> coach registrati nel sistema.</p>
                <a href="gestisci_coach.php" class="btn btn-block">Staff Coach</a>
            </div>

            <div class="card-box">
                <div class="card-icon">🏋️‍</div>
                <h3>Anagrafica Atleti</h3>
                <p>Totale atleti iscritti nella struttura: <strong><?=$num_utenti?></strong>.</p>
                <a href="gestisci_utenti.php" class="btn btn-block">Gestisci Utenti</a>
            </div>
        </div>

        <div class="col-right">
            
            <div class="card-box">
                <div class="card-icon">💳</div>
                <h3>Abbonamenti</h3>
                <p>Piani attualmente contrassegnati come attivi: <strong><?=$num_abbonamenti_attivi?></strong>.</p>
                <a href="gestisci_abbonamenti.php" class="btn btn-outline btn-block">Registro Abbonamenti</a>
            </div>

            <div class="card-box">
                <div class="card-icon">📅</div>
                <h3>Corsi Collettivi</h3>
                <p>Ci sono <strong><?=$num_corsi?></strong> corsi attivi nel palinsesto.</p>
                <a href="gestisci_corsi.php" class="btn btn-outline btn-block">Gestisci Corsi</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__."/../includes/footer.php";?>
