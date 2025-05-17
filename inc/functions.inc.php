<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$_SESSION['message'] = "";
unset($_SESSION['user']['password']);

//------------------ Constantes ----------------
define('BASE_URL', "http://localhost/yoopla/");


$info = '';



//--------------- Déconnexion --------------------------------

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    redirect(BASE_URL . 'authentication/login.php');
    exit; // Ensure script stops after redirect
} else if (isset($_GET['action']) && $_GET['action'] === '') {
    session_destroy();
    redirect(BASE_URL . 'login.php');
    exit;
}




//---------------- fonction alert ----------------
/**
 * Retourne un message d'alerte avec un contenu et une classe Bootstrap.
 * 
 * @param string $contenu Le contenu du message d'alerte.
 * @param string $class La classe Bootstrap du message d'alerte.
 * 
 * @return string Le message d'alerte.
 */
function alert(string $contenu, string $class = "warning"): string
{
    return "<div class=\"alert alert-$class alert-dismissible fade show text-center w-50 m-auto mb-5\" role=\"alert\">
                $contenu
            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
        </div>";
}

//---------------- fonction de redirection -----------
/**
 * Redirect the user to a specified URL.
 *
 * This function sends a raw HTTP header to redirect the user to the given URL.
 * The script execution is terminated immediately after the redirection.
 *
 * @param string $url The URL to redirect to.
 * @return void
 */
function redirect(string $url): void
{
    header("Location: $url");
    exit;
}


//---------------- fonction debug ----------------

/**
 * Fonction de debug
 * 
 * @param mixed $bug
 * 
 * @return void
 */
function debug($bug)
{
    echo "<pre class=\"alert alert-info\">";
    var_dump($bug);
    echo "</pre>";
}

//---------------- fonction déconnexion ---------------

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header('Location: ' . BASE_URL . 'connexion.php');
} else if (isset($_GET['action']) && $_GET['action'] == '') {
    session_destroy();
    header('Location: ' . BASE_URL . 'connexion.php');
}

################################################################

//--------------------Création des clés étrangères ------------------------------
/**
 * Crée une clé étrangère sur une table.
 *
 * @param string $tableFK Le nom de la table qui reçoit la clé étrangère.
 * @param string $keyFK Le nom de la colonne qui reçoit la clé étrangère.
 * @param string $tablePK Le nom de la table qui possède la clé primaire.
 * @param string $keyPK Le nom de la colonne qui possède la clé primaire.
 * @return void
 */
function foreignKey(string $tableFK, string $keyFK, string $tablePK, string $keyPK): void
{
    $cnx = connexionBdd();
    try {

        $sql = "ALTER TABLE $tableFK ADD FOREIGN KEY ($keyFK) REFERENCES $tablePK ($keyPK)";
        $request = $cnx->exec($sql);
    } catch (Exception $e) {
        global $info;
        $info = alert("Impossible d'ajouter la clé étrangère ({$keyFK}) de la table {$tableFK} -> qui corrrespond a la clé primaire ({$keyPK}) de la table {$tablePK}: " . $e->getMessage(), "danger");
    }
}


#############################################################


//------------------ User ------------------

/**
 * Crée la table "users" si elle n'existe pas déjà.
 *
 * La table "users" contient les champs suivants :
 * - ID_User : clé primaire auto-incrémentée
 * - firstName : prénom de l'utilisateur
 * - lastName : nom de l'utilisateur
 * - civility : titre de civilité ('f' pour femme, 'h' pour homme)
 * - email : adresse e-mail de l'utilisateur
 * - Password : mot de passe de l'utilisateur
 * - CheckAdmin : boolean pour savoir si l'utilisateur est administrateur
 *
 * @return void
 */
function UsersTable(): void
{
    try {
        $cnx = connexionBdd();
        $sql = "CREATE TABLE IF NOT EXISTS users (
            ID_User INT PRIMARY KEY AUTO_INCREMENT,
            firstName VARCHAR(255) NOT NULL,
            lastName VARCHAR(255) NOT NULL,
            photo_profil VARCHAR(255) DEFAULT NULL,
            civility ENUM('f', 'h') NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            checkAdmin ENUM('user', 'admin') NOT NULL DEFAULT 'user'
        )";
        $request = $cnx->exec($sql);
    } catch (Exception $e) {
        global $info;
        $info = alert("Error creating users table: " . $e->getMessage(), "danger");
    }
}

// UsersTable();

//---- GET admin Users ----
function getAllUsers()
{
    try {
        $cnx = connexionBdd();
        $sql = "SELECT * FROM users ORDER BY ID_User ASC";
        $request = $cnx->query($sql);
        return $request->fetchAll();
    } catch (Exception $e) {
        global $info;
        $info = alert("Error getting all users: " . $e->getMessage(), "danger");
    }
}

//------------------ Création de la table Event -----------------

/**
 * Creates the Event table in the database if it does not already exist.
 * The table will store information about different events.
 *
 * @return void
 */
function eventTable(): void
{
    try {
        $cnx = connexionBdd(); // Establish a connection to the database

        // SQL query to create the Event table with the necessary columns and constraints
        $sql = "CREATE TABLE IF NOT EXISTS events (
            ID_Event INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
            ID_User INT NOT NULL,  
            photo VARCHAR(255) NOT NULL,  
            description TEXT NOT NULL,  
            title VARCHAR(255) NOT NULL, 
            categorie VARCHAR(255) NOT NULL,
            date_start DATE NOT NULL,  
            date_end DATE NOT NULL,  
            time_start TIME NOT NULL,  
            time_end TIME NOT NULL,  
            zip_code VARCHAR(50) NOT NULL,  
            city VARCHAR(50) NOT NULL,  
            country VARCHAR(50),  
            capacity INT UNSIGNED NOT NULL DEFAULT 0,  
            price DECIMAL(10, 2)
            -- FOREIGN KEY (ID_User) REFERENCES users (ID_User)  
        )";

        // Execute the SQL query
        $request = $cnx->exec($sql);
    } catch (Exception $e) {
        global $info;
        $info = alert("Error creating event table: " . $e->getMessage(), "danger");
    }
}
// eventTable();
// foreignKey("events", "ID_User", "users", "ID_User");


//------------------ Création de la table reservations ------------------

/**
 * Création de la table "reservations"
 * 
 * @return void
 */
function reservationsTable(): void
{
    try {
        $cnx = connexionBdd();
        $sql = "CREATE TABLE IF NOT EXISTS reservations (
        ID_reservations INT PRIMARY KEY AUTO_INCREMENT,
        ID_User INT NOT NULL,
        ID_Event INT NOT NULL,
        date_reservation DATETIME DEFAULT CURRENT_TIMESTAMP,
        status ENUM('accepted', 'declined') DEFAULT 'accepted',
        message_reservation TEXT DEFAULT NULL
        )";
        $request = $cnx->exec($sql);
    } catch (Exception $e) {
        global $info;

        $info = alert("Erreur lors de la création de la table 'reservations': " . $e->getMessage(), "danger");
    }
}

// reservationsTable();
// foreignKey("reservations", "ID_Event", "events", "ID_Event");
// foreignKey("reservations", "ID_User", "users", "ID_User");

//___________ getReservations _________

function getAllReservations()
{
    try {
        $cnx = connexionBdd();
        $sql = "SELECT * FROM reservations";
        $request = $cnx->query($sql);
        return $request->fetchAll();
    } catch (Exception $e) {
        global $info;
        $info = alert("Error getting reservations: " . $e->getMessage(), "danger");
    }
}


/// ---------------- user Reservations ------
//Cette fonction renvoie toutes les reservations d'un utilisateur donné. 
function getUserReservations(int $ID_User)
{
    try {
        $cnx = connexionBdd();
        $sql = "SELECT * FROM reservations WHERE ID_User = :ID_User";
        $request = $cnx->prepare($sql);
        $request->execute(array(':ID_User' => $ID_User));
        return $request->fetchAll();
    } catch (Exception $e) {
        global $info;
        $info = alert("Error getting user reservations: " . $e->getMessage(), "danger");
    }
}



//____________________ Ajout Event _________________________

/**
 * Adds a new event to the database.
 *
 * @param string $photo The path to the event's photo.
 * @param string $description A description of the event.
 * @param string $title The title of the event.
 * @param string $date_start The start date of the event.
 * @param string $date_end The end date of the event.
 * @param string $time_start The start time of the event.
 * @param string $time_end The end time of the event.
 * @param string $zip_code The zip code of the event location.
 * @param string $city The city of the event location.
 * @param ?string $country The country of the event location.
 * @param int $capacity The maximum capacity of attendees.
 * @param float $price The price of the event.
 *
 * @return void
 */
function addEvent(
    int $user_id,
    string $photo,
    string $description,
    string $title,
    string $categorie,
    string $date_start,
    string $date_end,
    string $time_start,
    string $time_end,
    string $zip_code,
    string $city,
    ?string $country,
    int $capacity,
    float $price
): void {


    $data = [
        'ID_User' => $user_id,
        'photo' => $photo,
        'description' => $description,
        'title' => $title,
        'categorie' => $categorie,
        'date_start' => $date_start,
        'date_end' => $date_end,
        'time_start' => $time_start,
        'time_end' => $time_end,
        'zip_code' => $zip_code,
        'city' => $city,
        'country' => $country,
        'capacity' => $capacity,
        'price' => $price
    ];

    foreach ($data as $key => $value) {
        $data[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    // Validate the capacity and price
    $capacity = filter_var($capacity, FILTER_VALIDATE_INT);
    $price = filter_var($price, FILTER_VALIDATE_FLOAT);

    if (!$capacity || !$price) {
        echo "Veuillez fournir une capacité ou un prix valides.";
        return;
    }

    try {
        // Connect to the database
        $cnx = connexionBdd();


        // Prepare the SQL query
        $sql = "INSERT INTO events (ID_User,photo, description, title, categorie, date_start, date_end, time_start, time_end, zip_code, city, country, capacity, price) VALUES (:ID_User,:photo, :description, :title, :categorie, :date_start, :date_end, :time_start, :time_end, :zip_code, :city, :country, :capacity, :price)";

        // Execute the query
        $request = $cnx->prepare($sql);
        $request->execute($data);
    } catch (Exception $e) {

        global $info;

        $info = alert("Une erreur s'est produite lors de l'ajout de l'événement à la base de données.", "danger");
    }
}

//--------- getAllEvents -----------
function getAllEvents(): array
{
    try {
        $cnx = connexionBdd();
        $sql = "SELECT
                    e.ID_Event, e.photo, e.description, e.title, e.categorie,
                    e.date_start, e.date_end, e.time_start, e.time_end,
                    e.zip_code, e.city, e.country, e.capacity, e.price,
                    u.firstName, u.lastName -- Select user's names from the users table
                FROM
                    events e -- Alias event table as 'e'
                INNER JOIN
                    users u ON e.ID_User = u.ID_User -- Join condition
                ORDER BY
                    e.date_start DESC, e.time_start DESC";
        $request = $cnx->query($sql);
        $result = $request->fetchAll();
        return $result;
    } catch (Exception $e) {
        global $info;
        $info = alert("Une erreur s'est produite lors de la recherche de tous les événements.", "danger");
        return [];
    }
}

//------------ Limited Events -----------
function getLimitedEvents(): array
{
    try {
        $cnx = connexionBdd();
        $sql = "SELECT 
        e.ID_Event, e.photo, e.description, e.title, e.categorie,
                    e.date_start, e.date_end, e.time_start, e.time_end,
                    e.zip_code, e.city, e.country, e.capacity, e.price,
                    u.firstName, u.lastName  
        FROM events e 
        INNER JOIN users u ON e.ID_User = u.ID_User
        ORDER BY date_start 
        DESC, time_start DESC LIMIT 6";
        $request = $cnx->query($sql);
        $result = $request->fetchAll();
        return $result;
    } catch (Exception $e) {
        global $info;
        $info = alert("Une erreur s'est produite lors de la recherche des événements limités.", "danger");
        return [];
    }
}




//-------- showEventViaId -----------

function showEventViaId(int $id): array
{
    try {
        $cnx = connexionBdd();
        $sql = "SELECT 
        e.ID_Event, e.photo, e.description, e.title, e.categorie,
                    e.date_start, e.date_end, e.time_start, e.time_end,
                    e.zip_code, e.city, e.country, e.capacity, e.price,
                    u.firstName, u.lastName, u.ID_User -- Select user's names from the users table
                FROM
                    events e -- Alias event table as 'e'
                INNER JOIN
                    users u ON e.ID_User = u.ID_User -- Join condition 
                WHERE e.ID_Event = :id";
        $request = $cnx->prepare($sql);
        $request->execute(array(':id' => $id));
        $result = $request->fetch();
        return $result;
    } catch (Exception $e) {
        global $info;
        $info = alert("Une erreur s'est produite lors de la recherche de l'événement.", "danger");
        return [];
    }
}

//---------------- verif Event ------------

function verifEvent(string $title, string $date_start, string $time_start)
{
    try {
        $cnx = connexionBdd();
        $sql = "SELECT * FROM events
     WHERE
    title = :title
     AND
    date_start = :date_start
     AND
    time_start = :time_start";
        $request = $cnx->prepare($sql);
        $request->execute(array(
            ':title' => $title,
            ':date_start' => $date_start,
            ':time_start' => $time_start
        ));
        $result = $request->fetch();
        return $result;
    } catch (Exception $e) {
        global $info;
        $info = alert("Une erreur s'est produite lors de la recherche de l'événement.", "danger");
    }
}


//-------------- UPDATE EVENT --------------
/**
 * Updates an event in the database.
 *
 * @param string $photo The event's photo.
 * @param string $description The event's description.
 * @param string $title The event's title.
 * @param string $categorie The event's category.
 * @param string $date_start The event's start date.
 * @param string $date_end The event's end date.
 * @param string $time_start The event's start time.
 * @param string $time_end The event's end time.
 * @param string $zip_code The event's zip code.
 * @param string $city The event's city.
 * @param string $country The event's country.
 * @param int $capacity The event's capacity.
 * @param float $price The event's price.
 * @param int $ID_Event The event's ID.
 * @param int $ID_User The user's ID who created the event.
 *
 * @return void
 */
function updateEvent(string $photo, string $description, string $title, string $categorie, string $date_start, string $date_end, string $time_start, string $time_end, string $zip_code, string $city, string $country, int $capacity, float $price, int $ID_Event, int $ID_User): void
{
    try {
        $cnx = connexionBdd();
        $data = [
            'photo' => $photo,
            'description' => $description,
            'title' => $title,
            'categorie' => $categorie,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'time_start' => $time_start,
            'time_end' => $time_end,
            'zip_code' => $zip_code,
            'city' => $city,
            'country' => $country,
            'capacity' => $capacity,
            'price' => $price,
            'ID_Event' => $ID_Event,
            'ID_User' => $ID_User
        ];

        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
        }

        $sql = "UPDATE events SET
                photo = :photo,
                description = :description,
                title = :title,
                categorie = :categorie,
                date_start = :date_start,
                date_end = :date_end,
                time_start = :time_start,
                time_end = :time_end,
                zip_code = :zip_code,
                city = :city,
                country = :country,
                capacity = :capacity,
                price = :price,
                ID_User = :ID_User
                WHERE ID_Event = :ID_Event";
        $request = $cnx->prepare($sql);
        $request->execute($data);
    } catch (Exception $e) {
        global $info;
        $info = alert("Une erreur s'est produite lors de la mise à jour de l'événement." . $e->getMessage(), "danger");
    }
}

//------------------ DELETE EVENT ------------------
/**
 * Deletes an event from the database based on its ID.
 *
 * @param int $id The ID of the event to delete.
 *
 * @return void
 */
function deleteEvent(int $id): void
{
    try {
        $cnx = connexionBdd();
        $sql = "DELETE FROM events WHERE ID_Event = :id";
        $request = $cnx->prepare($sql);
        $request->execute(array(
            ':id' => $id
        ));
    } catch (Exception $e) {
        global $info;
        $info = alert("Une erreur s'est produite lors de la suppression de l'événement." . $e->getMessage(), "danger");
    }
}

//------------------- SHOW USER EVENTS ---------------

function showUserEvents(int $id)
{
    try {
        $cnx = connexionBdd();
        $sql = "SELECT * FROM events WHERE ID_User = :id
        ORDER BY date_start DESC";
        $request = $cnx->prepare($sql);
        $request->execute(array(
            ':id' => $id
        ));
        return $request->fetchAll();
    } catch (Exception $e) {
        global $info;
        $info = alert("Une erreur s'est produite lors de la récupération de vos événements: " . $e->getMessage(), "danger");
    }
}
//--------------------------------------------------------------------
//----------------------- USER ----------------------------------
//-----------------------------------------------------------------------



//_______________________ ADD USER _________________________

function addUser(string $firstName, string $lastName, string $photo_profil,  string $civility, string $email, string $password, string $checkAdmin): void
{
    // Create an associative array with column names of the users table as keys
    $data = [
        'firstName' => $firstName,
        'lastName' => $lastName,
        'photo_profil' => $photo_profil,
        'civility' => $civility,
        'email' => $email,
        'password' => $password,
        'checkAdmin' => $checkAdmin
    ];

    foreach ($data as $key => $value) {
        // Sanitize data to prevent XSS attacks
        $data[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    try {
        // Establish a database connection
        $cnx = connexionBdd();

        // Prepare an SQL statement to insert the user data
        $sql = "INSERT INTO users (firstName, lastName, civility, email, password, checkAdmin) VALUES (:firstName, :lastName, :photo_profil, :civility, :email, :password, :checkAdmin)";
        $request = $cnx->prepare($sql);

        // Execute the prepared statement with the user data
        $request->execute($data);
    } catch (Exception $e) {
        global $info;

        // Set a global alert message in case of an error
        $info = alert("Erreur de connexion au serveur de base de données ou de requêtes SQL." . $e->getMessage(), "danger");
    }
}

//__________________ Check if user exist ____________________

function checkUser(int $ID_User, string $email)
{
    try {
        // Establish a database connection
        $cnx = connexionBdd();

        // Prepare the SQL query to find the user by email
        $sql = "SELECT * FROM users WHERE
        ID_User = :ID_User AND email = :email";
        $request = $cnx->prepare($sql);

        // Execute the query with the email parameter
        $request->execute(array(
            ':ID_User' => $ID_User,
            ':email' => $email
        ));

        // Fetch the result as an associative array
        $result = $request->fetch();

        // Return the result
        return $result;
    } catch (Exception $e) {
        // Global alert for any error during user lookup
        global $info;
        $info = alert("Une erreur s'est produite lors de la recherche de l'utilisateur." . $e->getMessage(), "danger");
        return false; // Return false in case of an error
    }
}
//--------------UPDATE USER ---------------------

function updateUser(int $idUser, string $firstName, string $lastName, string $photo_profil, string $civility, string $email, string $password, string $checkAdmin): void
{
    try {
        $cnx = connexionBdd();
        $data = [
            'ID_User' => $idUser,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'photo_profil' => $photo_profil,
            'civility' => $civility,
            'email' => $email,
            'password' => $password,
            'checkAdmin' => $checkAdmin
        ];

        $sql = "UPDATE users SET
        firstName = :firstName,
        lastName = :lastName,
        photo_profil = :photo_profil,
        civility = :civility,
        email = :email,
        password = :password,
        checkAdmin = :checkAdmin
        WHERE ID_User = :ID_User";
        $request = $cnx->prepare($sql);
        $request->execute($data);
    } catch (Exception $e) {
        global $info;
        $info .= alert('Une erreur s\'est produite lors de la mise à jour de l\'utilisateur.' . $e->getMessage(), 'danger');
    }
}


//--------------- DELETE USER ------------

// function deleteUser(int $id): bool
// {
//     try {
//         $cnx = connexionBdd();
//         $sql = "DELETE FROM users WHERE ID_User = :id";
//         $request = $cnx->prepare($sql);
//         $request->execute([':id' => $id]);

//         // Vérifie si une ligne a été supprimée
//         if ($request->rowCount() > 0) {
//             return true;
//         } else {
//             global $info;
//             $info = alert("Aucun utilisateur supprimé. L'ID est peut-être invalide.", "warning");
//             return false;
//         }
//     } catch (Exception $e) {
//         global $info;
//         $info = alert("Une erreur s'est produite lors de la suppression : " . $e->getMessage(), "danger");
//         return false;
//     }
// }


// ---------------- Check user by email -------------

function checkUserByEmail(string $email)
{
    try {
        $cnx = connexionBdd();
        $sql = "SELECT * FROM users WHERE email = :email";
        $request = $cnx->prepare($sql);
        $request->execute(array(
            ':email' => $email
        ));
        $result = $request->fetch();
        return $result;
    } catch (Exception $e) {
        global $info;
        $info .= alert('Une erreur s\'est produite lors de la recherche de l\'utilisateur par email.' . $e->getMessage(), 'danger');
        return false;
    }
}

/////// ----------------- GET PASSWORD USER ------
function getUserPasswordHash(int $userId): ?string
{
    try {
        $cnx = connexionBdd();
        $stmt = $cnx->prepare("SELECT password FROM users WHERE ID_User = :id");
        $stmt->execute([':id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['password'] ?? null;
    } catch (Exception $e) {
        return null;
    }
}




//--------------------------------------------------------------------
//----------------------- RESERVATION ----------------------------------
//-----------------------------------------------------------------------

/**
 * Adds a new reservation to the database.
 *
 * @param int $ID_User The ID of the user making the reservation.
 * @param int $ID_Event The ID of the event being reserved.
 * @param string $status The status of the reservation (accepted or declined).
 * @param string $message The message left by the user when making the reservation.
 *
 * @return int|bool The ID of the inserted reservation or false if an error occurred.
 */
function addReservation(int $ID_User, int $ID_Event, string $status, string $message): int|bool
{
    try {
        $cnx = connexionBdd();
        $sql = "INSERT INTO reservations (
            ID_User,
            ID_Event,
            status,
            message_reservation) 
        VALUES (
            :ID_User,
            :ID_Event,
            :status,
            :message_reservation)";
        $request = $cnx->prepare($sql);
        $request->execute(
            array(
                ':ID_User' => $ID_User,
                ':ID_Event' => $ID_Event,
                ':status' => $status,
                ':message_reservation' => $message
            )
        );
        // return $request;
        if ($request->rowCount() > 0) {
            return (int)$cnx->lastInsertId(); // Return the ID of the inserted reservation
        } else {
            return false;
        }
    } catch (Exception $e) {
        global $info;
        $info = alert("Erreur lors de l'ajout de la réservation: " . $e->getMessage(), "danger");
        return false;
    }
}



//-------------------- END RESERVATION ---------------------


//--------------------- get reservation via ID ------------

/**
 * Fetches a reservation from the database by its ID.
 *
 * @param int $id The ID of the reservation to fetch.
 * @return array The reservation data as an associative array.
 */
function getReservationViaId(int $id): array
{
    try {
        // Establish a database connection
        $cnx = connexionBdd();

        // Prepare the SQL query to fetch the reservation
        $sql = "SELECT * FROM reservations WHERE ID_reservations = :id";
        $request = $cnx->prepare($sql);

        // Execute the query with the ID parameter
        $request->execute(array(':id' => $id));

        // Fetch the result as an associative array
        $result = $request->fetch();

        // Return the result
        return $result;
    } catch (Exception $e) {
        // Global alert for any error during reservation lookup
        global $info;
        $info = alert("Erreur au niveau de fetch de la reservation by ID ($id): " . $e->getMessage(), "danger");
        return []; // Return an empty array in case of an error
    }
}


//--------------------- END get reservation via ID ------------


//---------------- DELETE reservation ------------------
function deleteReservation(int $id): void
{
    try {
        $cnx = connexionBdd();
        $sql = "DELETE FROM reservations WHERE ID_reservations = :id";
        $request = $cnx->prepare($sql);
        $request->execute(array(
            ':id' => $id
        ));
    } catch (Exception $e) {
        global $info;
        $info = alert("Une erreur s'est produite lors de la suppression de la reservation." . $e->getMessage(), "danger");
    }
}

//------------------- END DELETE reservation ------------------ 


//*********** */


//--------------- fonction reservation exisit --------------

function reservationExists(int $ID_User, int $ID_Event): bool
{
    try {
        $cnx = connexionBdd();
        $sql = "SELECT * FROM reservations WHERE ID_User = :ID_User AND ID_Event = :ID_Event";
        $request = $cnx->prepare($sql);
        $request->execute(array(
            ':ID_User' => $ID_User,
            ':ID_Event' => $ID_Event
        ));
        $result = $request->fetch();
        return !empty($result);
    } catch (Exception $e) {
        global $info;
        $info = alert("Une erreur s'est produite lors de la recherche de la reservation." . $e->getMessage(), "danger");
        return false;
    }
}


///----------------------------------------------------------------------------
//------------------- fonctions  pour afficher les utilisateurs qui ont réservé l'évènement -------------------------

// 1. Récupérer la liste des réservations pour cet événement
function getReservationsByEventId(int $idEvent): array
{
    try {
        $cnx = connexionBdd();
        $sql = "SELECT ID_User FROM reservations WHERE ID_Event = :id";
        $stmt = $cnx->prepare($sql);
        $stmt->execute([':id' => $idEvent]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);  // Récupère un tableau d'IDs utilisateurs
    } catch (Exception $e) {
        global $info;
        $info .= alert('Une erreur s\'est produite lors de la récupération des réservations.' . $e->getMessage(), 'danger');
        return [];
    }
}

// 2. Récupérer les infos utilisateurs par leur ID
function getUsersByIds(array $ids): array
{
    try {
        $cnx = connexionBdd();
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT * FROM users WHERE ID_User IN ($placeholders)";
        $stmt = $cnx->prepare($sql);
        $stmt->execute($ids);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        global $info;
        $info .= alert('Une erreur s\'est produite lors de la récupération des utilisateurs.' . $e->getMessage(), 'danger');
        return [];
    }
}
//La ligne 929 :

// est une astuce classique en PHP pour créer une liste de placeholders dynamiques ? dans une requête SQL préparée :

// $placeholders = implode(',', array_fill(0, count($ids), '?'));

//Elle sert à générer quelque chose comme ceci : ?, ?, ?, ?
//c’est-à-dire une liste de ? séparés par des virgules, selon le nombre d’éléments dans $ids

/*
count($ids) // renvoie 3 car il y a 3 éléments dans l'array $ids
array_fill(0, 3, '?')
→ Crée un tableau avec 3 fois le caractère '?' 
['?', '?', '?']
implode(',', ['?', '?', '?']) // renvoie une chaine de caractère "?,?,?"

$placeholders = "?,?,?";


Dans SQL, l'instruction IN (...) permet de filtrer une colonne pour qu’elle corresponde à une ou plusieurs valeurs. Par exemple, ID_User = 2 OR ID_User = 5 OR ID_User = 9

De ce fait, notre requete semble à ça : 

SELECT * FROM users WHERE ID_User IN (2, 5, 9)

-- Ces ? sont des placeholders, c'est-à-dire des emplacements qui seront remplacés automatiquement par les vraies valeurs de $ids au moment de l’exécution avec

$stmt->execute($ids); //	Fournit les vraies valeurs qui remplacent chaque ?

✅ de sécuriser la requête (contre l'injection SQL)

✅ de construire une requête flexible (on peux avoir 1, 3, 10 ou 100 IDs)

✅ de ne pas faire manuellement une requête à la main

*/







////////////////////////////////////////////
///////// SEARCH FUNCTION /////////////////

function searchEvent($city, $title)
{
    try {
        $cnx = connexionBdd();
        $sql = "SELECT * FROM events 
        WHERE
         title LIKE :searchTitle 
         AND
          city LIKE :searchCity";
        $request = $cnx->prepare($sql);
        $request->execute(array(
            ':searchCity' => '%' . $city . '%',
            ':searchTitle' => '%' . $title . '%' //% = joker qui signifie "peu importe ce qu’il y a ici (zéro ou plusieurs caractères)"
            // contient "le mot recherché" n’importe où dans le texte
        ));
        $result = $request->fetchAll();
        return $result;
    } catch (Exception $e) {
        global $info;
        $info = alert("Une erreur s'est produite lors de la recherche de l'événement." . $e->getMessage(), "danger");
    }
}


//////
