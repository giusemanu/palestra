<?php
    require_once __DIR__ . "/../config/db_conn.php";
    require_once __DIR__ . "/../includes/functions.php";
    check_login('utente');
    $id_utente = $_SESSION['id_utente'];
    $id_scheda = $_GET['id_scheda'];
    try{
        if($id_scheda){
            $sql = "SELECT 
                        s.data_inizio, s.data_fine, s.id_scheda,
                        sa.tipo_allenamento,
                        e.nome_esercizio, e.gruppo_muscolare,
                        p.serie, p.ripetizioni, p.carico, p.recupero
                    FROM scheda s
                    JOIN sessione_allenamento sa ON s.id_scheda = sa.scheda
                    JOIN prevede p ON p.sessione_allenamento = sa.id_sessione_allenamento
                    JOIN esercizio e ON e.id_esercizio = p.esercizio
                    WHERE s.id_scheda = :id_scheda AND s.utente = :id_utente
                    ORDER BY sa.id_sessione_allenamento ASC, p.id_prevede ASC";
            $params = [':id_utente' => $id_utente, ':id_scheda' => $id_scheda];
        }else{
            $sql = "SELECT 
                        s.data_inizio, s.data_fine, s.id_scheda,
                        sa.tipo_allenamento,
                        e.nome_esercizio, e.gruppo_muscolare,
                        p.serie, p.ripetizioni, p.carico, p.recupero
                    FROM scheda s
                    JOIN sessione_allenamento sa ON s.id_scheda = sa.scheda
                    JOIN prevede p ON p.sessione_allenamento = sa.id_sessione_allenamento
                    JOIN esercizio e ON e.id_esercizio = p.esercizio
                    WHERE s.utente = :id_utente 
                    ORDER BY s.data_inizio DESC, sa.id_sessione_allenamento ASC, p.id_prevede ASC";
            $params = [':id_utente' => $id_utente];
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $risultati_grezzi = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $e){
        redirect("/palestra/utente/dashboard.php?error=errore_db");
    }

    $scheda_strutturata = [];
    $info_scheda = null;
    if(count($risultati_grezzi) > 0){
        $info_scheda = [
            'data_inizio' => $risultati_grezzi[0]['data_inizio'],
            'data_fine'   => $risultati_grezzi[0]['data_fine']
        ];
        foreach($risultati_grezzi as $riga){
            $nome_sessione = $riga['tipo_allenamento'];
            $scheda_strutturata[$nome_sessione][] = [
                'nome'     => $riga['nome_esercizio'],
                'gruppo'   => $riga['gruppo_muscolare'],
                'serie'    => $riga['serie'],
                'rip'      => $riga['ripetizioni'],
                'carico'   => $riga['carico'],
                'recupero' => $riga['recupero']
            ];
        }
    }
    $titolo_pagina = "La tua Scheda";
    require_once __DIR__."/../includes/header.php";
?>

<div class="container">
    
    <?php mostraFeedback();?>

    <div class="welcome-header">
        <h1>Dettaglio Scheda</h1>
        <p>
            <?php if($info_scheda){?>
                Scheda valida dal <strong><?=date('d/m/Y', strtotime($info_scheda['data_inizio']))?></strong>
                al <strong><?=date('d/m/Y', strtotime($info_scheda['data_fine']));?></strong>
            <?php }else{?>
                Nessuna scheda attiva trovata.
            <?php }?>
        </p>
    </div>

    <?php if(!empty($scheda_strutturata)){?>
        <?php foreach($scheda_strutturata as $nome_sessione => $esercizi){?>
            
            <div class="content-box">
                <h3><?=htmlspecialchars($nome_sessione)?></h3>
                
                <table>
                    <thead>
                        <tr>
                            <th>Esercizio</th>
                            <th>Gruppo</th>
                            <th>Serie</th>
                            <th>Rep</th>
                            <th>Kg</th>
                            <th>Recupero</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($esercizi as $ex){?>
                            <tr>
                                <td><?=$ex['nome']?></td>
                                <td><?=$ex['gruppo']?></td>
                                <td><?=$ex['serie']?></td>
                                <td><?=$ex['rip']?></td>
                                <td><?=$ex['carico']?></td>
                                <td><?=$ex['recupero']?>''</td>
                            </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div> 

            <?php }?>
    <?php }?>

</div>

<?php require_once __DIR__."/../includes/footer.php"; ?>