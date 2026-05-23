<?php
	require_once __DIR__."/../config/db_conn.php";
	require_once __DIR__."/../includes/functions.php";

	check_login('admin');

	if($_SERVER['REQUEST_METHOD'] === 'POST'){
	    $nome_corso = trim($_POST['nome_corso']);

	    if(empty($nome_corso)){
	        redirect("/palestra/admin/gestisci_corsi.php?error=campi_vuoti");
	    }

	    try{
	        $stmtCheck = $pdo->prepare("SELECT id_corso FROM corso WHERE nome_corso = ?");
	        $stmtCheck->execute([$nome_corso]);
	        if($stmtCheck->fetch()){
	            redirect("/palestra/admin/gestisci_corsi.php?error=dati_duplicati");
	        }

	        $query = "INSERT INTO corso (nome_corso) VALUES (?)";
	        $stmt = $pdo->prepare($query);
	        $stmt->execute([$nome_corso]);

	        redirect("/palestra/admin/gestisci_corsi.php?msg=Nuovo+corso+inserito+nel+palinsesto!");
	    }catch(PDOException $e){
	        redirect("/palestra/admin/gestisci_corsi.php?error=errore_db");
	    }
	}else{
	    redirect("/palestra/admin/dashboard.php");
	}
