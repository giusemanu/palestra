<?php
    session_start();
    require_once __DIR__."/../config/db_conn.php"; 
    require_once __DIR__."/../includes/functions.php";
    if($_SERVER['REQUEST_METHOD']==='POST'){
        $email = trim($_POST['email']);
        $password_inserita = $_POST['password'];
        if(empty($email) || empty($password_inserita)){
            redirect("/palestra/index.php?error=campi_vuoti");
        }
        try{
            $query = "SELECT *
                      FROM utente
                      WHERE email = :email";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if($user){
                if(password_verify($password_inserita, $user['password'])){
                    session_regenerate_id(true);
                    $_SESSION['is_logged_in'] = true;
                    $_SESSION['id_utente'] = $user['id_utente']; 
                    $_SESSION['ruolo'] = 'utente';
                    $_SESSION['nome'] = $user['nome'];
                    redirect("/palestra/utente/dashboard.php");
                }else{
                    redirect("/palestra/index.php?error=credenziali_errate");
                }
            }

            $queryCoach = "SELECT * FROM coach WHERE email = :email";
            $stmt = $pdo->prepare($queryCoach);
            $stmt->execute([':email' => $email]);
            $coach = $stmt->fetch(PDO::FETCH_ASSOC);
            if($coach){           
                if(password_verify($password_inserita, $coach['password'])){             
                    session_regenerate_id(true);
                    $_SESSION['is_logged_in'] = true;
                    $_SESSION['id_coach'] = $coach['id_coach']; 
                    $_SESSION['ruolo'] = 'coach';
                    $_SESSION['nome'] = $coach['nome'];
                    redirect("/palestra/coach/dashboard.php");
                }else{
                    redirect("/palestra/index.php?error=credenziali_errate");
                }
            }
            redirect("/palestra/index.php?error=credenziali_errate");

        }catch (PDOException $e){   
            redirect("/palestra/index.php?error=errore_sistema");
        }
    }else{
        redirect("/palestra/index.php");
    }
?>