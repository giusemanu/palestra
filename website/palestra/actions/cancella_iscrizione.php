<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('utente');
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $id_utente = (int)$_SESSION['id_utente'];
        $id_corso = (int)$_POST['id_corso'];
        if($id_corso <= 0){
            redirect("/palestra/utente/corsi_disponibili.php");
        }
        try{
            $query = "DELETE
                      FROM partecipa 
                      WHERE utente = :id_utente
                      AND corso = :id_corso";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ":id_utente" => $id_utente,
                ":id_corso"  => $id_corso
            ]);
            redirect("/palestra/utente/dashboard.php?msg=iscrizione_cancellata");
        }catch(PDOException $e){
            redirect("/palestra/utente/dashboard.php?error=errore_db");
        }
    }else{
        redirect("/palestra/utente/dashboard.php");
    }
?>