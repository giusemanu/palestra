<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('coach');
    if($_SERVER['REQUEST_METHOD']==='POST'){
        $id_scheda = (int)$_POST['id_scheda'];
        $tipo_allenamento = trim($_POST['tipo_allenamento']);
        if($id_scheda > 0 && !empty($tipo_allenamento)) {
            try{
                $stmt = $pdo->prepare("INSERT INTO sessione_allenamento (tipo_allenamento, scheda)
                                       VALUES (:tipo_allenamento, :scheda)");
                $stmt->execute([
                    ':tipo_allenamento' => $tipo_allenamento,
                    ':scheda' => $id_scheda
                ]);
                redirect("/palestra/coach/gestisci_sessione.php?id_scheda=$id_scheda&msg=sessione_aggiunta");
            }catch(PDOException $e){
                redirect("/palestra/coach/gestisci_sessione.php?id_scheda=$id_scheda&error=errore_db");
            }
        }else{
            redirect("/palestra/coach/gestisci_sessione.php?id_scheda=$id_scheda&error=campi_vuoti");
        }
    }
    redirect("/palestra/coach/dashboard.php");
?>