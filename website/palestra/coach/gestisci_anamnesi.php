<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('coach');
    if(isset($_GET['id_utente'])){
        $id_utente = (int)$_GET['id_utente'];
        try{
            $query_1 = "SELECT nome, cognome 
                        FROM utente
                        WHERE id_utente = :id_utente";
            $stmt = $pdo->prepare($query_1);
            $stmt->execute([':id_utente' => $id_utente]);
            $dati_utente = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$dati_utente) {
                redirect("/palestra/coach/utenti.php?error=utente_non_trovato");
            }

            $query_2 = "SELECT *
                        FROM anamnesi
                        WHERE utente = :id_utente
                        ORDER BY data_anamnesi DESC";
            $stmt = $pdo->prepare($query_2);
            $stmt->execute([':id_utente' => $id_utente]);            
            $anamnesi_utente = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            redirect("/palestra/coach/dashboard.php?error=errore_db");
        }
    }else{
        redirect("/palestra/coach/utenti.php?error=id_mancante");
    }
    $titolo_pagina = "Gestione Anamnesi - ".$dati_utente['nome'];
    require_once __DIR__."/../includes/header.php";
?>

<div class="container">
    
    <?php mostraFeedback(); ?>

    <div class="welcome-header">
        <h1>Anamnesi di <?=($dati_utente['nome'].' '.$dati_utente['cognome'])?></h1>
        <p>Monitora i parametri corporei e registra le nuove misurazioni.</p>
    </div>

    <div class="dashboard-grid">
        <div class="col-left">
            <div class="content-box h-100">
                <h3>➕ Nuova Registrazione</h3>
                <p class="mb-20">Compila tutti i campi per aggiornare i dati biometrici.</p>

                <form action="/palestra/actions/add_anamnesi.php" method="POST">
                    <input type="hidden" name="id_utente" value="<?=$id_utente?>">

                    <div class="form-group">
                        <label class="form-label" for="data_anamnesi">Data Misurazione</label>
                        <input type="date" id="data_inizio" name="data_anamnesi" class="form-input" value="<?=date('Y-m-d')?>" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group form-col">
                            <label class="form-label" for="peso">Peso (kg)</label>
                            <input type="number" id="peso" name="peso" step="0.01" min="1" class="form-input" placeholder="0.00" required>
                        </div>
                        <div class="form-group form-col">
                            <label class="form-label" for="altezza">Altezza (cm)</label>
                            <input type="number" id="altezza" name="altezza" step="0.01" min="1" class="form-input" placeholder="0.00" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="massa_muscolare">Massa Muscolare (kg)</label>
                        <input type="number" id="massa_muscolare" name="massa_muscolare" step="0.01" min="0" class="form-input" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group form-col">
                            <label class="form-label" for="massa_magra">Massa Magra (%)</label>
                            <input type="number" id="massa_magra" name="massa_magra" step="0.01" min="0" class="form-input" required>
                        </div>
                        <div class="form-group form-col">
                            <label class="form-label" for="massa_grassa">Massa Grassa (%)</label>
                            <input type="number" id="massa_grassa" name="massa_grassa" step="0.01" min="0" class="form-input" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-block">Registra Dati</button>
                </form>
            </div>
        </div>

        <div class="col-right">
            <div class="content-box h-100">
                <h3>📋 Storico Misurazioni</h3>
                
                <?php if (empty($anamnesi_utente)){?>
                    <div class="error-msg mt-20">
                        Nessun dato registrato per questo utente.
                    </div>
                <?php }else{?>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Peso</th>
                                    <th>Altezza</th>
                                    <th>Muscolo</th>
                                    <th>Magra</th>
                                    <th>Grassa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($anamnesi_utente as $a){?>
                                    <tr>
                                        <td class="text-nowrap"><?=date('d/m/Y', strtotime($a['data_anamnesi']))?></td>
                                        <td class="text-nowrap"><?=htmlspecialchars($a['peso'])?> kg</td>
                                        <td class="text-nowrap"><?=htmlspecialchars($a['altezza'])?> cm</td>
                                        <td class="text-nowrap"><?=htmlspecialchars($a['massa_muscolare'])?> kg</td>
                                        <td class="text-nowrap"><?=htmlspecialchars($a['massa_magra'])?> %</td>
                                        <td class="text-nowrap"><?=htmlspecialchars($a['massa_grassa'])?> %</td>
                                    </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                <?php }?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__."/../includes/footer.php"; ?>