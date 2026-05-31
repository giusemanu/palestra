<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('coach');
    $id_coach = $_SESSION['id_coach'];
    try{
        $query = "SELECT *
                  FROM utente
                  WHERE coach = :id_coach
                  ORDER BY cognome ASC, nome ASC";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id_coach' => $id_coach]);
        $lista_utenti = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $e){
        redirect("/palestra/coach/dashboard.php?error=errore_db");
    }
    $titolo_pagina = "Gestione Utenti - Coach";
    require_once __DIR__."/../includes/header.php";
?>

<div class="container">
    
    <?php mostraFeedback();?>

    <div class="content-box">
        <div class="box-header">
            <h3>👥 I tuoi Atleti</h3>
            <span class="badge badge-blue"><?php echo count($lista_utenti);?> iscritti</span>
        </div>

        <?php if(empty($lista_utenti)){?>
            <p class="text-center">Non hai ancora registrato nessun atleta.</p>
        <?php }else{?>
            <div class="table-responsive">
                <table>
                    <thead> 
                        <tr>
                            <th>Cognome</th>
                            <th>Nome</th>
                            <th>Data Nascita</th>
                            <th>Telefono</th>
                            <th>Email</th>
                            <th class="text-center">Scheda</th>
                            <th class="text-center">Anamnesi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($lista_utenti as $u){?>
                            <tr>
                                <td><?=htmlspecialchars($u['cognome'])?></td>
                                <td><?=htmlspecialchars($u['nome'])?></td>
                                <td><?=htmlspecialchars(date('d/m/Y', strtotime($u['data_nascita'])))?></td>
                                <td><?=htmlspecialchars($u['telefono'])?></td>
                                <td><?=htmlspecialchars($u['email'])?></td>
                                
                                <td class="text-center">
                                    <a href="modifica_scheda.php?id_utente=<?=$u['id_utente']?>" class="btn btn-outline btn-sm">
                                        🏋️ Scheda
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="gestisci_anamnesi.php?id_utente=<?=$u['id_utente']?>" class="btn btn-outline-info btn-sm">
                                        ⚖️ Dati
                                    </a>
                                </td>
                            </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
        <?php }?>
    </div>
</div>

<?php require_once __DIR__."/../includes/footer.php"; ?>
