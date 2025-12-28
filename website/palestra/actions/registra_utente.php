<?php
    require_once __DIR__."/../config/db_conn.php";
    require_once __DIR__."/../includes/functions.php";
    check_login('coach');
    if($_SERVER['REQUEST_METHOD']==='POST'){
        $nome = trim($_POST['nome']);
        $cognome = trim($_POST['cognome']);
        $data_nascita = $_POST['data_nascita'];
        $telefono = trim($_POST['telefono']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $coach = trim($_SESSION['id_coach']);
        if(empty($nome) || empty($cognome) || empty($data_nascita) || empty($telefono) || empty($email) || empty($coach)){
            redirect("/palestra/coach/dashboard.php?error=campi_vuoti");
        }else{
            if($data_nascita > date('Y-m-d')){
                redirect("/palestra/coach/utenti.php?error=data_futura");
            }
            $password = trim(password_hash($_POST['password'],PASSWORD_DEFAULT));
            try{
                $query = "INSERT INTO utente (nome, cognome, data_nascita, telefono, email, password, coach)
                          VALUES (:nome, :cognome, :data_nascita, :telefono, :email, :password, :coach)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([
                    ':nome' => $nome,
                    ':cognome' => $cognome,
                    ':data_nascita' => $data_nascita,
                    ':telefono' => $telefono,
                    ':email' => $email,
                    ':password' => $password,
                    ':coach' => $coach
                ]);
                redirect("/palestra/coach/dashboard.php?msg=utente_registrato");
            }catch(PDOException $e){
                if($e->getCode() == 23000){
                    $messaggio_errore = $e->getMessage();
                    if(strpos($messaggio_errore, 'email') !== false){
                        redirect("/palestra/coach/dashboard.php?error=email_esistente");                        
                    }
                    elseif(strpos($messaggio_errore, 'telefono') !== false){
                        redirect("/palestra/coach/dashboard.php?error=telefono_esistente");                        
                    }
                    else{
                        redirect("/palestra/coach/dashboard.php?error=dati_duplicati");                        
                    }
                }else{
                    redirect("/palestra/coach/dashboard.php?error=errore_db");                    
                }
            }
        }
    }else{
        redirect("/palestra/index.php");
    }
?>