<?php
	require_once __DIR__."/../config/db_conn.php";
	require_once __DIR__."/../includes/functions.php";

	check_login('admin');

	if($_SERVER['REQUEST_METHOD'] === 'POST'){
	    $nome = trim($_POST['nome']);
	    $cognome = trim($_POST['cognome']);
	    $email = trim($_POST['email']);
	    $telefono = trim($_POST['telefono']);
	    $password = trim($_POST['password']);
	    $data_nascita = trim($_POST['data_nascita']);
	    
	    $coach = !empty($_POST['coach']) ? (int)$_POST['coach'] : null;

	    if(empty($nome) || empty($cognome) || empty($email) || empty($telefono) || empty($password) || empty($data_nascita)){
	        redirect("/palestra/admin/gestisci_utenti.php?error=campi_vuoti");
	    }

	    try{
	        $stmtCheck = $pdo->prepare("SELECT id_utente FROM utente WHERE email = ? OR telefono = ?");
	        $stmtCheck->execute([$email, $telefono]);
	        if($stmtCheck->fetch()){
	            redirect("/palestra/admin/gestisci_utenti.php?error=dati_duplicati");
	        }

	        $query = "INSERT INTO utente (nome, cognome, data_nascita, telefono, email, password, coach) 
	                  VALUES (?, ?, ?, ?, ?, ?, ?)";
	        $stmt = $pdo->prepare($query);
	        $password_hash = password_hash($password, PASSWORD_DEFAULT);	        
	        $stmt->execute([$nome, $cognome, $data_nascita, $telefono, $email, $password_hash, $coach]);
	        redirect("/palestra/admin/gestisci_utenti.php?msg=Atleta+iscritto+con+successo!");
	    }catch(PDOException $e){
	        redirect("/palestra/admin/gestisci_utenti.php?error=errore_db");
	    }
	}else{
	    redirect("/palestra/admin/dashboard.php");
	}
