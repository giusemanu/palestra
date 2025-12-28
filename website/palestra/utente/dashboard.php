<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('utente');
    $titolo_pagina = "Dashboard Utente";
    $id_utente = $_SESSION['id_utente'];
    try{
        $stmt = $pdo->prepare("SELECT c.*
                               FROM coach c
                               JOIN utente u ON c.id_coach = u.coach
                               WHERE u.id_utente = :id_utente");
        $stmt->execute([':id_utente' => $id_utente]);
        $coach = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT *
                               FROM scheda
                               WHERE utente = :id_utente
                               ORDER BY data_inizio DESC
                               LIMIT 1");
        $stmt->execute([':id_utente' => $id_utente]);
        $scheda = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT *
                               FROM partecipa
                               WHERE utente = :id_utente");
        $stmt->execute([":id_utente" => $id_utente]);
        $corsi = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $e){
        redirect("/palestra/index.php?error=errore_db");
    }
    $titolo_pagina = "Dashboard - Utente";
    require_once __DIR__."/../includes/header.php";
    mostraFeedback();    
?>

<div class="container">
    <header class="welcome-header">
        <h1>Ciao, <?=htmlspecialchars($_SESSION['nome'])?>!</h1>
        <p>
            <?php if($coach){?>
                <div class="coach-box">
                    <p>Coach : <strong><?=htmlspecialchars($coach['nome'].' '.$coach['cognome'])?></strong> 
                        <label for="info-toggle" class="btn-info">ℹ️</label>
                    </p>

                    <input type="checkbox" id="info-toggle" class="hidden-check">

                    <div class="modal">
                        <div class="modal-content">
                            <label for="info-toggle" class="close-btn">&times;</label>
                            <h3>Informazioni sul tuo Coach</h3>
                            <p>Email : <?=htmlspecialchars($coach['email'])?></p>
                            <p>Telefono : <?=htmlspecialchars($coach['telefono'])?></p>
                            <p>Data Nascita : <?=htmlspecialchars(date('d/m/Y', strtotime($coach['data_nascita'])))?></p> 
                        </div>
                    </div>
            </div>
            <?php }else{?>
                Non hai ancora un coach assegnato.
            <?php }?>
        </p>
    </header>

    <div class="dashboard-grid">
        <div class="col-left">
            <div class="card-box">
                <div class="card-icon">🏋️‍♂️</div>
                <h3>La tua Scheda</h3>
                <?php if($scheda){?>
                    <p>
                        Scheda attiva dal:<br>
                        <strong><?=date('d/m/Y', strtotime($scheda['data_inizio']))?></strong>
                    </p>
                    <a href="vedi_scheda.php?id_scheda=<?=$scheda['id_scheda']?>" class="btn">Apri Scheda</a>
                <?php }else{?>
                    <p>Non hai una scheda attiva al momento.</p>
                    <button class="btn btn-disabled" disabled>In attesa del Coach</button>
                <?php }?>
            </div>
        </div>

        <div class="col-right">
            <div class="card-box">
                <div class="card-icon">📅</div>
                <h3>Corsi</h3>
                <p>Sei iscritto a <strong><?=count($corsi)?></strong> corsi.</p>
                <a href="corsi_disponibili.php" class="btn btn-outline">Prenota Corso</a>
            </div>
            <div class="card-box">
                <div class="card-icon">📈</div>
                <h3>Progressi</h3>
                <p>Monitora i tuoi miglioramenti.</p>
                <a href="progressi.php" class="btn btn-outline-info">Vedi Storico</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__."/../includes/footer.php";?>