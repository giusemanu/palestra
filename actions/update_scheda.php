<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('coach');
    if($_SERVER['REQUEST_METHOD']==='POST'){
        $id_coach = (int)$_SESSION['id_coach'];
        $id_scheda = (int)$_POST['id_scheda'];
        $id_utente = (int)$_POST['id_utente'];
        $data_inizio = trim($_POST['data_inizio']);
        $data_fine = trim($_POST['data_fine']);
        if(!empty($id_scheda) && !empty($id_utente) && |empty($data_inizio) && !empty($data_fine)){
            if($data_fine < $data_inizio){
                redirect("/palestra/coach/modifica_scheda.php?id_utente=$id_utente&error=date_invalide");
            }else{
                try{
                    $query = "UPDATE scheda
                            SET data_inizio = :data_inizio,
                                data_fine = :data_fine
                            WHERE id_scheda = :id_scheda";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([
                        ':data_inizio' => $data_inizio,
                        ':data_fine' => $data_fine,
                        ':id_scheda' => $id_scheda
                    ]);
                    redirect("/palestra/coach/modifica_scheda.php?id_utente=$id_utente&msg=date_aggiornate");
                }catch(PDOException $e){
                    redirect("/palestra/coach/modifica_scheda.php?id_utente=$id_utente&error=errore_db");
                }
            }
        }else{
            redirect("/palestra/coach/dashboard.php?error=campi_vuoti");
        }
    }else{
        redirect("/palestra/coach/dashboard.php");
    }
?>