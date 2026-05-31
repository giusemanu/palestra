# Pewter's Gym - Gestione Palestra

Questo progetto è un sistema informativo web-based sviluppato per la gestione completa delle attività tecniche e amministrative di una palestra. 
L'applicativo è sviluppato in **PHP** (Back-end) interfacciato con un database relazionale **SQL**.

## Descrizione del Progetto
Il sistema centralizza la gestione degli iscritti, del personale tecnico e dell'offerta sportiva. L'architettura del database è progettata per preservare l'integrità storica dei dati: ogni utente è seguito da un personal trainer (Coach) e possiede uno storico di schede di allenamento, rilevazioni fisiche (Anamnesi) e abbonamenti fiscali.

L'allenamento è strutturato in modo gerarchico: 
`Scheda ➔ Sessioni Giornaliere ➔ Esercizi (con parametri di carico, serie e recupero)`.

## Ruoli e Funzionalità
Il sistema prevede un rigoroso controllo degli accessi basato su tre ruoli distinti:

* **Admin**
    * Ha una visione globale e amministrativa della struttura.
    * Gestisce l'anagrafica (operazioni CRUD su utenti e coach).
    * Gestisce il palinsesto dei corsi collettivi.
    * Emette e monitora lo storico degli **abbonamenti**, bloccando di fatto l'accesso ai servizi per gli utenti non in regola.

* **Coach**
    * Visualizza esclusivamente l'elenco degli atleti a lui assegnati.
    * Crea, modifica e assegna le **Schede di Allenamento**.
    * Registra periodicamente l'**Anamnesi** (peso, massa magra, massa grassa) per monitorare i progressi.
    * Gestisce il catalogo globale degli esercizi e monitora le iscrizioni ai corsi dei propri atleti.

* **Utente**
    * Accede in modalità sola lettura al proprio profilo.
    * Consulta la scheda di allenamento attuale e l'archivio di quelle passate.
    * Visualizza i grafici/tabelle dei propri progressi fisici (Anamnesi).
    * Può iscriversi o disiscriversi ai corsi collettivi offerti dalla palestra (se in possesso di un abbonamento valido).

## Stack Tecnologico
* **Back-end:** PHP
* **Database:** SQL (MySQL/MariaDB)
* **Front-end:** HTML, CSS
