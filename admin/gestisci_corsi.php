<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('admin');
    
    try{
        $stmt = $pdo->query("SELECT * FROM corso ORDER BY nome_corso ASC");
        $lista_corsi = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $e){
        redirect("/palestra/admin/dashboard.php?error=errore_db");
    }
    
    $titolo_pagina = "Gestione Corsi - Admin";
    require_once __DIR__."/../includes/header.php";
?>

<div class="container">
    
    <?php mostraFeedback(); ?>

    <div class="content-box">
        <h3>➕ Crea Nuovo Corso Collettivo</h3>
        <p class="mb-20">Inserisci il nome del nuovo corso da aggiungere al palinsesto della palestra.</p>

        <form action="/palestra/actions/add_corso.php" method="POST">
            <div class="form-row">
                <div class="form-group form-col">
                    <label class="form-label" for="nome_corso">Nome del Corso</label>
                    <input type="text" id="nome_corso" name="nome_corso" class="form-input" placeholder="Es. CrossFit, Pilates, Spinning..." required>
                </div>
            </div>

            <button type="submit" class="btn">Pubblica Corso</button>
        </form>
    </div>

    <div class="content-box mt-40">
        <div class="box-header">
            <h3>📋 Palinsesto Corsi Attivi</h3>
            <span class="badge badge-blue"><?= count($lista_corsi) ?> Corsi</span>
        </div>

        <?php if(empty($lista_corsi)){ ?>
            <p class="text-center">Nessun corso collettivo pianificato al momento.</p>
        <?php } else { ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th class="text-left">Identificativo (ID)</th>
                            <th class="text-left">Nome della Disciplina</th>
                            <th class="text-center col-actions">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($lista_corsi as $co){ ?>
                            <tr>
                                <td class="text-left text-id">
                                <td class="text-left"><strong><?= htmlspecialchars($co['nome_corso']) ?></strong></td>
                                <td class="text-center">
                                    <form action="/palestra/actions/delete_admin.php" method="POST">
                                        <input type="hidden" name="id" value="<?= $co['id_corso'] ?>">
                                        <input type="hidden" name="entita" value="corso">
                                        <button type="submit" class="btn btn-danger btn-sm">🗑️ Elimina</button>
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
