<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('coach');
    if($_SERVER['REQUEST_METHOD']==='POST'){
        $id_sessione = (int)$_POST['id_sessione'];
        $id_esercizio = (int)$_POST['id_esercizio'];
        $serie = (int)$_POST['serie'];
        $reps = (int)$_POST['ripetizioni'];
        $carico = (float)$_POST['carico'];
        $rec = (int)$_POST['recupero'];
        if($id_sessione > 0 && $id_esercizio > 0 && $serie > 0 && $reps > 0){
            try{
                $query = "INSERT INTO prevede (serie, ripetizioni, carico, recupero, sessione_allenamento, esercizio) 
                          VALUES (:serie, :reps, :carico, :rec, :id_sessione, :id_esercizio)";
                
                $stmt = $pdo->prepare($query);
                $stmt->execute([
                    ':serie'   => $serie,
                    ':reps'    => $reps,
                    ':carico'  => $carico,
                    ':rec'     => $rec,
                    ':id_sessione' => $id_sessione,
                    ':id_esercizio'   => $id_esercizio
                ]);

                redirect("/palestra/coach/gestisci_esercizi_sessione.php?id_sessione=$id_sessione&msg=esercizio_aggiunto");
            }catch (PDOException $e){
                redirect("/palestra/coach/gestisci_esercizi_sessione.php?id_sessione=$id_session&error=errore_db");
            }
        }else{
            redirect("/palestra/coach/gestisci_esercizi_sessione.php?id_sessione=$id_session&error=campi_vuoti");
        }
    }else{
        redirect("/palestra/coach/dashboard.php");
    }
?>