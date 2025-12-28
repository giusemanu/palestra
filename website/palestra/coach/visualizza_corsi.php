<?php
    require_once __DIR__ . "/../config/db_conn.php";
    require_once __DIR__ . "/../includes/functions.php";
    check_login('coach');
    $id_coach = $_SESSION['id_coach'];
    try {
        $query = "SELECT DISTINCT c.*
                  FROM corso c
                  JOIN partecipa p ON p.corso = c.id_corso
                  JOIN utente u ON p.utente = u.id_utente
                  WHERE u.coach = :id_coach
                  ORDER BY c.nome_corso ASC";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id_coach' => $id_coach]);
        $corsi = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $e){
        redirect("/palestra/coach/dashboard.php?error=errore_db");
    }
    $titolo_pagina = "Registro Iscritti Corsi";
    require_once __DIR__ . "/../includes/header.php";
?>

<div class="container">
    
    <?php mostraFeedback();?>

    <div class="welcome-header">
        <h1>📅 Registro Corsi</h1>
        <p>Visualizzazione dei corsi frequentati dai tuoi atleti.</p>
    </div>

    <?php if (count($corsi) > 0){?>
        
        <div class="cards-grid">
            
            <?php foreach ($corsi as $corso){?>
                
                <?php
                    // Per ogni corso, recuperiamo solo i TUOI atleti iscritti
                    $sql_iscritti = "SELECT u.nome, u.cognome, u.email 
                                     FROM partecipa p
                                     JOIN utente u ON p.utente = u.id_utente
                                     WHERE p.corso = :id_corso
                                     AND u.coach = :id_coach
                                     ORDER BY u.cognome ASC";
                    
                    $stmt_sub = $pdo->prepare($sql_iscritti);
                    $stmt_sub->execute([
                        ':id_corso' => $corso['id_corso'],
                        ':id_coach' => $id_coach
                    ]);
                    $iscritti = $stmt_sub->fetchAll(PDO::FETCH_ASSOC);
                    $num_iscritti = count($iscritti);
                ?>

                <div class="course-card">
                    
                    <div class="course-header">
                        <h3><?=htmlspecialchars($corso['nome_corso'])?></h3>
                        <div class="text-muted-white">
                            Atleti iscritti : <?=$num_iscritti?>
                        </div>
                    </div>

                    <div class="course-body">
                        <?php if ($num_iscritti > 0){?>
                            
                            <ul class="session-list scroll-list">
                                <?php foreach ($iscritti as $atleta){?>
                                    <li class="session-item">
                                        <span class="session-icon">👤</span>
                                        <div>
                                            <strong><?=htmlspecialchars($atleta['cognome']." ".$atleta['nome'])?></strong>
                                            <div class="text-muted">
                                                <?=htmlspecialchars($atleta['email'])?>
                                            </div>
                                        </div>
                                    </li>
                                <?php }?>
                            </ul>

                        <?php }else{?>
                            <p class="text-center text-muted">
                                Nessun dato disponibile.
                            </p>
                        <?php }?>
                    </div>
                </div>

            <?php }?>

        </div>

    <?php }else{?>
        <div class="content-box">
            <p class="text-center">Non ci sono corsi attivi con tuoi iscritti.</p>
        </div>
    <?php }?>

</div>

<?php require_once __DIR__."/../includes/footer.php"; ?>