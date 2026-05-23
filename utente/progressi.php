<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('utente');
    $id_utente = $_SESSION['id_utente'];
    try {
        $query = "SELECT *
                  FROM anamnesi
                  WHERE utente = :id_utente
                  ORDER BY data_anamnesi DESC";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id_utente' => $id_utente]);
        $anamnesi_utente = $stmt->fetchAll();
    } catch(PDOException $e) {
        redirect("/palestra/utente/dashboard.php?error=error_db");
    }
    $titolo_pagina = "I miei Progressi";
    require_once __DIR__."/../includes/header.php";
?>

<div class="container">
    
    <div class="welcome-header">
        <h1>I tuoi Progressi</h1>
        <p>Monitora i cambiamenti del tuo fisico e i risultati raggiunti.</p>
    </div>

    <div class="content-box">
        <h3>Storico Anamnesi</h3>

        <?php if(count($anamnesi_utente) > 0){?>
            
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Peso</th>
                        <th>Altezza</th>
                        <th>Massa Muscolare</th>
                        <th>Massa Magra</th>
                        <th>Massa Grassa</th>    
                        <th>BMI</th>                
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($anamnesi_utente as $row){?>
                        <tr>
                            <td><?=date('d/m/Y', strtotime($row['data_anamnesi']))?></td>
                            <td><?=htmlspecialchars($row['peso'])?> kg</td>
                            <td><?=htmlspecialchars($row['altezza'])?> cm</td>
                            <td><?=htmlspecialchars($row['massa_muscolare'])?> %</td>
                            <td><?=htmlspecialchars($row['massa_magra'])?> %</td>
                            <td><?=htmlspecialchars($row['massa_grassa'])?> %</td>
                            <td class="text-nowrap">
                                <?php 
                                    $bmi = ($row['altezza'] > 0) ? $row['peso'] / pow($row['altezza']/100, 2) : 0;
                                    $classe = match(true) {
                                        $bmi == 0    => '',
                                        $bmi < 18.5  => 'bmi-sottopeso',
                                        $bmi < 25    => 'bmi-normopeso',
                                        $bmi < 30    => 'bmi-sovrappeso',
                                        default      => 'bmi-obesita',
                                    };
                                ?>
                                <span class="<?=$classe?>"><?=$bmi ? number_format($bmi, 1) : 'N/D'?></span>
                            </td>
                        </tr>
                    <?php }?>
                </tbody>
            </table>

        <?php }else{?>
            <p>
                Nessun progresso registrato dal tuo Coach al momento.
            </p>
        <?php }?>

    </div>
</div>
    
<?php require_once __DIR__."/../includes/footer.php";?>