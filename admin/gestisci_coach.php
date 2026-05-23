<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('admin');
    
    try{
        $stmt = $pdo->query("SELECT * FROM coach ORDER BY cognome ASC, nome ASC");
        $lista_coach = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $e){
        redirect("/palestra/admin/dashboard.php?error=errore_db");
    }
    
    $titolo_pagina = "Gestione Staff Coach - Admin";
    require_once __DIR__."/../includes/header.php";
?>

<div class="container">
    
    <?php mostraFeedback(); ?>

    <div class="content-box">
        <h3>➕ Registra Nuovo Coach</h3>
        <p class="mb-20">Compila i dati anagrafici e di contatto per inserire un nuovo personal trainer nel sistema.</p>

        <form action="/palestra/actions/add_coach.php" method="POST">
            
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
                    <label class="form-label" for="password">Password di Accesso</label>
                    <input type="password" id="password" name="password" class="form-input" required>
                </div>
                <div class="form-group form-col">
                    <label class="form-label" for="data_nascita">Data di Nascita</label>
                    <input type="date" id="data_nascita" name="data_nascita" class="form-input" required>
                </div>
            </div>

            <button type="submit" class="btn">Salva Coach</button>
        </form>
    </div>

    <div class="content-box mt-40">
        <div class="box-header">
            <h3>👥 Personale Staff Coach</h3>
            <span class="badge badge-blue"><?= count($lista_coach) ?> registrati</span>
        </div>

        <?php if(empty($lista_coach)){ ?>
            <p class="text-center">Nessun coach registrato nel sistema.</p>
        <?php } else { ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Cognome</th>
                            <th>Nome</th>
                            <th class="text-nowrap">Data Nascita</th>
                            <th class="text-nowrap">Telefono</th>
                            <th>Email</th>
                            <th class="text-center">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($lista_coach as $c){ ?>
                            <tr>
                                <td><?= htmlspecialchars($c['cognome']) ?></td>
                                <td><?= htmlspecialchars($c['nome']) ?></td>
                                <td class="text-nowrap"><?= htmlspecialchars(date('d/m/Y', strtotime($c['data_nascita']))) ?></td>
                                <td class="text-nowrap"><?= htmlspecialchars($c['telefono']) ?></td>
                                <td><?= htmlspecialchars($c['email']) ?></td>
                                <td class="text-center">
                                    <form action="/palestra/actions/delete_item_admin.php" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questo coach? Questa azione imposterà a NULL il riferimento nei suoi atleti assegnati.');">
                                        <input type="hidden" name="id" value="<?= $c['id_coach'] ?>">
                                        <input type="hidden" name="entita" value="coach">
                                        <button type="submit" class="btn btn-danger btn-sm">🗑️ Licenzia</button>
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
