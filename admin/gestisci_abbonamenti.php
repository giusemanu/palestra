<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('admin');
    
    try{
        $queryAbbonamenti = "SELECT a.*, u.nome, u.cognome 
                             FROM abbonamento a
                             JOIN utente u ON a.utente = u.id_utente
                             ORDER BY a.data_scadenza DESC, u.cognome ASC";
        $stmt = $pdo->query($queryAbbonamenti);
        $lista_abbonamenti = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmtUtenti = $pdo->query("SELECT id_utente, nome, cognome FROM utente ORDER BY cognome ASC, nome ASC");
        $elenco_utenti = $stmtUtenti->fetchAll(PDO::FETCH_ASSOC);

    }catch(PDOException $e){
        redirect("/palestra/admin/dashboard.php?error=errore_db");
    }
    
    $titolo_pagina = "Registro Abbonamenti - Admin";
    require_once __DIR__."/../includes/header.php";
?>

<div class="container">
    <?php mostraFeedback(); ?>
    <div class="content-box">
        <h3>💳 Emetti / Rinnova Abbonamento</h3>
        <p class="mb-20">Seleziona un atleta e inserisci i dettagli del nuovo piano d'acquisto. Il sistema calcolerà automaticamente la scadenza.</p>

        <form action="/palestra/actions/add_abbonamento.php" method="POST">
            
            <div class="form-row">
                <div class="form-group form-col">
                    <label class="form-label" for="utente">Seleziona Atleta</label>
                    <select id="utente" name="utente" class="form-input" required>
                        <option value="">-- Scegli un Atleta --</option>
                        <?php foreach($elenco_utenti as $ut){ ?>
                            <option value="<?= $ut['id_utente'] ?>">
                                <?= htmlspecialchars($ut['cognome'] . " " . $ut['nome']) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group form-col">
                    <label class="form-label" for="tipo_abbonamento">Tipo Abbonamento</label>
                    <select id="tipo_abbonamento" name="tipo_abbonamento" class="form-input" required>
                        <option value="">-- Seleziona Durata --</option>
                        <option value="Mensile">Mensile (30 Giorni)</option>
                        <option value="Trimestrale">Trimestrale (90 Giorni)</option>
                        <option value="Annuale">Annuale (365 Giorni)</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group form-col">
                    <label class="form-label" for="prezzo">Importo Pagato (€)</label>
                    <input type="number" id="prezzo" name="prezzo" step="0.01" min="0" class="form-input" placeholder="Es. 50.00" required>
                </div>
            </div>

            <button type="submit" class="btn">Registra Pagamento e Attiva</button>
        </form>
    </div>

    <div class="content-box mt-40">
        <div class="box-header">
            <h3>📋 Storico Abbonamenti</h3>
            <span class="badge badge-blue"><?= count($lista_abbonamenti) ?> Contratti totali</span>
        </div>

        <?php if(empty($lista_abbonamenti)){ ?>
            <p class="text-center">Nessun abbonamento presente in archivio.</p>
        <?php } else { ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Atleta</th>
                            <th>Tipo Piano</th>
                            <th class="text-nowrap">Data Inizio</th>
                            <th class="text-nowrap">Data Scadenza</th>
                            <th>Prezzo</th>
                            <th class="text-center">Stato</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($lista_abbonamenti as $ab){ 
                            // Forza graficamente il badge in base allo stato registrato
                            $classe_badge = ($ab['stato'] === 'Attivo') ? 'badge-blue' : 'badge-red';
                        ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($ab['cognome'] . " " . $ab['nome']) ?></strong></td>
                                <td><?= htmlspecialchars($ab['tipo_abbonamento']) ?></td>
                                <td class="text-nowrap"><?= htmlspecialchars(date('d/m/Y', strtotime($ab['data_inizio']))) ?></td>
                                <td class="text-nowrap"><?= htmlspecialchars(date('d/m/Y', strtotime($ab['data_scadenza']))) ?></td>
                                <td><strong>€ <?= htmlspecialchars(number_format($ab['prezzo'], 2, ',', '.')) ?></strong></td>
                                <td class="text-center">
                                    <span class="badge <?= $classe_badge ?>">
                                        <?= strtoupper(htmlspecialchars($ab['stato'])) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>
</div>

<?php require_once __DIR__."/../includes/footer.php"; ?>
