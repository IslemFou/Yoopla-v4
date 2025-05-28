<?php
require_once '../inc/init.inc.php';
require_once '../inc/functions.inc.php';

$info = "";
// Génération du token CSRF s’il n’existe pas encore
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
//CSRF signifie Cross-Site Request Forgery, en français « falsification de requête intersites ».
// C’est une attaque informatique qui vise à tromper un utilisateur authentifié sur un site web pour qu’il réalise, à son insu, une action non désirée (comme modifier ses données, faire un achat, ou même changer un mot de passe) en exploitant sa session active.

// --- START Access Control ---
// Check if the user is logged in (adjust the session variable if needed,
if (!isset($_SESSION['user']['ID_User'])) {
    // If not logged in, redirect to the login page
    redirect(BASE_URL . 'login.php'); // redirect function
}
// --- END Access Control ---
//récupération de l'id utilisateur connecté
$id_user = $_SESSION['user']['ID_User'];

// debug($_POST);



if (isset($_GET['action']) && isset($_GET['ID_Event']) && $_GET['action'] == "update") {
    $id_event = htmlentities($_GET['ID_Event']);
    $event = ShowEventViaId($id_event);
    // debug($event);
    if (empty($event)) {
        redirect('home.php');
        exit;
    }
} else {
    $event = null; // Initialize to null if not updating
}
//----------Suppression d'un évenement -----------
if (isset($_GET['action']) && isset($_GET['ID_Event']) && $_GET['action'] == "delete") {
    $id_event = htmlentities($_GET['ID_Event']);

    if (!empty($_GET['action']) && $_GET['action'] == "delete" && !empty($_GET['ID_Event'])) {

        deleteEvent($id_event);
        $info .= alert("L'événement a été supprimé avec succès", "success");
        redirect('home.php');
    }
}


// --- START Form Submission ---

if ($_SERVER['REQUEST_METHOD'] === 'POST' || !empty($_POST)) {

    // --- Input Variables & Initialization ---
    $photo_filename = ''; // Store the final filename for the DB
    $info = ''; // Reset info for this submission
    $verif = true;

      //vérification du token CSRF 
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die("Erreur de sécurité : token CSRF invalide.");
        }

    //-------- Début vérification de l'image

    if (!empty($_FILES['photo']['name'])) { // Check if a file was selected
        // debug($_FILES['photo']);

        if ($_FILES['photo']['error'] === UPLOAD_ERR_OK) { // Check for upload errors

            // Validate file type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            //Cette fonction ouvre une base de données magique et retourne son instance qui va permettre de détecter le type MIME d'un fichier.
            //** FILEINFO_MIME_TYPE précise qu’on veut seulement le type MIME, pas le charset.
            //en procédural :
            //finfo_open(int $flags = FILEINFO_NONE, ?string $magic_database = null): finfo|false
            //** flags: Une ou une union de plusieurs constantes Fileinfo.
            //en orienté objet:
            //public finfo::__construct(int $flags = FILEINFO_NONE, ?string $magic_database = null)
            $verifExtensionFile = finfo_file($finfo, $_FILES['photo']['tmp_name']);
            //Analyse le fichier temporaire téléchargé par l'utilisateur et Retourne quelque chose comme : 'image/jpeg', 'image/png', etc.
            finfo_close($finfo);
            //ferme un fichier magique (la base de données) et libére ses ressources liées à la base de données magique (celle utilisée par fileinfo pour identifier les types de fichiers).

            $extensionsAutorisee = [
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/webp',
                'image/svg+xml'
            ];
            //on Déclare un tableau des types MIME acceptés pour les fichiers image.
            // documentation : https://www.php.net/manual/fr/function.finfo-file.php

            // Validate file size (e.g., max 5MB)
            $max_size = 5 * 1024 * 1024; // 5 MB

            if (in_array($verifExtensionFile, $extensionsAutorisee)) {
                if ($_FILES['photo']['size'] <= $max_size) {
                    // Generate a unique filename to prevent overwrites and security issues
                    $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                    //pathinfo() retourne des informations sur le chemin path, sous forme de chaine ou de tableau associatif
                    $unique_name = uniqid('event_', true) . '.' . strtolower($extension);
                    $upload_path = '../assets/images/' . $unique_name;

                    // Use move_uploaded_file to move the uploaded file to the desired location
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                        $photo_filename = $unique_name; // Store the unique name for the database
                    } else {
                        $info .= alert("Erreur lors de l'enregistrement de l'image.", "danger");
                    }
                } else {
                    $info .= alert("Le fichier est trop volumineux (max 5MB).", "danger");
                }
            } else {
                $info .= alert("Le format du fichier n'est pas autorisé (JPEG, PNG, GIF, SVG).", "danger");
            }
        } else {
            // Handle specific upload errors
            switch ($_FILES['photo']['error']) {
                case UPLOAD_ERR_FORM_SIZE: // Max file size specified in the HTML form was exceeded.
                    $info .= alert("Le fichier est trop volumineux.", "danger");
                    break;
                case UPLOAD_ERR_NO_FILE:
                    // This case might not be reached due to the initial !empty check
                    $info .= alert("Aucun fichier n'a été téléchargé.", "danger");
                    break;
                default:
                    $info .= alert("Erreur lors du téléchargement du fichier (Code: " . $_FILES['photo']['error'] . ").", "danger");
            }
        }
    } else {
        //  Use a default image if none is provided
            // Si on est en update et qu'une image existe déjà, on la garde
    if (!empty($_POST['photo_existante'])) {
        $photo_filename = $_POST['photo_existante'];
    } else {
        // Sinon, image par défaut
        $photo_filename =  $_SERVER['DOCUMENT_ROOT'] . 'Yoopla/assets/images/default-img/default_event.png';
    }
    }


    //-------- Fin vérification de l'image

    //récupération des données du formulaire
    $title = trim($_POST['title'] ?? '');
    $date_start = trim($_POST['date_start'] ?? '');
    $time_start = substr(trim($_POST['time_start'] ?? ''), 0, 5); // Ensure time is in HH:MM format
    $time_end = substr(trim($_POST['time_end'] ?? ''), 0, 5);
    $description = trim($_POST['description'] ?? '');
    $capacity = (int)trim($_POST['capacity'] ?? ''); // Convert to integer
    $price = (float)trim($_POST['price'] ?? ''); // Convert to float
    $zip_code = trim($_POST['zip_code'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $categorie = trim($_POST['categorie'] ?? '');
    $date_end = trim($_POST['date_end'] ?? '');


    // vérification du titre
    if (empty($title) || strlen($title) < 2) {

        $info .= alert("Le champ titre n'est pas valide", "danger");
    }

    //verif Categorie
    if (!isset($categorie) || strlen($categorie) < 2) {

        $info .= alert("la catégorie n'est pas correcte", "danger");
    }



    //verif date de debut
    if (!$date_start || empty($date_start) || !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $date_start)) {

        $info .= alert("La date de debut n'est pas valide", "danger");

    } elseif (!DateTime::createFromFormat('Y-m-d', $date_start)) {

          $info .= alert("Format de date invalide", "danger");

            } else {
               
                $today = date('Y-m-d'); 
                
                if ($date_start === $today) {

                    $info .= alert("La date de début ne peut pas être la date actuelle", "danger");

                } elseif ($date_start < $today) {

                    $info .= alert("La date de début doit être une date future", "danger");
                }
                    }
    

    //verif date de fin
    if (!$date_end ||empty($date_end) || !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $date_end)) {

        $info .= alert("La date de fin n'est pas valide", "danger");

    }elseif (!DateTime::createFromFormat('Y-m-d', $date_start)) {

          $info .= alert("Format de date invalide", "danger");

            } else {
               
                $today = date('Y-m-d'); 
                
                if ($date_end === $today) {

                    $info .= alert("La date de fin ne peut pas être la date actuelle", "danger");

                } elseif ($date_end < $today) {

                    $info .= alert("La date de fin doit être une date future", "danger");
                }
                    }
    
    // une condition pour que la date de début doit etre inférieure ou égale à la date de fin

    if ($date_start > $date_end) {

        $info .= alert("La date de début doit être inférieure ou égale à la date de fin", "danger");
    }

    //verif heure de debut
    // debug($time_start);

    if (!isset($time_start) || !preg_match('/^[0-9]{2}:[0-9]{2}$/', $time_start)) {

        $info .= alert("L'heure de debut n'est pas valide", "danger");
    } 
    
    //verif heure de fin

    if (empty($time_end) || !preg_match('/^[0-9]{2}:[0-9]{2}$/', $time_end)) {

        $info .= alert("L'heure de fin n'est pas valide", "danger");
    } 
    
    //une condition pour que l'heure de debut doit etre inférieure ou égale à l'heure de fin

    if ($time_start > $time_end) {
        $info .= alert("L'heure de début doit être inférieure ou égale à l'heure de fin", "danger");
    }
    //verif code postale // '/^[0-9]{5}$/'
    if (empty($zip_code) || !preg_match('/^[0-9]{5}$/', $zip_code)) {

        $info .= alert("Le code postal n'est pas valide", "danger");
    }

    //verif ville
    if (empty($city) ||  strlen(trim($city)) < 4 || strlen(trim($city)) > 50 || preg_match('/[0-9]/', $city)) {

        $info .= alert("La ville n'est pas valide", "danger");
    }

    //verif pays
    if (empty($country) || strlen($country) < 2) {
        $info .= alert("Le pays n'est pas valide", "danger");
    }

    //verif capacite
    if (empty($capacity) || !is_numeric($capacity)) {
        $info .= alert("La capacité n'est pas valide", "danger");
    }

    //verif prix
    if (empty($price) || !is_numeric($price)) {

        $info .= alert("Le prix n'est pas valide", "danger");
    }
    //verification description

    if (empty($description) || strlen($description) < 2) {

        $info .= alert("Veuillez laisser une description", "danger");
    }


    if (empty($info)) {

        if (isset($_GET) && isset($_GET['action']) && isset($_GET['ID_Event']) && !empty($_GET['action']) && !empty($_GET['ID_Event']) && $_GET['action'] == 'update') {

            $id_event = htmlentities($_GET['ID_Event']);
            $id_user = htmlentities($_SESSION['user']['ID_User']);

            updateEvent(
                $photo_filename,
                $description,
                $title,
                $categorie,
                $date_start,
                $date_end,
                $time_start,
                $time_end,
                $zip_code,
                $city,
                $country,
                $capacity,
                $price,
                $id_event,
                $id_user
            );

            $info .= alert("L'événement a été modifié avec succès", "success");
            // redirect('home.php');
        } else {

            if (verifEvent($title, $date_start, $time_start)) { // si l'évenement existe dans la BDD

                $info .= alert("L'événement existe deja", "danger");
            } else {
                //ajout d'un évenement
                addEvent(
                    $id_user,
                    $photo_filename,
                    $description,
                    $title,
                    $categorie,
                    $date_start,
                    $date_end,
                    $time_start,
                    $time_end,
                    $zip_code,
                    $city,
                    $country,
                    $capacity,
                    $price
                );

                //affichage d'un message de confirmation

                $info .= alert('L\'événement a été ajouté avec succès, <a href="' . BASE_URL . 'home.php" class="fw-medium text-yoopla-blue text-decoration-none">aller à l\'accueil</a>', 'success');
            }
        }
    }
}


// ------ END Form Submission ------




require_once '../inc/header.inc.php';
?>
<section>
    <?php
    echo $info;
    ?>
    <h1 class="text-center fs-2 m-3"><?= isset($event) ?  $event['title'] : 'Ajouter un évenement' ?></h1>

    <form action="" method="post" class="container w-75 bg-light rounded-3 p-3 mb-5" enctype="multipart/form-data">
         <!-- csrf token -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        
        <!-- image $_FILES -->
        <div class="d-flex align-items-center justify-content-center">
            <!-- Si la photo est insérée par l'utilisateur sinon afficher la photo par defaut -->
            <div class="col-md-12 mb-5 border rounded-3 p-3 muted" style="height:10rem; object-fit: cover;
            background-image: url('<?= isset($event) ? BASE_URL . '/assets/images/' . $event['photo'] : BASE_URL . '/assets/images/default-img/default_event.png'; ?>');
            
            ">
                <label for="photo" class="form-label mb-3 text-light"><?= isset($event) ? '<i class="bi bi-pencil-square"></i>' : 'Affiche par défaut' ?></label>
                <br>
                <input type="file" name="photo" id="photo" class="form-control w-50 m-auto" >
                <?php if (isset($event) && !empty($event['photo']) && str_contains($event['photo'], 'event_')) : ?>
                <input type="hidden" name="photo_existante" value="<?= htmlspecialchars($event['photo']) ?>">
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <!-- titre -->
            <div class="col-md-6 mb-5 m-auto">
                <label for="title" class="form-label mb-3 fw-bold">Titre de l'évenement <?= isset($event) ? '<i class="bi bi-pencil-square"></i>' : '' ?></label>

                <input type="text" name="title" id="title" class="form-control" placeholder="Ex: Journée Yoga en plein air ..." value="<?= isset($event) ? $event['title'] : '' ?>">
            </div>
            <!-- genre -->
            <div class="col-md-6 mb-5 m-auto">
                <label for="categorie" class="form-label mb-3 fw-bold">Type de l'évenement <?= isset($event) ? '<i class="bi bi-pencil-square"></i>' : '' ?></label>
                <input type="text" name="categorie" id="categorie" class="form-control" placeholder="Ex: Yoga, sport, culture..." value="<?= isset($event) ? $event['categorie'] : '' ?>">
            </div>
        </div>
        <!-- date début et date de fin -->
        <div class="form-row row">
            <!-- date_start -->
            <div class="col-md-6 mb-5">
                <i class="bi bi-calendar3-event"></i><label for="date_start" class="form-label mb-3 m-1" >Date de début <?= isset($event) ? '<i class="bi bi-pencil-square"></i>' : '' ?></label>
                <input type="date" class="form-control " id="date_start" name="date_start" value="<?= isset($event) ? $event['date_start'] : '' ?>">
            </div>
            <!-- date_end -->
            <div class="col-md-6 mb-5">
                <i class="bi bi-calendar3-event"></i><label for="date_end" class="form-label mb-3 m-1">Date de fin <?= isset($event) ? '<i class="bi bi-pencil-square"></i>' : '' ?></label>
                <input type="date" class="form-control " id="date_end" name="date_end" value="<?= isset($event) ? $event['date_end'] : '' ?>">
            </div>
        </div>
        <div class="row">
            <!-- heure debut-->
            <div class="col-md-6 mb-5">
                <i class="bi bi-clock"></i><label for="time_start" class="form-label mb-3 m-1" >Heure de début <?= isset($event) ? '<i class="bi bi-pencil-square"></i>' : '' ?></label>
                <input type="time" class="form-control " id="time_start" name="time_start" value="<?= isset($event) ? $event['time_start'] : '' ?>">
            </div>
            <!-- heure fin-->
            <div class="col-md-6 mb-5">
                <i class="bi bi-clock"></i><label for="time_end" class="form-label mb-3 m-1" >Heure de fin <?= isset($event) ? '<i class="bi bi-pencil-square"></i>' : '' ?></label>
                <input type="time" class="form-control " id="time_end" name="time_end" value="<?= isset($event) ? $event['time_end'] : '' ?>">
            </div>
        </div>

        <div class="form-row row">
            <!-- code postal -->
            <div class="col-md-4 mb-5">
                <i class="bi bi-mailbox"></i><label for="zip_code" class="form-label mb-3 m-1">Code postale <?= isset($event) ? '<i class="bi bi-pencil-square"></i>' : '' ?></label>
                <input type="text" class="form-control" id="zip_code" name="zip_code" placeholder="75000" value="<?= isset($event) ? $event['zip_code'] : '' ?>">
            </div>
            <!-- Ville -->
            <div class="col-md-4 mb-5">
                <i class="bi bi-geo"></i><label for="city" class="form-label mb-3 m-1">Ville <?= isset($event) ? '<i class="bi bi-pencil-square"></i>' : '' ?></label>
                <input type="text" class="form-control" id="city" name="city" placeholder="Paris" value="<?= isset($event) ? $event['city'] : '' ?>">
            </div>
            <!-- country -->
            <div class="col-md-4 mb-5">
                <i class="bi bi-geo-alt"></i><label for="country" class="form-label mb-3 m-1">Pays <?= isset($event) ? '<i class="bi bi-pencil-square"></i>' : '' ?></label>
                <input type="text" class="form-control" id="country" name="country" placeholder="FRANCE" value="<?= isset($event) ? $event['country'] : '' ?>">
            </div>
        </div>
        <div class="form-row row">
            <!-- prix -->
            <div class="col-md-6 mb-5">
                <i class="bi bi-tag"></i><label for="price" class="form-label mb-3 m-1">Prix <?= isset($event) ? '<i class="bi bi-pencil-square"></i>' : '' ?></label>
                <div class=" input-group">
                    <input type="text" class="form-control" id="price" name="price" aria-label="Euros amount (with dot and two decimal places)" value="<?= isset($event) ? $event['price'] : '' ?>">
                    <span class="input-group-text">€</span>
                </div>
            </div>
            <!-- Capacité max d'acceuil -->
            <div class="col-md-6 mb-5">
                <i class="bi bi-people"></i><label for="number" class="form-label mb-3 m-1">Capacité d'accueil maximale <?= isset($event) ? '<i class="bi bi-pencil-square"></i>' : '' ?></label>
                <input id="number" type="number" name="capacity" min="1" class="form-control" placeholder="30" value="<?= isset($event) ? $event['capacity'] : '' ?>">
            </div>
        </div>
        <!-- description -->
        <div class="">
            <div class="col-12 mb-5">
                <label for="description" class="form-label mb-3">Description <?= isset($event) ? '<i class="bi bi-pencil-square"></i>' : '' ?></label>
                <textarea type="text" class="form-control" id="description" name="description" rows="4" placeholder="Décrivez votre évenement"><?= isset($event) ? $event['description'] : '' ?></textarea>
            </div>
            <!-- Bouton  -->
            <div class="row justify-content-center m-3">
                <button type="submit" class="btn btn-yoopla-primary fw-medium rounded-5 px-4 p-3 w-25" data-bs-toggle="modal" data-bs-target="#ModaladdEvent"><?= isset($event) ? 'Modifier' : 'Ajouter' ?></button>
                <!-- Modal -->
                <div class="modal fade" id="ModaladdEvent" tabindex="-1" aria-labelledby="ModaladdEventLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="ModaladdEventLabel">Confirmation d'ajout d'événement</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Evénement ajouté avec succès !</p>
                            </div>
                            <div class="modal-footer">
                                <a href="#" class="btn btn-danger" data-bs-dismiss="modal">Annuler</a>
                                <a href="<?= BASE_URL ?>index.php" class="btn btn-primary">Page d'acceuil</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin modal -->
        </div>
    </form>
</section>




<?php

require_once '../inc/footer.inc.php';
