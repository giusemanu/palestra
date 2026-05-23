<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('coach');
    try{
        $query = "SELECT *
                  FROM esercizio
                  ORDER BY gruppo_muscolare ASC, nome_esercizio ASC";
        $stmt = $pdo->query($query);
        $esercizi = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch (PDOException $e){
        redirect("/palestra/coach/dashboard.php?error=errore_db");
    }
    $titolo_pagina = "Archivio Esercizi";
    require_once __DIR__."/../includes/header.php";
?>

<div class="container">
    
    <?php mostraFeedback(); ?>

    <div class="welcome-header">
        <h1>💪 Archivio Esercizi</h1>
        <p>Gestisci la lista globale degli esercizi disponibili per le schede di allenamento.</p>
    </div>

    <div class="dashboard-grid">
        <div class="col-left">
            <div class="content-box h-100">
                <h3>➕ Nuovo Esercizio</h3>
                <p class="mb-20">Inserisci un esercizio non ancora presente in lista.</p>

                <form action="/palestra/actions/add_esercizio.php" method="POST">
                    
                    <div class="form-group">
                        <label class="form-label" for="nome_esercizio">Nome Esercizio</label>
                        <input type="text" id="nome_esercizio" name="nome_esercizio" class="form-input" placeholder="Es. Squat con bilanciere" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="gruppo_muscolare">Gruppo Muscolare</label>
                        <select id="gruppo_muscolare" name="gruppo_muscolare" class="form-input" required>
                            <option value="">-- Seleziona --</option>
                            <option value="Petto">Petto</option>
                            <option value="Dorso">Dorso</option>
                            <option value="Gambe">Gambe</option>
                            <option value="Spalle">Spalle</option>
                            <option value="Braccia">Braccia</option>
                            <option value="Core">Core / Addome</option>
                            <option value="Cardio">Cardio</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-block btn-form-margin">Salva in Archivio</button>
                </form>
            </div>
        </div>

        <div class="col-right">
            <div class="content-box h-100">
                <h3>📋 Lista Esercizi (<?=count($esercizi)?>)</h3>
                
                <?php if (empty($esercizi)){?>
                    <div class="error-msg mt-20">
                        Nessun esercizio presente in archivio.
                    </div>
                <?php }else{?>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th class="text-left">Nome</th>
                                    <th class="text-left">Gruppo</th>
                                    <th class="text-center">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($esercizi as $es){?>
                                    <tr>
                                        <td class="text-left"><strong><?= htmlspecialchars($es['nome_esercizio']) ?></strong></td>
                                        <td class="text-left"><span class="badge badge-blue"><?= htmlspecialchars($es['gruppo_muscolare']) ?></span></td>
                                        <td class="text-center">
                                            <form action="/palestra/actions/delete_item.php" method="POST" class="form-inline">
                                                <input type="hidden" name="id" value="<?=$es['id_esercizio']?>">
                                                <input type="hidden" name="entita" value="esercizio">
                                                <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                                            </form>
                                        </td>
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

<?php require_once __DIR__ . "/../includes/footer.php"; ?>