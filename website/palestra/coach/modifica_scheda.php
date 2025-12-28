<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('coach');
    $id_utente = (int)$_GET['id_utente'];

    if($id_utente <= 0){
        redirect("utenti.php");
    }

    $dati_scheda = null;
    $sessioni = [];
    try{
        $query = "SELECT s.*, u.nome, u.cognome 
                  FROM scheda s 
                  JOIN utente u ON s.utente = u.id_utente
                  WHERE s.utente = :id_utente 
                  ORDER BY s.data_inizio DESC 
                  LIMIT 1";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':id_utente' => $id_utente
        ]);
        $dati_scheda = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dati_scheda) {
            redirect("nuova_scheda.php?utente=".$id_utente);
        }

        $stmt = $pdo->prepare("SELECT * 
                               FROM sessione_allenamento
                               WHERE scheda = :id_scheda");
        $stmt->execute([':id_scheda' => $dati_scheda['id_scheda']]);
        $sessioni = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }catch (PDOException $e){
        redirect("dashboard.php?error=errore_db");
    }
    $titolo_pagina = "Dettaglio Scheda";
    require_once __DIR__."/../includes/header.php";
?>

<div class="container">
    
    <?php mostraFeedback();?>

    <div class="content-box mb-20">
        <div class="box-header">
            <h3>Scheda di: <?=($dati_scheda['cognome'].' '.$dati_scheda['nome'])?></h3>
            
            <?php 
                $oggi = date('Y-m-d');
                if(!empty($dati_scheda['data_fine']) && $dati_scheda['data_fine'] < $oggi){
                    echo '<span class="badge badge-red">Scaduta</span>';
                }else{
                    echo '<span class="badge badge-green">Attiva</span>';
                }
            ?>
        </div>
        <p>Da questa pagina puoi rinnovare la validità della scheda o modificarne gli esercizi.</p>
    </div>

    <div class="dashboard-grid">
        
        <div class="col-left">
            <div class="content-box h-100">
                <h3>📅 Validità e Date</h3>
                <p>Modifica qui sotto le date per rinnovare la scheda.</p>
                
                <form action="/palestra/actions/update_scheda.php" method="POST">
                    <input type="hidden" name="id_scheda" value="<?=$dati_scheda['id_scheda']?>">
                    
                    <input type="hidden" name="id_utente" value="<?=$dati_scheda['utente']?>">
                    
                    <div class="form-group">
                        <label class="form-label">Data Inizio</label>
                        <input type="date" name="data_inizio" class="form-input" value="<?=$dati_scheda['data_inizio']?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Data Scadenza</label>
                        <input type="date" name="data_fine" class="form-input" value="<?=$dati_scheda['data_fine']?>" required>
                    </div>

                    <button type="submit" class="btn btn-block">💾 Aggiorna Date</button>
                </form>
            </div>
        </div>

        <div class="col-right">
            <div class="content-box h-100">
                <h3>💪 Sessioni di Allenamento</h3>
                <p>Questa scheda contiene <strong><?=count($sessioni)?></strong> sessioni.</p>

                <?php if(count($sessioni) > 0){?>
                    <ul class="session-list">
                        <?php foreach($sessioni as $s){?>
                            <li class="session-item">
                                <span class="session-icon">🔹</span>
                                <strong><?=htmlspecialchars($s['tipo_allenamento'])?></strong>
                            </li>
                        <?php }?>
                    </ul>
                <?php }else{?>
                    <div class="error-msg">
                        Nessuna sessione inserita!
                    </div>
                <?php }?>

                <a href="gestisci_sessione.php?id_scheda=<?=$dati_scheda['id_scheda']?>" class="btn btn-outline btn-block">
                    ✏️ Modifica Sessioni & Esercizi
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__."/../includes/footer.php"; ?>