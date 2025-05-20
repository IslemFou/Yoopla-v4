<?php


/* ------------- Création d'une fonction pour se connecter à la base de donnée -------------*/

//constante du server
define("DBHOST", "localhost");

// // constante de l'utilisateur de la BDD du serveur en local => root
define("DBUSER", "root");

// // constante pour le mot de passe de serveur en local => pas de mot de passe
define("DBPASS", "");

// // Constante pour le nom de la BDD
define("DBNAME", "yoopla");

/* --------------------------------
-----------------------------------------------------------------------
Création d'une fonction pour se connecter à la base de donnée
-------------------------------------------------------------------------------------------------
--------------------------------
*/

function connexionBdd(): object
{
    $dsn = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8";


    try {
        $pdo = new PDO($dsn, DBUSER, DBPASS);
        //On définit le mode d'erreur de PDO sur Exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //On définit le mode de "fetch" par défaut
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        // je vérifie la connexion avec ma BDD avec un simple echo
        // echo "Je suis connecté à la BDD";
    } catch (PDOException $e) {  // PDOException est une classe qui représente une erreur émise par PDO et $e c'est l'objetde la clase en question qui vas stocker cette erreur

        die("Erreur : " . $e->getMessage()); // die d'arrêter le PHP et d'afficher une erreur en utilisant la méthode getmessage de l'objet $e
    }
    return $pdo; // on retourne la connexion à la BDD , un objet 
}

connexionBdd();
//--------------- fin fonction connexionBdd --------------