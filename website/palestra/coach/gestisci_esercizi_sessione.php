<?php
    require_once __DIR__ . "/../config/db_conn.php";
    require_once __DIR__ . "/../includes/functions.php";
    check_login('coach');
    $id_sessione = (int)$_GET['id_sessione'];
    if($id_sessione <= 0) redirect("dashboard.php");
    try{
        $query_sess = "SELECT sa.*, s.id_scheda, u.nome, u.cognome 
                       FROM sessione_allenamento sa
                       JOIN scheda s ON sa.scheda = s.id_scheda
                       JOIN utente u ON s.utente = u.id_utente
                       WHERE sa.id_sessione_allenamento = :id_sessione";
        $stmt = $pdo->prepare($query_sess);
        $stmt->execute([':id_sessione' => $id_sessione]);
        $sessione = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$sessione) redirect("dashboard.php");
        
        $esercizi_db = $pdo->query("SELECT * 
                                    FROM esercizio
                                    ORDER BY gruppo_muscolare, nome_esercizio")->fetchAll(PDO::FETCH_ASSOC);

        $query_prevede = "SELECT p.*, e.nome_esercizio 
                          FROM prevede p
                          JOIN esercizio e ON p.esercizio = e.id_esercizio
                          WHERE p.sessione_allenamento = :id_sessione
                          ORDER BY p.id_prevede ASC";
        $stmt = $pdo->prepare($query_prevede);
        $stmt->execute([':id_sessione' => $id_sessione]);
        $esercizi_inseriti = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $e){
        redirect("dashboard.php?error=errore_db");
    }
    $titolo_pagina = "Esercizi Sessione";
    require_once __DIR__."/../includes/header.php";
?>

<div class="container">

    <?php mostraFeedback(); ?>

    <div class="content-box mb-20">
        <div class="box-header">
            <div>
                <h3>Sessione : <?=($sessione['tipo_allenamento'])?></h3>
                <p class="text-muted">Atleta : <?=($sessione['nome']." ".$sessione['cognome'])?></p>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="col-left">
            <div class="content-box">
                <h3>➕ Aggiungi Esercizio</h3>
                <form action="/palestra/actions/add_esercizio_sessione.php" method="POST" class="mt-20">
                    <input type="hidden" name="id_sessione" value="<?=$id_sessione?>">
                    
                    <div class="form-group">
                        <label class="form-label">Esercizio</label>
                        <select name="id_esercizio" class="form-input" required>
                            <option value="">-- Seleziona --</option>
                            <?php 
                            $g_prec = "";
                            foreach($esercizi_db as $ex){
                                if($g_prec != $ex['gruppo_muscolare']){
                                    if($g_prec != "") echo "</optgroup>";
                                    $g_prec = $ex['gruppo_muscolare'];
                                    echo "<optgroup label='".htmlspecialchars($g_prec)."'>";
                                }
                            ?>
                                <option value="<?=$ex['id_esercizio']?>"><?=htmlspecialchars($ex['nome_esercizio'])?></option>
                            <?php } echo "</optgroup>"; ?>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group form-col">
                            <label class="form-label">Serie</label>
                            <input type="number" name="serie" class="form-input" min="1" required>
                        </div>
                        <div class="form-group form-col">
                            <label class="form-label">Ripetizioni</label>
                            <input type="number" name="ripetizioni" class="form-input" min="1" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group form-col">
                            <label class="form-label">Carico (kg)</label>
                            <input type="number" name="carico" step="0.01" min="0" class="form-input" value="0.00">
                        </div>
                        <div class="form-group form-col">
                            <label class="form-label">Recupero (sec)</label>
                            <input type="number" name="recupero" min="0" class="form-input" value="60">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-block">Aggiungi</button>
                </form>
            </div>
        </div>

        <div class="col-right">
            <div class="content-box">
                <h3>📋 Esercizi Inseriti</h3>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Esercizio</th>
                                <th>S x R</th>
                                <th>Carico</th>
                                <th>Rec.</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($esercizi_inseriti as $ei){?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($ei['nome_esercizio']) ?></strong></td>
                                    <td><?= (int)$ei['serie'] ?> x <?= (int)$ei['ripetizioni'] ?></td>
                                    <td><?= number_format($ei['carico'], 2) ?> kg</td>
                                    <td><?= (int)$ei['recupero'] ?>''</td>
                                    <td>
                                        <form action="/palestra/actions/delete_item.php" method="POST">
                                            <input type="hidden" name="id" value="<?= $ei['id_prevede'] ?>">
                                            <input type="hidden" name="entita" value="esercizio_sessione">
                                            <input type="hidden" name="redirect_id" value="<?= $id_sessione ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__."/../includes/footer.php";?>