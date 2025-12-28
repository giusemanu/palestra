<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('coach');
    if($_SERVER['REQUEST_METHOD']==='POST'){
        $data_anamnesi = $_POST['data_anamnesi'];
        $peso = (float)$_POST['peso'];
        $altezza = (float)$_POST['altezza'];
        $massa_muscolare = (float)$_POST['massa_muscolare'];
        $massa_magra = (float)$_POST['massa_magra'];
        $massa_grassa = (float)$_POST['massa_grassa'];
        $id_utente = (int)$_POST['id_utente'];
        $id_coach = (int)$_SESSION['id_coach'];
        if(!empty($data_anamnesi) && $peso > 0 && $altezza > 0 && $massa_muscolare > 0 && $massa_magra > 0 && $massa_grassa > 0 && !empty($id_utente)){
            try{
                $query = "INSERT INTO anamnesi (data_anamnesi, peso, altezza, massa_muscolare, massa_magra, massa_grassa, utente, coach)
                          VALUES (:data_anamnesi, :peso, :altezza, :massa_muscolare, :massa_magra, :massa_grassa, :id_utente, :id_coach)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([
                    ':data_anamnesi' => $data_anamnesi,
                    ':peso' => $peso,
                    ':altezza' => $altezza,
                    ':massa_muscolare' => $massa_muscolare,
                    ':massa_magra' => $massa_magra,
                    ':massa_grassa' => $massa_grassa,
                    ':id_utente' => $id_utente,
                    ':id_coach' => $id_coach
                ]); 
                redirect("/palestra/coach/gestisci_anamnesi.php?id_utente=$id_utente&msg=anamnesi_inserita");
            }catch(PDOException $e){
                redirect("/palestra/coach/dashboard.php?error=errore_db");
            }
        }else{
            redirect("/palestra/coach/dashboard.php?error=campi_vuoti");
        }
    }else{
        redirect("/palestra/index.php");
    }
?>