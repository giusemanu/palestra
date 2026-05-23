<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('admin');
    
    try{
        $queryUtenti = "SELECT u.*, c.nome AS coach_nome, c.cognome AS coach_cognome 
                        FROM utente u 
                        LEFT JOIN coach c ON u.coach = c.id_coach 
                        ORDER BY u.cognome ASC, u.nome ASC";
        $stmt = $pdo->query($queryUtenti);
        $lista_utenti = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmtCoach = $pdo->query("SELECT id_coach, nome, cognome FROM coach ORDER BY cognome ASC");
        $elenco_coach = $stmtCoach->fetchAll(PDO::FETCH_ASSOC);

    }catch(PDOException $e){
        redirect("/palestra/admin/dashboard.php?error=errore_db");
    }
    
    $titolo_pagina = "Gestione Anagrafica Atleti - Admin";
    require_once __DIR__."/../includes/header.php";
?>

<div class="container">
    
    <?php mostraFeedback(); ?>

    <div class="content-box">
        <h3>➕ Iscrivi Nuovo Atleta</h3>
        <p class="mb-20">Compila l'anagrafica per inserire un nuovo membro all'interno della struttura della palestra.</p>

        <form action="/palestra/actions/add_utente.php" method="POST">
            
            <div class="form-row">
                <div class="form-group form-col">
                    <label class="form-label" for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" class="form-input" required>
                </div>
                <div class="form-group form-col">
                    <label class="form-label" for="cognome">Cognome</label>
                    <input type="text" id="cognome" name="cognome" class="form-input" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group form-col">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-input" required>
                </div>
                <div class="form-group form-col">
                    <label class="form-label" for="telefono">Telefono</label>
                    <input type="text" id="telefono" name="telefono" class="form-input" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group form-col">
                    <label class="form-label" for="password">Password Iniziale</label>
                    <input type="password" id="password" name="password" class="form-input" required>
                </div>
                <div class="form-group form-col">
                    <label class="form-label" for="data_nascita">Data di Nascita</label>
                    <input type="date" id="data_nascita" name="data_nascita" class="form-input" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group form-col">
                    <label class="form-label" for="coach">Assegna un Personal Trainer (Opzionale)</label>
                    <select id="coach" name="coach" class="form-input">
                        <option value="">-- Nessun Coach Assegnato --</option>
                        <?php foreach($elenco_coach as $co){ ?>
                            <option value="<?= $co['id_coach'] ?>">
                                <?= htmlspecialchars($co['cognome'] . " " . $co['nome']) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn">Salva Utente</button>
        </form>
    </div>

    <div class="content-box mt-40">
        <div class="box-header">
            <h3>🏋️‍ Registro Atleti</h3>
            <span class="badge badge-blue"><?= count($lista_utenti) ?> iscritti</span>
        </div>

        <?php if(empty($lista_utenti)){ ?>
            <p class="text-center">Nessun atleta registrato nel sistema.</p>
        <?php } else { ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Cognome</th>
                            <th>Nome</th>
                            <th class="text-nowrap">Data Nascita</th>
                            <th>Telefono</th>
                            <th>Email</th>
                            <th>Coach Assegnato</th>
                            <th class="text-center">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($lista_utenti as $u){ ?>
                            <tr>
                                <td><?= htmlspecialchars($u['cognome']) ?></td>
                                <td><?= htmlspecialchars($u['nome']) ?></td>
                                <td class="text-nowrap"><?= htmlspecialchars(date('d/m/Y', strtotime($u['data_nascita']))) ?></td>
                                <td class="text-nowrap"><?= htmlspecialchars($u['telefono']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td>
                                    <?php if(!empty($u['coach'])){ 
                                        echo htmlspecialchars($u['coach_cognome'] . " " . $u['coach_nome']);
                                    } else { ?>
                                        <span class="text-muted" style="font-style: italic;">Non assegnato</span>
                                    <?php } ?>
                                </td>
                                <td class="text-center">
                                    <form action="/palestra/actions/delete_admin.php" method="POST">
                                        <input type="hidden" name="id" value="<?= $u['id_utente'] ?>">
                                        <input type="hidden" name="entita" value="utente">
                                        <button type="submit" class="btn btn-danger btn-sm">🗑️ Rimuovi</button>
                                    </form>
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
