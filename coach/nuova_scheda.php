<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('coach');
    $id_coach = (int)$_SESSION['id_coach'];
    $query = "SELECT id_utente, nome, cognome, email
              FROM utente
              WHERE coach = :id_coach
              ORDER BY cognome ASC";
    try{
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id_coach' => $id_coach]);
        $lista_utenti = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $e){
        redirect("/palestra/coach/dashboard.php?error=errore_db");
    }
    $titolo_pagina = "Nuova Scheda";
    require_once __DIR__."/../includes/header.php";
?>

<div class="container">
    
    <?php mostraFeedback();?>

    <div class="content-box content-box-centered">
        
        <h1 class="text-center">Crea Nuova Scheda</h1>
        <p class="text-center mb-20">
            Seleziona l'atleta e imposta la durata della nuova scheda di allenamento.
        </p>
        
        <form action="/palestra/actions/crea_scheda.php" method="POST">
            
            <div class="form-group">
                <label class="form-label" for="utente">Seleziona Atleta:</label>
                <select id="utente" name="utente" class="form-input" required>
                    <option value="">-- Scegli Utente --</option>
                    <?php foreach($lista_utenti as $u){?>
                        <option value="<?=(int)$u['id_utente']?>">
                            <?=htmlspecialchars($u['cognome'].' '.$u['nome']).' ('.$u['email'].')'?>
                        </option>
                    <?php }?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="data_inizio">Data Inizio:</label>
                <input type="date" id="data_inizio" name="data_inizio" class="form-input" 
                       value="<?=date('Y-m-d')?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="data_fine">Data Fine :</label>
                <input type="date" id="data_fine" name="data_fine" class="form-input" required>
            </div>

            <button type="submit" class="btn btn-block btn-form-margin">
                Crea Scheda
            </button>

        </form>
    </div>
</div>

<?php require_once __DIR__."/../includes/footer.php"; ?>