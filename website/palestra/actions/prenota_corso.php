<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('utente');
    if($_SERVER['REQUEST_METHOD']==='POST'){
        $id_utente = trim($_SESSION['id_utente']);
        $id_corso = (int) trim(($_POST['id_corso'])) ;
        if($id_corso<=0){
            redirect("/palestra/utente/dashboard.php");
        }
        try{
            $query_prenotazione = "SELECT id_partecipa
                                   FROM partecipa
                                   WHERE utente = :id_utente
                                   AND corso = :id_corso";
            $stmt=$pdo->prepare($query_prenotazione);
            $stmt->execute([
                ':id_utente' => $id_utente,
                ':id_corso' => $id_corso
            ]);
            $verifica = $stmt->fetch(PDO::FETCH_ASSOC);
            if($verifica){
                redirect("/palestra/utente/dashboard.php?error=gia_iscritto");
            }
            $query_insert="INSERT INTO partecipa (utente, corso)
                           VALUES (:id_utente, :id_corso)";
            $stmt=$pdo->prepare($query_insert);
            $stmt->execute([
                ":id_utente" =>$id_utente,
                ":id_corso" => $id_corso
            ]);
            redirect("/palestra/utente/dashboard.php?msg=registrato");

        }catch(PDOException $e){
            redirect("/palestra/utente/dashboard.php?error=errore_db");
        }
    }else{
        redirect("/palestra/utente/dashboard.php");
    }
?>