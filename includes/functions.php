<?php
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    function escape($testo){
        return htmlspecialchars($testo, ENT_QUOTES, 'UTF-8');
    }

    function redirect($url){
        header("Location: " . $url);
        exit();
    }

    function is_logged_in(){
        return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
    }

    function check_login($ruolo_richiesto){
        if(!isset($_SESSION['ruolo'])){
            redirect("/palestra/index.php?error=sessione_scaduta");
        }
        if($_SESSION['ruolo'] != $ruolo_richiesto){
            if($_SESSION['ruolo'] === 'utente'){
                redirect("/palestra/utente/dashboard.php");
            }elseif($_SESSION['ruolo'] === 'coach'){
                redirect("/palestra/coach/dashboard.php");
            }else{
                redirect("/palestra/actions/logout.php");
            }
        }
    }

    function mostraFeedback() {
        if(isset($_GET['error'])){
            $messaggio = "";    
            switch ($_GET['error']){
                case 'campi_vuoti': $messaggio = "Devi compilare tutti i campi del modulo."; break;
                case 'dati_duplicati': $messaggio = "I dati inseriti sono già presenti nel sistema."; break;
                case 'errore_db': $messaggio = "Si è verificato un problema tecnico col database."; break;
                case 'db_error': $messaggio = "Errore nel salvataggio dei dati."; break;
                case 'accesso_negato': $messaggio = "Non hai i permessi per visualizzare questa risorsa."; break;
                case 'id_mancante': $messaggio = "Parametro identificativo mancante."; break;
                case 'dati_invalidi': $messaggio = "I dati inviati non sono validi."; break;
                case 'email_esistente': $messaggio = "L'indirizzo email inserito è già in uso."; break;
                case 'telefono_esistente': $messaggio = "Numero di telefono già associato ad un altro utente."; break;
                case 'credenziali_errate': $messaggio = "Email o Password errati."; break;
                case 'sessione_scaduta': $messaggio = "Sessione scaduta, per favore riaccedi."; break;
                case 'account_non_trovato': $messaggio = "Nessun account trovato con questa email."; break;
                case 'utente_non_trovato': $messaggio = "L'atleta selezionato non esiste."; break;
                case 'scheda_non_trovata': $messaggio = "La scheda richiesta non è stata trovata."; break;
                case 'sessione_non_trovata': $messaggio = "La sessione di allenamento non esiste."; break;
                case 'impossibile_cancellare_dati_collegati': $messaggio = "Impossibile eliminare: ci sono dati collegati a questo elemento."; break;
                case 'date_invalide': $messaggio = "La data di fine scheda non può essere antecedente a quella d'inizio"; break;
                case 'data_futura': $messaggio = "La data di nascita non può essere nel futuro!"; break;
                default: $messaggio = "Errore imprevisto."; break;
            }
            echo '<div class="error-msg">'.$messaggio.'</div>';
        }

        if(isset($_GET['msg'])){
            $messaggio = "";
            switch ($_GET['msg']){
                case 'cancellazione_ok': $messaggio = "Eliminazione effettuata con successo."; break;
                case 'utente_registrato': $messaggio = "Nuovo utente registrato correttamente!"; break;
                case 'registrazione_ok': $messaggio = "Registrazione completata! Ora puoi accedere."; break;
                case 'logout_success': $messaggio = "Logout effettuato correttamente."; break;
                case 'registrato': $messaggio = "Iscrizione al corso effettuata!"; break;
                case 'iscrizione_cancellata': $messaggio = "Iscrizione dal corso rimossa."; break;
                case 'scheda_creata': $messaggio = "Scheda creata con successo."; break;
                case 'date_aggiornate': $messaggio = "Validità della scheda aggiornata."; break;
                case 'sessione_aggiunta': $messaggio = "Nuova sessione di allenamento creata."; break;
                case 'esercizio_aggiunto': $messaggio = "Esercizio inserito nella sessione."; break;
                case 'esercizio_creato': $messaggio = "Esercizio aggiunto all'archivio globale."; break;
                case 'anamnesi_inserita': $messaggio = "Dati biometrici registrati correttamente!"; break;
                default: $messaggio = htmlspecialchars($_GET['msg']); break;
            }
            echo '<div class="success-msg">'.$messaggio.'</div>';
        }
    }
?>