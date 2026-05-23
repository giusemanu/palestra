<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('coach');
    if($_SERVER['REQUEST_METHOD']==='POST'){
        $nome_esercizio = trim($_POST['nome_esercizio']);
        $gruppo_muscolare = trim($_POST['gruppo_muscolare']);
        if(empty($nome_esercizio) || empty($gruppo_muscolare)){
            redirect("/palestra/coach/gestisci_esercizi.php?error=campi_vuoti");
        }
        try{
            $query = "INSERT INTO esercizio (nome_esercizio, gruppo_muscolare)
                      VALUES (:nome_esercizio, :gruppo_muscolare)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':nome_esercizio' => $nome_esercizio,
                ':gruppo_muscolare' => $gruppo_muscolare,
            ]);
            redirect("/palestra/coach/gestisci_esercizi.php?msg=esercizio_creato");
        }catch(PDOException $e){
            redirect("/palestra/coach/gestisci_esercizi.php?error=errore_db");
        }
    }else{
        redirect("/palestra/index.php");
    }
?>