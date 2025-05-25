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
            $checkTerms = $_POST['checkTerms'];


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

<body data-bs-theme="light" id="gradientBg">
    <header class="container mt-5 d-flex justify-content-between align-items-center">
        <div>
            <a class="navbar-brand" href="#"><img src="<?= BASE_URL ?>assets/images/logo/logo.svg" style="width: 10rem;" alt="Yoopla logo" class="logo-yoopla" data-logo-light="<?= BASE_URL ?>assets/images/logo/logo.svg"
                    data-logo-dark="<?= BASE_URL ?>assets/images/logo/logoYooplaWhite.svg"></a>
            <h6>Activités pour tous !</h6>
        </div>
        <!-- dark/light mode -->
        <div class="form-check form-switch switchBtn"
            style="--bs-form-switch-width:60px;--bs-form-switch-height:24px"
            title="mode sombre/clair">
            <input class="form-check-input" type="checkbox" role="switch" id="themeSwitch" checked />
            <label class="form-check-label fw-medium fs-6 mt-1" for="themeSwitch">clair</label>
        </div>
        <!-- end switch button -->
    </header>
    <main class="mt-3 container-fluid">
        <?php
        echo $info;
        ?>
        <section class="container">
            <!-- formulaire d'inscription -->
            <div class="bgRegistration m-auto rounded-4" style="  width: 60rem;">
                <fieldset>
                    <legend class="text-center m-3 fw-regular">S'inscrire</legend>

                    <form method="POST" class="mt-3 p-4" id="termsForm">
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
                                        <!-- Modal content -->
                                        <div class="container">
                                            <h3>Conditions Générales d’Utilisation (CGU) de l’application Yoopla</h3>
                                            <p><strong>Dernière mise à jour :</strong> 25/05/2025</p>
                                            <p>Bienvenue sur <strong>Yoopla</strong>, une application web et mobile de <strong>création, gestion et réservation d’événements</strong>. En accédant à notre plateforme, vous acceptez de vous conformer aux présentes <strong>Conditions Générales d’Utilisation (CGU).</strong></p>
                                            <h4>1. Objet</h4>
                                            <p>Les présentes CGU ont pour objet de définir les conditions dans lesquelles les utilisateurs peuvent accéder à l’application Yoopla, créer ou réserver des événements, et utiliser les services proposés.</p>

                                            <h4>2. Définitions</h4>
                                            <ul>
                                                <li><strong>Utilisateur</strong> : toute personne qui accède et utilise les services de Yoopla.</li>
                                                <li><strong>Organisateur</strong> : utilisateur qui crée un événement sur la plateforme.</li>
                                                <li><strong>Participant</strong> : utilisateur qui réserve une place à un événement.</li>
                                                <li><strong>Événement</strong> : toute activité créée via l'application (réunion, atelier, soirée, etc.).</li>
                                            </ul>

                                            <h4>3. Acceptation des conditions</h4>
                                            <p>En vous inscrivant ou en utilisant Yoopla, vous reconnaissez avoir pris connaissance et accepté sans réserve les présentes CGU. Si vous n’acceptez pas ces conditions, veuillez ne pas utiliser l’application.</p>

                                            <h4>4. Inscription et accès</h4>
                                            <p>L’inscription est gratuite. Pour accéder à certaines fonctionnalités (création ou réservation), l’utilisateur doit fournir des informations exactes, complètes et à jour.</p>
                                            <p>L’utilisateur est responsable de la confidentialité de ses identifiants et de toute activité réalisée via son compte.</p>

                                            <h4>5. Utilisation de l’application</h4>
                                            <p>L’utilisateur s’engage à :</p>
                                            <ul>
                                                <li>Utiliser Yoopla dans un cadre légal et respectueux.</li>
                                                <li>Ne pas publier de contenu illicite, offensant ou trompeur.</li>
                                                <li>Ne pas perturber le bon fonctionnement de la plateforme.</li>
                                                <li>Respecter les conditions spécifiques de chaque événement.</li>
                                            </ul>
                                            <p>Yoopla se réserve le droit de suspendre ou supprimer un compte en cas de non-respect des CGU.</p>

                                            <h4>6. Création et gestion d’événements</h4>
                                            <p>L’organisateur est seul responsable du contenu, des informations, du lieu et du déroulement des événements qu’il propose via Yoopla.</p>
                                            <p>Yoopla ne saurait être tenu responsable en cas de litige entre organisateurs et participants.</p>

                                            <h4>7. Réservation et annulation</h4>
                                            <p>Les participants peuvent réserver des événements via la plateforme, sous réserve de disponibilité.</p>
                                            <p>Les conditions d’annulation, de remboursement ou de modification sont fixées par l’organisateur et doivent être clairement affichées dans la description de l’événement.</p>

                                            <h2>8. Tarification</h2>
                                            <p>L’utilisation de Yoopla peut être gratuite ou payante selon les services proposés (ex. : événements premium, réservations payantes).</p>
                                            <p>Toute transaction est sécurisée via des partenaires de paiement tiers.</p>

                                            <h4>9. Propriété intellectuelle</h4>
                                            <p>L’ensemble des contenus présents sur Yoopla (textes, logo, charte graphique, fonctionnalités) est la propriété exclusive de Yoopla ou de ses partenaires.</p>
                                            <p>Toute reproduction, distribution ou utilisation non autorisée est interdite.</p>

                                            <h4>10. Responsabilité</h4>
                                            <p>Yoopla met tout en œuvre pour assurer le bon fonctionnement du service mais ne garantit pas l’absence d’erreurs, d’interruptions ou de défaillances.</p>
                                            <p>Yoopla décline toute responsabilité quant aux contenus, comportements ou événements créés par les utilisateurs.</p>

                                            <h4>11. Données personnelles</h4>
                                            <p>Yoopla collecte et traite les données personnelles dans le respect du RGPD et de sa <a href="<?= BASE_URL ?>authentication/confidentalite.php" target="_blank">Politique de confidentialité</a>.</p>
                                            <p>L’utilisateur dispose d’un droit d’accès, de rectification et de suppression de ses données à tout moment.</p>

                                            <h4>12. Modification des CGU</h4>
                                            <p>Yoopla se réserve le droit de modifier les présentes CGU à tout moment. L’utilisateur sera informé par tout moyen utile (email, notification, publication sur le site).</p>

                                            <h4>13. Droit applicable</h4>
                                            <p>Les présentes CGU sont soumises au droit français et tout litige sera de la compétence exclusive des tribunaux du ressort de Paris.</p>

                                            <hr>

                                            <p><strong>✅ En utilisant l’application, vous reconnaissez avoir lu, compris et accepté les présentes Conditions Générales d’Utilisation.</strong></p>

                                        </div>
                                        <!-- fin modal content -->
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Refuser</button>
                                        <button type="text" class="btn btn-yoopla-primary" name="checkTerms" value="accepted" id="acceptTerms">Accepter</button>
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

    <!-- Bootstrap popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous"></script>
    <!-- customized js -->
    <script src="<?= BASE_URL; ?>assets/script/script.js"></script>
</body>

</html>