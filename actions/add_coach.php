<?php
	require_once __DIR__."/../config/db_conn.php";
	require_once __DIR__."/../includes/functions.php";

	check_login('admin');

	if($_SERVER['REQUEST_METHOD'] === 'POST'){
	    $nome = trim($_POST['nome']);
	    $cognome = trim($_POST['cognome']);
	    $data_nascita = trim($_POST['data_nascita']);
	    $telefono = trim($_POST['telefono']);
	    $email = trim($_POST['email']);
	    $password = trim($_POST['password']);

	    if(empty($nome) || empty($cognome) || empty($data_nascita) || empty($telefono) || empty($email) || empty($password)){
	        redirect("/palestra/admin/gestisci_coach.php?error=campi_vuoti");
	    }

	    if(strtotime($data_nascita) > time())
	        redirect("/palestra/admin/dashboard.php?error=data_futura");

	    try{
	        $stmtCheck = $pdo->prepare("SELECT id_coach FROM coach WHERE email = ? OR telefono = ?");
	        $stmtCheck->execute([$email, $telefono]);
	        if ($stmtCheck->fetch()) {
	            redirect("/palestra/admin/gestisci_coach.php?error=dati_duplicati");
	        }

	        $query = "INSERT INTO coach (nome, cognome, data_nascita, telefono, email, password) VALUES (?, ?, ?, ?, ?, ?)";
	        $stmt = $pdo->prepare($query); 
	        $password_hash = password_hash($password, PASSWORD_DEFAULT);	        
	        $stmt->execute([$nome, $cognome, $data_nascita, $telefono, $email, $password_hash]);
	        redirect("/palestra/admin/gestisci_coach.php?msg=Registrazione+completata+con+successo!");
	    }catch(PDOException $e){
	        redirect("/palestra/admin/gestisci_coach.php?error=errore_db");
	    }
	}else{
	    redirect("/palestra/admin/dashboard.php");
	}
