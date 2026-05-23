<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('utente');
    $id_utente = $_SESSION['id_utente'];
    try{
        $query = "SELECT s.*, c.nome as nome_coach, c.cognome as cognome_coach
                  FROM scheda s
                  JOIN coach c ON s.coach = c.id_coach
                  WHERE s.utente = :id_utente
                  ORDER by s.data_inizio DESC";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id_utente' => $id_utente]);
        $lista_schede = $stmt->fetchAll();
    }catch(PDOException $e){
        redirect("/palestra/utente/dashboard.php?error=errore_db");
    }
    $titolo_pagina = "Archivio Schede";
    require_once __DIR__."/../includes/header.php";
?>

<div class="container">
    
    <div class="welcome-header">
        <h1>Il tuo storico schede</h1>
        <p>Qui puoi consultare tutte le schede di allenamento passate e presenti.</p>
    </div>

    <div class="content-box">
        
        <h3>Elenco Schede</h3>

        <?php if(count($lista_schede) > 0){?>

            <table>
                <thead>
                    <tr>
                        <th>Data Inizio</th>
                        <th>Data Fine</th>
                        <th>Coach</th>
                        <th>Dettagli</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($lista_schede as $scheda){?>
                        <tr>
                            <td><strong><?=date('d/m/Y', strtotime($scheda['data_inizio']))?></strong></td>
                            <td><?=date('d/m/Y', strtotime($scheda['data_fine']))?></td>                            
                            <td><?=htmlspecialchars($scheda['nome_coach'].' '.$scheda['cognome_coach'])?></td>
                            <td><a href="vedi_scheda.php?id_scheda=<?=$scheda['id_scheda']?>" class="btn">Visualizza</a></td>
                        </tr>
                    <?php }?>
                </tbody>
            </table>

        <?php }else{?>
            <p class="text-muted text-center mt-20">
                Non hai ancora nessuna scheda in archivio.
            </p>
        <?php }?>

    </div>
</div>

<?php require_once __DIR__."/../includes/footer.php";?>