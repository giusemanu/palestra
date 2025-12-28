<?php
    require_once __DIR__."/config/db_conn.php";
    require_once __DIR__."/includes/functions.php";
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    $titolo_pagina = "Home - Gym Manager";
    require_once __DIR__."/includes/header.php";
?>

<div class="container">
    
    <?php mostraFeedback();?>

    <?php if(is_logged_in()){?>
        
        <div class="card-box auth-box">
            <div class="welcome-icon">💪</div>
            
            <h1>Bentornato in Palestra!</h1>
            
            <p>
                Sei già connesso come <strong><?= htmlspecialchars($_SESSION['nome'])?></strong>.
            </p>

            <?php 
                $dash_url = ($_SESSION['ruolo'] === 'coach') ? 'coach/dashboard.php' : 'utente/dashboard.php';
            ?>
            
            <a href="<?=$dash_url?>" class="btn btn-block">Vai alla tua Dashboard</a>
        </div>

    <?php }else{?>

        <div class="card-box auth-box">
            <h2>Accedi</h2>
            
            <form action="actions/login.php" method="POST">
                <div class="form-group">
                    <label class="form-label">Email:</label>
                    <input type="email" name="email" required class="form-input">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password:</label>
                    <input type="password" name="password" required class="form-input">
                </div>

                <button type="submit" class="btn btn-block">Login</button>
            </form>
            
            <div>
                Non hai un account? <br>
                Iscriviti in palestra e un Coach ti registrerà!
            </div>
        </div>

    <?php }?>

</div>

<?php require_once __DIR__."/includes/footer.php"; ?>