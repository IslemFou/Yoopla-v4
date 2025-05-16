<?php
require_once 'inc/init.inc.php';
require_once 'inc/functions.inc.php';



if (!isset($_SESSION['user'])) { // si une session existe avec un identifiant user je me redirige vers la page home.php
    header("location:home.php");
}


if (!empty($_POST)) {
    $valid = true; //valeur par defaut de la variable valid qui va permettre de savoir si le formulaire est correct ou non

    foreach ($_POST as $key => $value) {

        if (empty(trim($value))) {

            $valid = false;
            //c'est à dire que le formulaire n'est pas correct
        }
    }

    if (!$valid) {
        $info = alert("Veuillez remplir tous les champs", "danger");
    } else {

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

        if (!isset($_POST['email']) ||  strlen(trim($_POST['email'])) < 5 || strlen(trim($_POST['email'])) > 100 || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

            $info .= alert("Le champ email n'est pas valide", "danger");
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
        if (!isset($_POST["password"]) || !preg_match($regexMdp, $_POST['password'])) {
            $info .= alert("Le mot de passe n'est pas valide, il doit contenir au moins 8 caractères, une lettre majuscule, une lettre minuscule, un chiffre et un caractère special", "danger");
        }
        //confirmation mot de passe
        if (!isset($_POST['confirmMdp']) || $_POST['password'] != $_POST['confirmMdp']) {
            $info .= alert("La confirmation du mot de passe n'est pas valide", "danger");
        }

        if (!isset($_POST['checkAdmin']) || $_POST['checkAdmin'] != "user" && $_POST['checkAdmin'] != "admin") {
            $info .= alert("Le champ rôle n'est pas valide", "danger");
        }

        if (empty($info)) {
            $lastName = htmlspecialchars(trim($_POST['lastName']));
            $firstName = htmlspecialchars(trim($_POST['firstName']));
            $civility = htmlspecialchars(trim($_POST['civility']));
            $email = htmlspecialchars(trim($_POST['email']));
            $password = htmlspecialchars(trim($_POST['password']));
            $confirmMdp = htmlspecialchars(trim($_POST['confirmMdp']));
            $checkAdmin = htmlspecialchars(trim($_POST['checkAdmin']));

            $mdpHash = password_hash($password, PASSWORD_DEFAULT);


            //check if email exist in database
            if (checkUser($email)) {


                // update user
                // addUser($firstName, $lastName, $civility, $email, $mdpHash, $checkAdmin);

                $info = alert("Profil mis à jour avec succès", 'success');
            }
        }
    }
}

?>


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
    <!-- bootstrap  css link-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- favicon  -->
    <link href="assets/images/logo/favIcon.svg" rel="icon">

    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- fast bootstrap link start -->
    <link href="https://cdn.jsdelivr.net/npm/fastbootstrap@2.2.0/dist/css/fastbootstrap.min.css" rel="stylesheet" integrity="sha256-V6lu+OdYNKTKTsVFBuQsyIlDiRWiOmtC8VQ8Lzdm2i4=" crossorigin="anonymous">
    <!-- fast bootstrap link end -->

    <!-- css -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/styles.css">
    <title>Profil</title>
</head>

<body data-bs-theme="light">
    <header class="container-fluid mt-5 d-flex justify-content-around">
        <div class="">
            <a class="navbar-brand" href="#"><img src="<?= BASE_URL ?>assets/images/logo/logo.svg" style="width: 10rem;" alt="Yoopla logo"></a>
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
                <legend>Mon Profil</legend>
                <form action="#" method="POST" class="mt-3 p-4 bg-light rounded-4 bg-opacity-25">
                    <div class="bg-profil col-md-12 mb-5 border rounded-3 p-3">
                        <!-- image par défaut -->
                        <img src="./assets/images/default-img/default_avatar.jpg" alt="image_profil" class="photoProfil rounded-circle border border-2 border-white mb-2" title="image de profil" width="100" height="100">
                    </div>
                    <div class="m-5 d-flex">
                        <label for="photo_profil" class="form-label mt-3" id="inputGroupFile02">Insérer une photo de profil</label>
                        <input type="file" name="photo_profil" class="mx-2 input-group-text text-center">
                    </div>

                    <div class=" mt-3 col">
                        <label for="checkAdmin" class="form-label mb-3">Votre Role</label>
                        <select class="form-select rounded-5" name="checkAdmin">

                            <?php
                            if (isset($_SESSION['user']['checkAdmin']) && $_SESSION['user']['checkAdmin'] == 'admin') {
                                echo '<option value="admin">Administrateur</option>';
                            } else {
                                echo '<option value="user">Utilisateur</option>';
                            }
                            ?>
                        </select>
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
                        <div class=" mt-3 col">
                            <label for="civility" class="form-label mb-3">Civilité</label>
                            <select class="form-select rounded-5" name="civility">
                                <option value="">homme ou femme ?</option>
                                <option value="h">Homme</option>
                                <option value="f">Femme</option>
                            </select>
                        </div>
                        <div>
                            <div class="mt-3 col">
                                <label for="email" class="form-label mb-3">Adresse Email</label>
                                <input type="text" name="email" class="form-control rounded-5" id="email" placeholder="email@example.com" value=" <?= $_SESSION['user']['email'] ?>"">
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
                                <a href="" class=" mt-3 btn text-decoration-none text-danger fw-regular">Supprimer mon profil</a>
                            </div>
                            <div class="col-md-12 m-3 d-flex justify-content-center">

                                <button type="submit" class="col-md-6 col-sm-12 fs-5 text-center btn-lg btn btn-yoopla-primary fw-regular rounded-5 shadow m-3" id="liveToastBtn">Mettre à jour</button>

                                <!-- toast info -->
                                <div class="toast-container position-fixed bottom-0 start-0 p-6">
                                    <div class="toast text-bg-success" id="liveToast" role="alert" aria-live="assertive" aria-atomic="true">
                                        <div class="toast-body">
                                            <div class="d-flex gap-4">
                                                <span><i class="fa-solid fa-circle-check fa-lg icon-success"></i></span>
                                                <div class="d-flex flex-column flex-grow-1 gap-2">
                                                    <div class="d-flex align-items-center">
                                                        <span class="fw-semibold">Votre profil a été mis à jour avec succès !</span>
                                                        <button type="button" class="btn-close btn-close-sm ms-auto" data-bs-dismiss="toast"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <span>I will auto dismiss after 8 seconds.</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                </form>
            </fieldset>
            <div class=" mt-5">
                <a href="index.php" class="text-decoration-none btn-yoopla-secondary-outlined rounded-5 px-5 py-3 fw-medium shadow-sm icon-link icon-link-hover"><i class=" bi bi-arrow-left-square"></i>Retour à la page d'accueil</a>
            </div>
        </section>
    </main>
    <footer class="container d-flex justify-content-around py-3">
        <p>&copy; 2025 Yoopla. Tous droits réservés</p>
    </footer>
    <!-- animation -->
    <script
        src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs"
        type="module"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <!-- Bootstrap popper -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

    <!-- fast bootstrap script start -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- fast bootstrap script end -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>


    <!-- customized js -->
    <script src="<?= BASE_URL; ?>assets/script/script.js"></script>