<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('coach');
    if($_SERVER['REQUEST_METHOD']==='POST'){
        $data_inizio = ($_POST['data_inizio']);
        $data_fine = ($_POST['data_fine']);
        $utente = trim($_POST['utente']);
        $coach = trim($_SESSION['id_coach']);
        if(empty($data_inizio) || empty($data_fine) || empty($utente) || empty($coach)){
            redirect("/palestra/coach/dashboard.php?error=campi_vuoti");
        }
        if ($data_fine < $data_inizio) {
            redirect("/palestra/coach/modifica_scheda.php?id_utente=$utente&error=date_invalide");
        }else{
            try{
                $query = "INSERT INTO scheda (data_inizio, data_fine, utente, coach)
                        VALUES (:data_inizio, :data_fine, :utente, :coach)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([
                    ':data_inizio' => $data_inizio,
                    ':data_fine' => $data_fine,
                    ':utente' => $utente,
                    ':coach' => $coach
                ]);
                redirect("/palestra/coach/dashboard.php?msg=scheda_creata");
            }catch(PDOException $e){
                redirect("/palestra/coach/dashboard.php?error=errore_db");
            }
        }
    }else{
        redirect("/palestra/index.php");
    }
?>