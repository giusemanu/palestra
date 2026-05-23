<?php
	require_once __DIR__."/../config/db_conn.php";
	require_once __DIR__."/../includes/functions.php";

	check_login('admin');

	if($_SERVER['REQUEST_METHOD'] === 'POST'){
	    $id_utente = (int)$_POST['utente'];
	    $tipo = trim($_POST['tipo_abbonamento']);
	    $prezzo = (float)$_POST['prezzo'];

	    if($id_utente <= 0 || empty($tipo) || $prezzo < 0){
	        redirect("/palestra/admin/gestisci_abbonamenti.php?error=campi_vuoti");
	    }

		$data_inizio = date('Y-m-d'); 
	    switch($tipo){
	        case 'Mensile':
	            $data_scadenza = date('Y-m-d', strtotime("+30 days"));
	            break;
	        case 'Trimestrale':
	            $data_scadenza = date('Y-m-d', strtotime("+90 days"));
	            break;
	        case 'Annuale':
	            $data_scadenza = date('Y-m-d', strtotime("+365 days"));
	            break;
	        default:
	            redirect("/palestra/admin/gestisci_abbonamenti.php?error=dati_invalidi");
	    }

	    try{
	        $stmtUpdate = $pdo->prepare("UPDATE abbonamento SET stato = 'Scaduto' WHERE utente = ? AND stato = 'Attivo'");
	        $stmtUpdate->execute([$id_utente]);

	        $query = "INSERT INTO abbonamento (tipo_abbonamento, data_inizio, data_scadenza, prezzo, stato, utente) 
	                  VALUES (?, ?, ?, ?, 'Attivo', ?)";
	        $stmt = $pdo->prepare($query);
	        $stmt->execute([$tipo, $data_inizio, $data_scadenza, $prezzo, $id_utente]);

	        redirect("/palestra/admin/gestisci_abbonamenti.php?msg=Abbonamento+attivato+e+rinnovato!");

	    }catch(PDOException $e){
	        redirect("/palestra/admin/gestisci_abbonamenti.php?error=errore_db");
	    }
	}else{
	    redirect("/palestra/admin/dashboard.php");
	}
