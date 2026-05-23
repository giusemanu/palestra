<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";

    check_login('admin');
    
    if($_SERVER['REQUEST_METHOD'] === 'POST'){ 
        $id = (int)$_POST['id']; 
        $entita = trim($_POST['entita']);
        
        if($id <= 0 || empty($entita)){
            redirect("/palestra/admin/dashboard.php?error=dati_invalidi");
        }
        
        $tabella = '';
        $colonna_id = ''; 
        $redirect_url = "/palestra/admin/dashboard.php";
        
        switch($entita){
            case 'coach':
                $tabella = 'coach'; 
                $colonna_id = 'id_coach';
                $redirect_url = "/palestra/admin/gestisci_coach.php"; 
            break;

            case 'utente':
                $tabella = 'utente';
                $colonna_id = 'id_utente';
                $redirect_url = "/palestra/admin/gestisci_utenti.php";
            break;

            case 'corso':
                            $tabella = 'corso';
                            $colonna_id = 'id_corso';
                            $redirect_url = "/palestra/admin/gestisci_corsi.php";
            break;

            default:
                redirect("/palestra/admin/dashboard.php?error=operazione_non_permessa");
        }

        try{
            $query = "DELETE FROM $tabella WHERE $colonna_id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            redirect($redirect_url."?msg=cancellazione_ok");
        }catch(PDOException $e){
            if($e->getCode() == '23000'){
                redirect($redirect_url."?error=impossibile_cancellare_dati_collegati");
            } else {
                redirect($redirect_url."?error=errore_db");
            }
        }
    }else{
        redirect("/palestra/index.php");
    }
