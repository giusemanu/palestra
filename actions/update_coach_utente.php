<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('admin');

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $id_utente = (int)$_POST['id_utente'];
        
        $id_coach = $_POST['id_coach'] !== '' ? (int)$_POST['id_coach'] : null;

        if($id_utente <= 0){
            redirect("/palestra/admin/gestisci_utenti.php?error=dati_invalidi");
        }

        try{
            $stmt = $pdo->prepare("UPDATE utente SET coach = :id_coach WHERE id_utente = :id_utente");
            $stmt->execute([
                ':id_coach' => $id_coach,
                ':id_utente' => $id_utente
            ]);
            
            redirect("/palestra/admin/gestisci_utenti.php?msg=coach_aggiornato");
            
        }catch(PDOException $e){
            redirect("/palestra/admin/gestisci_utenti.php?error=errore_db");
        }
    }else{
        redirect("/palestra/index.php");
    }
?>
