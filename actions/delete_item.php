<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('coach');
    if($_SERVER['REQUEST_METHOD']==='POST'){ 
        $id = (int)$_POST['id']; 
        $entita = trim($_POST['entita']);
        $redirect_id = (int)$_POST['redirect_id'];
        if($id <= 0 || empty($entita)){
            redirect("/palestra/coach/dashboard.php?error=dati_invalidi");
        }
        $tabella = '';
        $colonna_id = ''; 
        $redirect_url = "/palestra/coach/dashboard.php";
        switch($entita){
            case 'scheda':
                $tabella = 'scheda'; 
                $colonna_id = 'id_scheda';
                $redirect_url = "/palestra/coach/utenti.php"; 
            break;

            case 'sessione':
                $tabella = 'sessione_allenamento';
                $colonna_id = 'id_sessione_allenamento';
                if($redirect_id > 0) {
                    $redirect_url = "/palestra/coach/gestisci_sessione.php?id=".$redirect_id;
                } else {
                    $redirect_url = "/palestra/coach/dashboard.php";
                }
            break;

            case 'esercizio':
                $tabella = 'esercizio'; 
                $colonna_id = 'id_esercizio';
                $redirect_url = "/palestra/coach/gestisci_esercizi.php"; 
            break;

            case 'esercizio_sessione':
                $tabella = 'prevede';
                $colonna_id = 'id_prevede';
                $redirect_id = (int)$_POST['redirect_id'];
                $redirect_url = "/palestra/coach/gestisci_esercizi_sessione.php?id_sessione=".$redirect_id;
            break;

            default:
                redirect("/palestra/coach/dashboard.php?error=operazione_non_permessa");
        }

        try{
            $query = "DELETE
                      FROM $tabella
                      WHERE $colonna_id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            redirect($redirect_url."?msg=cancellazione_ok");
        }catch(PDOException $e){
            if($e->getCode() == '23000'){
                $sep = (strpos($redirect_url, '?') !== false) ? '&' : '?';
                redirect($redirect_url.$sep."error=impossibile_cancellare_dati_collegati");
            } else {
                redirect("/palestra/coach/dashboard.php?error=db_error");
            }
        }
    }else{
        redirect("/palestra/index.php");
    }
?>