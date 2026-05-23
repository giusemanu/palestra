<?php
    require_once __DIR__ . "/../config/db_conn.php";
    require_once __DIR__ . "/../includes/functions.php";
    check_login('coach');
    $id_scheda = (int)$_GET['id_scheda'];
    $id_coach = (int)$_SESSION['id_coach'];
    if($id_scheda <= 0){
        redirect("dashboard.php");
    }
    try{
        $query = "SELECT s.*, u.nome, u.cognome, u.id_utente
                  FROM scheda s 
                  JOIN utente u ON s.utente = u.id_utente
                  WHERE s.id_scheda = :id_scheda";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([":id_scheda" => $id_scheda]);
        $scheda = $stmt->fetch(PDO::FETCH_ASSOC);            
            
        if (!$scheda){
            redirect("utenti.php?error=scheda_non_trovata");
        }

        $query_sessioni = "SELECT *
                           FROM sessione_allenamento 
                           WHERE scheda = :id_scheda 
                           ORDER BY id_sessione_allenamento ASC";
        $stmt = $pdo->prepare($query_sessioni);
        $stmt->execute([":id_scheda" => $id_scheda]);
        $sessioni = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $e){
        redirect("dashboard.php?error=errore_db");
    }
    $titolo_pagina = "Gestione Sessioni";
    require_once __DIR__."/../includes/header.php";
?>

<div class="container">
    
    <?php mostraFeedback(); ?>

    <div class="content-box mb-20">
        <div class="box-header">
            <h3>Sessioni di: <?=htmlspecialchars($scheda['cognome'].' '.$scheda['nome'])?></h3>
            <span class="badge badge-blue">Scheda #<?=$id_scheda?></span>
        </div>
    </div>

    <div class="dashboard-grid">
        
        <div class="col-left">
            <div class="content-box h-100">
                <h3>➕ Nuova Sessione</h3>
                <p class="mb-20">Aggiungi un giorno di allenamento (es. Giorno A, Pull, Gambe).</p>
                
                <form action="/palestra/actions/add_sessione.php" method="POST">
                    <input type="hidden" name="id_scheda" value="<?=$id_scheda?>">
                    
                    <div class="form-group">
                        <label class="form-label" for="tipo">Nome Sessione / Giorno</label>
                        <input type="text" name="tipo_allenamento" id="tipo_allenamento" class="form-input" placeholder="Es. Lunedì - Petto" required>
                    </div>
                    
                    <button type="submit" class="btn btn-block">Aggiungi Sessione</button>
                </form>
            </div>
        </div>

        <div class="col-right">
            <div class="content-box h-100">
                <h3>📋 Sessioni Attuali (<?=count($sessioni)?>)</h3>

                <?php if(count($sessioni) > 0){?>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th class="text-left">Nome Sessione</th>
                                    <th class="text-center">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sessioni as $sess){?>
                                    <tr>
                                        <td class="text-left">
                                            <strong><?=htmlspecialchars($sess['tipo_allenamento'])?></strong>
                                        </td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="gestisci_esercizi_sessione.php?id_sessione=<?=$sess['id_sessione_allenamento']?>" class="btn btn-sm">
                                                    💪 Esercizi
                                                </a>
                                                
                                                <form action="/palestra/actions/delete_item.php" method="POST" class="form-inline">
                                                    <input type="hidden" name="id" value="<?=$sess['id_sessione_allenamento']?>">
                                                    <input type="hidden" name="entita" value="sessione">
                                                    <input type="hidden" name="redirect_id" value="<?=$id_scheda?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                <?php }else{?>
                    <div class="error-msg mt-20">
                        Nessuna sessione presente.<br>Aggiungine una dal box a fianco.
                    </div>
                <?php }?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>