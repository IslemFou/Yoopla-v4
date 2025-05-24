<?php
require_once '../inc/init.inc.php';
require_once '../inc/functions.inc.php';

if (isset($_SESSION['user'])) { // si une session existe avec un identifiant user je me redirige vers la page home.php
    header("location:home.php");
}

$info = "";
$title = "S'inscrire à Yoopla";

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

        if (!isset($_POST['checkTerms']) || $_POST['checkTerms'] != "accepted") {
            $info .= alert("Veillez accepter les conditions d'utilisation", "danger");
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
            if (checkUserByEmail($email)) {
                $info .= alert('Email deja existant, vous pouvez vous connecter vers votre <a href="' . BASE_URL . 'login.php">se connecter</a> ou vous inscrire vers un autre <a href="' . BASE_URL . 'authentication/registration.php" class="text-decoration-none text-yoopla-blue fw-bold">compte', 'warning');
            } else {
                addUser($firstName, $lastName, $civility, '', $email, $mdpHash, $checkAdmin);
                $info = alert("Vous êtes bien inscrit(e), vous pouvez vous connectez <a href='" . BASE_URL . "authentication/login.php' class='text-yoopla-blue text-decoration-none fw-bold fw-bold'>ici</a>", 'success');
            }
        }
    }
}

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

    <!-- css -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/styles.css">
    <title><?= $title; ?></title>
</head>

<body>
    <header class="container mt-5 d-flex justify-content-between align-items-center">
        <div>
            <a class="navbar-brand" href="#"><img src="<?= BASE_URL ?>assets/images/logo/logo.svg" style="width: 10rem;" alt="Yoopla logo" class="logo-yoopla"></a>
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
        <?php
        echo $info;
        ?>
        <section class="container">
            <!-- formulaire d'inscription -->
            <div class="bgRegistration m-auto rounded-4" style="background-color:rgb(255, 225, 225);  width: 60rem;">
                <fieldset>
                    <legend class="text-center m-3 fw-regular">S'inscrire</legend>

                    <form action="" method="POST" class="mt-3 p-4" id="termsForm">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-5">
                                <label for="lastName" class="form-label mb-3">Nom</label>
                                <input type="text" class="form-control rounded-5" name="lastName" id="lastName" placeholder="Nom" autocomplete="family-name">
                            </div>
                            <div class="col-md-6 mb-5">
                                <label for="firstName" class="form-label mb-3">Prenom</label>
                                <input type="text" class="form-control  rounded-5" id="firstName" name="firstName" placeholder="Prenom" autocomplete="given-name">
                            </div>
                            <div class="col-md-6 mb-5">
                                <label for="civility" class="form-label mb-3">Civilité</label>
                                <select class="form-select rounded-5" name="civility">
                                    <option value="">homme ou femme ?</option>
                                    <option value="h">Homme</option>
                                    <option value="f">Femme</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label mb-3">Adresse Email</label>
                                <input type="text" class="form-control rounded-5" name="email" id="email" placeholder="email@example.com" autocomplete="email">
                            </div>
                            <div class="col-md-6 mb-3  position-relative">
                                <label for="password" class="form-label  mb-3">Mot de passe</label>
                                <input type="password" class="form-control rounded-5" name="password" placeholder="exemple : Test@123" title="Au moins 8 caractères, une lettre majuscule, une lettre minuscule, un chiffre et un caractère special" id="password" autocomplete="new-password">
                                <i class="bi bi-eye-fill position-absolute eyeSlash text-secondary" title="afficher le mot de passe"></i>
                            </div>
                            <div class="col-md-6 mb-3  position-relative">
                                <label for="confirmMdp" class="form-label labelConfirm mb-3">Confirmation mot de passe</label>
                                <input type="password" class="form-control mb-3 rounded-5 password" id="confirmMdp" name="confirmMdp" placeholder="Confirmer votre mot de passe " autocomplete="new-password"><i class="bi bi-eye-fill position-absolute eyeSlashConfirm text-secondary" title="afficher le mot de passe"></i>
                            </div>
                            <!-- ceci est un input de type hiddent afin d'assignier un role user par défaut à l'utilisateur qui s'inscrit -->
                            <input type="text" hidden name="checkAdmin" value="user">

                            <!-- checkbox -->
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="flexCheckDefault" name="checkTerms" value="accepted">
                                <label class="form-check-label" for="flexCheckDefault" style="cursor:pointer;">
                                    J'accepte les termes et conditions
                                </label>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Voir les conditions</a>
                            </div>
                            <!-- end checkbox -->

                            <div class="col-md-12 mb-3 row justify-content-center">
                                <button type="submit" class="col-md-6 col-sm-12 fs-5 text-center btn-lg btn btn-yoopla-primary fw-regular rounded-5 shadow m-3">S'inscrire</button>
                            </div>
                            <div class="row justify-content-center">
                                <button class="btn btn-light fw-regular rounded-5 col-md-6 col-sm-12 shadow m-3 mx-auto disabled" type="disabled">
                                    <i class="bi bi-google m-2" style="color:#FF0000;"></i>Se connecter avec Google
                                </button>
                            </div>
                            <p class="mt-5 text-center">Vous avez dèjà un compte ! <a href="<?= BASE_URL ?>authentication/login.php" class=" text-yoopla-blue fw-medium">connectez-vous</a></p>
                        </div>
                        <!-- Modal Bootstrap -->
                        <div class="modal fade" id="termsModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="termsModalLabel">Termes et Conditions</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        Voici les termes et conditions que vous devez accepter pour continuer.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Refuser</button>
                                        <button type="text" class="btn btn-primary" name="checkTerms" value="accepted" id="acceptTerms">Accepter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </fieldset>
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



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>


    <!-- customized js -->
    <script src="<?= BASE_URL; ?>assets/script/script.js"></script>
</body>

</html>