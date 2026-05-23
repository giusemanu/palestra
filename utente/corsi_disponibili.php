<?php
    require_once __DIR__ . "/../config/db_conn.php";
    require_once __DIR__ . "/../includes/functions.php";
    check_login('utente');
    $id_utente = $_SESSION['id_utente'];
    try{
        $stmt = $pdo->query("SELECT * FROM corso ORDER BY nome_corso ASC");
        $elenco_corsi = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT corso FROM partecipa WHERE utente = :id_utente");
        $stmt->execute([':id_utente' => $id_utente]);
        $i_miei_corsi_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }catch(PDOException $e){
        redirect("/palestra/utente/dashboard.php?error=errore_db");
    }
    $titolo_pagina = "Corsi Disponibili";
    require_once __DIR__."/../includes/header.php";
?>

<div class="container">
    
    <?php mostraFeedback();?>

    <div class="page-content">
        
        <div class="welcome-header">
            <h1>Corsi Disponibili</h1>
            <p>Consulta l'elenco dei corsi attivi e prenota il tuo posto.</p>
        </div>        
        
        <?php if(count($elenco_corsi) > 0){?>
            
            <table>
                <thead>
                    <tr>
                        <th>Nome Corso</th>
                        <th>Stato</th>
                        <th>Azione</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($elenco_corsi as $c){?>
                        <?php 
                            $iscritto = in_array($c['id_corso'], $i_miei_corsi_ids);
                        ?>
                        <tr>
                            <td>
                                <strong style="font-size: 1.1rem; color: #333;">
                                    <?=htmlspecialchars($c['nome_corso'])?>
                                </strong>
                                </td>
                            
                            <td>
                                <?php if($iscritto){?>
                                    <span class="status-badge status-ok">Iscritto &check;</span>
                                <?php }else{?>
                                    <span class="status-badge status-open">Disponibile</span>
                                <?php }?>
                            </td>

                            <td>
                                <?php if($iscritto){?>
                                    
                                    <form action="/palestra/actions/cancella_iscrizione.php" method="POST">
                                        <input type="hidden" name="id_corso" value="<?=$c['id_corso']?>">
                                        <button type="submit" class="btn btn-danger">Annulla</button>
                                    </form>

                                <?php }else{?>
                                    
                                    <form action="/palestra/actions/prenota_corso.php" method="POST">
                                        <input type="hidden" name="id_corso" value="<?=$c['id_corso']?>">
                                        <button type="submit" class="btn">Prenota</button>
                                    </form>
                                    
                                <?php }?>
                            </td>
                        </tr>
                    <?php }?>
                </tbody>
            </table>

        <?php }else{?>
            <p class="text-muted text-center mt-20">
                Non ci sono corsi disponibili al momento.
            </p>
        <?php }?>

    </div>
</div>

<?php require_once __DIR__."/../includes/footer.php"; ?>