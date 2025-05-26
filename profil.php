<?php
require_once 'inc/init.inc.php';
require_once 'inc/functions.inc.php';
$info = '';
$photo_filename = '';
$imgSrc =BASE_URL . 'assets/images/default-img/default_avatar.jpg'; // Chemin par défaut pour l'image de profil 


if (!isset($_SESSION['user'])) { // si une session existe avec un identifiant user je me redirige vers la page home.php
    redirect('index.php');
    exit;
}
// debug($_POST);

$current_photo = $_SESSION['user']['photo_profil'] ?? '';
$photo_to_update = $current_photo;

    if (empty($_SESSION['user']['photo_profil']) || !file_exists('assets/images/profils/' . $_SESSION['user']['photo_profil'])) {
                 $photo_profil = BASE_URL . '/assets/images/default-img/default_avatar.jpg';
              }


    if (isset($_SESSION['user']['photo_profil']) && !empty($_SESSION['user']['photo_profil'])) {

                if (str_contains($_SESSION['user']['photo_profil'], 'profil_')) {
                     $photo_profil = BASE_URL . 'assets/images/profils/' . $_SESSION['user']['photo_profil'];
                
              } else{              
                 $photo_profil = $imgSrc;
              }
            }

// DELETE USER ---------------
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['action'], $_POST['ID_User']) &&
    $_POST['action'] === 'delete' &&
    intval($_POST['ID_User']) === $_SESSION['user']['ID_User']
) {
    $id_user = intval($_POST['ID_User']);

    // debug($id_user);

    if (deleteUser($id_user)) {
        unset($_SESSION['user']);
        $_SESSION['message'] = alert("Profil supprimé avec succès.", "success");
        redirect('authentication/login.php');
        exit;
    } else {
        $info = alert("Suppression échouée pour l'ID: $id_user", "danger");
    }
}

// --------------------------FIN DELETE USER

// debug($_POST);

//----------------------- DEBUT UPDATE PROFIL USER ----------------------------------
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' || !empty($_POST) &&
    $_POST['action'] === 'update'
) {


    if (!empty($_FILES['photo_profil']['name'])) { // Check if a file was selected
        // debug($_FILES['photo']);

        if ($_FILES['photo_profil']['error'] === UPLOAD_ERR_OK) { // Check for upload errors

            // Validate file type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            //Cette fonction ouvre une base de données magique et retourne son instance qui va permettre de détecter le type MIME d'un fichier.
            //** FILEINFO_MIME_TYPE précise qu’on veut seulement le type MIME, pas le charset.
            //en procédural :
            //finfo_open(int $flags = FILEINFO_NONE, ?string $magic_database = null): finfo|false
            //** flags: Une ou une union de plusieurs constantes Fileinfo.
            //en orienté objet:
            //public finfo::__construct(int $flags = FILEINFO_NONE, ?string $magic_database = null)
            $verifExtensionFile = finfo_file($finfo, $_FILES['photo_profil']['tmp_name']);
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
                if ($_FILES['photo_profil']['size'] <= $max_size) {
                    // Generate a unique filename to prevent overwrites and security issues
                    $extension = pathinfo($_FILES['photo_profil']['name'], PATHINFO_EXTENSION);
                    //pathinfo() retourne des informations sur le chemin path, sous forme de chaine ou de tableau associatif
                    $unique_name = uniqid('profil_', true) . '.' . strtolower($extension);
                    $upload_path = 'assets/images/profils/' . $unique_name;

                    // Use move_uploaded_file to move the uploaded file to the desired location
                    if (move_uploaded_file($_FILES['photo_profil']['tmp_name'], $upload_path)) {

                        if (!empty($photo_to_update) && $photo_to_update !== $unique_name && file_exists('assets/images/profils/' . $photo_to_update)) {
                            unlink('assets/images/profils/' . $photo_to_update);
                        }

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
            switch ($_FILES['photo_profil']['error']) {
                case UPLOAD_ERR_FORM_SIZE: // Max file size specified in the HTML form was exceeded.
                    $info .= alert("Le fichier est trop volumineux.", "danger");
                    break;
                case UPLOAD_ERR_NO_FILE:
                    // This case might not be reached due to the initial !empty check
                    $info .= alert("Aucun fichier n'a été téléchargé.", "danger");
                    break;
                default:
                    $info .= alert("Erreur lors du téléchargement du fichier (Code: " . $_FILES['photo_profil']['error'] . ").", "danger");
            }
        }
    }


    //vérification du nom
    if (!isset($_POST['lastName']) || strlen(trim($_POST['lastName'])) < 2 || strlen(trim($_POST['lastName'])) > 50) {

        $info .= alert("Le champ nom n'est pas valide", "danger");
    }
    //vérification du prénom
    if (!isset($_POST['firstName']) || strlen(trim($_POST['firstName'])) < 2 || strlen(trim($_POST['firstName'])) > 50) {
        $info .= alert("Le champ prenom n'est pas valide", "danger");
    }

    //civilité
    if (!isset($_POST['civility']) || $_POST['civility'] != "h" && $_POST['civility'] != "f") {
        $info .= alert("Le champ civilité n'est pas valide", "danger");
    }

    //email
    if (!isset($_POST['email']) || trim($_POST['email']) === '') {
        $email = $_SESSION['user']['email'];
    } elseif (strlen(trim($_POST['email'])) < 5 || strlen(trim($_POST['email'])) > 100 || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $info .= alert("Le champ email n'est pas valide", "danger");
    } else {
        $email = htmlspecialchars(trim($_POST['email']));
    }


    //mot de passe
    $regexMdp = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
    /*
            ^ : Début de la chaîne.
            (?=.*[A-Z]) : Doit contenir au moins une lettre majuscule.
            (?=.*[a-z]) : Doit contenir au moins une lettre minuscule.
            (?=.*\d) : Doit contenir au moins un chiffre.
            (?=.*[@$!%*?&]) : Doit contenir au moins un caractère spécial parmi @$!%*?&
            [A-Za-z\d@$!%*?&]{12,} : Doit être constitué uniquement de lettres majuscules, lettres minuscules, chiffres et caractères spéciaux spécifiés, et doit avoir une longueur minimale de 12 caractères.
            $ : Fin de la chaîne.
       */


    if (!empty($_POST['password'])) {
        if (!preg_match($regexMdp, $_POST['password'])) {
            $info .= alert("Le mot de passe n'est pas valide.", "danger");
        } elseif ($_POST['password'] !== $_POST['confirmMdp']) {
            $info .= alert("La confirmation du mot de passe n'est pas valide.", "danger");
        } else {
            $mdpHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
    } else {
        $mdpHash = getUserPasswordHash($_SESSION['user']['ID_User']);
        if (!$mdpHash) {
            $info .= alert("Erreur : impossible de récupérer le mot de passe actuel.", "danger");
        }
    }

    if (empty($info)) {
        $id_user = $_SESSION['user']['ID_User'];
        $lastName = htmlspecialchars(trim($_POST['lastName']));
        $firstName = htmlspecialchars(trim($_POST['firstName']));
        $civility = htmlspecialchars(trim($_POST['civility']));

        //check if user exist in database
        if (checkUser($id_user, $email)) {

            // update user
            updateUser($id_user, $firstName, $lastName, $photo_filename, $civility, $email, $mdpHash);


            $userUpdated = checkUserByEmail($email);

            // Mise à jour de la session 
            if ($userUpdated) {
                unset($userUpdated['password']); // on ne veux pas garder le mot de passe en session
                $_SESSION['user'] = $userUpdated;
            }


            $info .= alert("Profil mis à jour avec succès", 'success');
        } else {
            $info .= alert('Erreur sur la mise à jour de votre profil', 'danger');
        }
    }
}
//---------------------------------------------------FIN UPDATE PROFIL USER
?>



<!-- HTML de la page d'inscription -->
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Islem FOURATI">
    <meta name="description" content="Projet de soutenance de la formation de développeur web">
    <meta name="keywords" content="Projet de soutenance, reservation, HTML, CSS, JS, PHP, MySQL">

    <title>Profil</title>
    <!-- bootstrap  css link-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <!-- favicon  -->
    <link href="assets/images/logo/favIcon.svg" rel="icon">

    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- fast bootstrap link start -->
    <link href="https://cdn.jsdelivr.net/npm/fastbootstrap@2.2.0/dist/css/fastbootstrap.min.css" rel="stylesheet" integrity="sha256-V6lu+OdYNKTKTsVFBuQsyIlDiRWiOmtC8VQ8Lzdm2i4=" crossorigin="anonymous">
    <!-- fast bootstrap link end -->
    <!-- css -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/styles.css">

    <!-- customized js -->
    <script src="<?= BASE_URL; ?>assets/script/script.js" defer></script>
</head>

<body data-bs-theme="light">
    <header class="container-fluid mt-5 d-flex justify-content-around">
        <div>
            <a class="navbar-brand" href="<?= BASE_URL ?>home.php"><img src="<?= BASE_URL ?>assets/images/logo/logo.svg" style="width: 10rem;" alt="Yoopla logo" class="logo-yoopla" data-logo-light="<?= BASE_URL ?>assets/images/logo/logo.svg"
                    data-logo-dark="<?= BASE_URL ?>assets/images/logo/logoYooplaWhite.svg"></a>
            <h6>Activités pour tous !</h6>
        </div>
        <!-- dark/light mode -->
        <div class="form-check form-switch switchBtn"
            style="--bs-form-switch-width:60px;--bs-form-switch-height:24px"
            title="mode sombre/clair">
            <input class="form-check-input" type="checkbox" role="switch" id="switchSizeLargeChecked" checked />
            <label class="form-check-label fw-medium fs-6 mt-1" for="switchSizeLargeChecked">clair</label>
        </div>
        <!-- end switch button -->
    </header>
    <main class="mt-3 container-fluid">
        <section class="container">
            <fieldset class="container w-75 m-auto">
                <?php
                echo $info;
                // debug($_SESSION['user']['photo_profil']);
                ?>
                <legend>Mon Profil</legend>
                <form action="" method="POST" class="mt-3 p-4 bg-light rounded-4 bg-opacity-25" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update">
                    <div class="bg-profil col-md-12 mb-5 border rounded-3 p-3">
                        <!-- image par défaut -->
                        <img src="<?php
                        
                        if (empty($_SESSION['user']['photo_profil']) || !file_exists('assets/images/profils/' . $_SESSION['user']['photo_profil'])) {
                            echo BASE_URL . 'assets/images/default-img/default_avatar.jpg';
                        } else {
                            echo BASE_URL . 'assets/images/profils/' . $_SESSION['user']['photo_profil'];
                        }
                         
                        ?>" alt="image_profil" class="photoProfil rounded-circle border border-2 border-white mb-2" title="image de profil" width="100" height="100" style="object-fit:cover;" id="photoPreview">
                    </div>
                    <div class="m-5 d-flex">
                        <label for="photo_profil" class="form-label mt-3" id="inputGroupFile02">modifier ma photo de profil</label>
                        <input type="file" name="photo_profil" id="photo_profil" class="mx-2 input-group-text text-center" accept="image/*" value="<?php
                        
                        if (empty($_SESSION['user']['photo_profil']) || !file_exists('assets/images/profils/' . $_SESSION['user']['photo_profil'])) {
                            echo BASE_URL . 'assets/images/default-img/default_avatar.jpg';
                        } else {
                            echo BASE_URL . 'assets/images/profils/' . $_SESSION['user']['photo_profil'];
                        }
                         
                        ?>">
                    </div>
                    <div class="col">
                        <div class="row">
                            <?php

                            if ($_SESSION['user']['checkAdmin'] == 'admin') {
                                echo '<div class="col">';
                            ?>
                                <div class="col">
                                    <label for="checkAdmin" class="form-label mb-3">Votre Role</label>
                                    <select class="form-select rounded-5" name="checkAdmin">
                                        <option selected value="admin">Administrateur</option>
                                        <option value="user">Utilisateur</option>
                                    </select>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                        <div class="col">
                            <label for="civility" class="form-label mb-3">Civilité</label>
                            <select class="form-select rounded-5" name="civility">
                                <?php
                                if (isset($_SESSION['user']['civility']) && $_SESSION['user']['civility'] == 'h') {
                                    echo '<option selected value="h">Homme</option>';
                                    echo '<option value="h">Homme</option>';
                                } else {
                                    echo '<option selected value="f">Femme</option>';
                                    echo '<option value="h">Homme</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3 row">
                        <div class="mt-3 col">
                            <label for="firstName" class="form-label">Prénom</label>
                            <input type="text" name="firstName" class="form-control rounded-5" placeholder="First name" aria-label="First name" value=" <?= $_SESSION['user']['firstName'] ?>">
                        </div>
                        <div class="mt-3 col">
                            <label for="lastName" class="form-label">Nom</label>
                            <input type="text" name="lastName" class="form-control rounded-5" placeholder="Last name" aria-label="Last name" value=" <?= $_SESSION['user']['lastName'] ?>">
                        </div>
                    </div>
                    <div>
                        <div class=" mt-3 col">
                            <label for="old_email" class="form-label mb-3">Adresse Email</label>
                            <input type="text" name="old_email" class="form-control rounded-5" id="old_email" placeholder="email@example.com" value=" <?= $_SESSION['user']['email'] ?>"">
                        </div>
                                <div class=" mt-3 col">
                            <label for="email" class="form-label mb-3">Nouvelle adresse Email</label>
                            <input type="text" name="email" class="form-control rounded-5" id="email" placeholder="email@example.com">
                        </div>
                    </div>
                    <label for=" password" class="mt-5 form-label mb-3 fs-5">Mettre à jour votre mot de passe</label>
                    <div class=" row">
                        <div class="col-md-6 mb-3  position-relative">
                            <label for="password" class="form-label labelConfirm mb-3">Mot de passe</label>
                            <input type="password" class="form-control rounded-5" name="password" placeholder="exemple : Test@123" title="Au moins 8 caractères, une lettre majuscule, une lettre minuscule, un chiffre et un caractère special" id="password">
                            <i class="bi bi-eye-fill position-absolute eyeSlash text-secondary" title="afficher le mot de passe"></i>
                        </div>
                        <div class="col-md-6 mb-3  position-relative">
                            <label for="confirmMdp" class="form-label mb-3">Confirmation mot de passe</label>
                            <input type="password" class="form-control mb-3 rounded-5 password" id="confirmMdp" name="confirmMdp" placeholder="Confirmer votre mot de passe "><i class="bi bi-eye-fill position-absolute eyeSlashConfirm text-secondary" title="afficher le mot de passe"></i>
                        </div>
                    </div>
                    <div class="col-md-12 m-3 d-flex justify-content-center">

                        <button type="submit" class="col-md-6 col-sm-12 fs-5 text-center btn-lg btn btn-yoopla-primary fw-regular rounded-5 shadow m-3">Mettre à jour</button>
                    </div>
                </form>
            </fieldset>



                                <!-- suppression du profil  -->


            <form action="" method="POST" class="text-center" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre profil ?');">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="ID_User" value="<?= $_SESSION['user']['ID_User'] ?>">

                <button type="button" class="btn btn-secondary rounded-5 fw-medium icon-link icon-link-hover" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-trash3-fill"></i> Supprimer mon profil</button>

            <!-- <button type="button" disabled class=" mt-3 btn text-decoration-none text-danger fw-regular" data-bs-toggle="modal" data-bs-target="#exampleModal">Supprimer mon profil</button> -->

                    <!-- debut modal suppression  -->
                     <div class="modal fade" tabindex="-1" id="exampleModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Confirmer la suppression du profil</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="text-center modal-body m-3">
                                    <i class="bi bi-exclamation-circle-fill text-danger mx-2"></i>Voulez-vous vraiment supprimer votre profil ?
                                </div>

                                <form method="post" action="" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre profil ?');" class="modal-footer">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="ID_User" value="<?= $_SESSION['user']['ID_User'] ?>">
                                    <div class="modal-footer">

                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-outline-danger">Oui, supprimer mon profil</button>

                                    </div>
                                </form>
                            </div>
                        </div>

                         <!-- fin modal suppression  -->

                    </div>
                    <!-- fin suppression du profil  -->
                     
            </form>
            <div class=" mt-5">
                <a href="home.php" class="text-decoration-none btn-yoopla-secondary-outlined rounded-5 px-5 py-3 fw-medium shadow-sm icon-link icon-link-hover"><i class=" bi bi-arrow-left-square"></i>Retour à la page d'accueil</a>
            </div>
        </section>
    </main>
    <footer class="container d-flex justify-content-around py-3">
        <p>&copy; 2025 Yoopla. Tous droits réservés</p>
    </footer>
    <!-- animation -->
    <!-- Bootstrap 5.3.5 CSS -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7"
        crossorigin="anonymous" />

    <!-- Your Lottie player (needs to stay a type="module" script) -->
    <script
        type="module"
        src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs">
    </script>

    <!-- Bootstrap 5.3.5 JS bundle (includes Popper.js) -->
    <script
        defer
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous">
    </script>

</body>

</html>