<?php
require_once '../inc/init.inc.php';
require_once '../inc/functions.inc.php';
$title = "Connexion";



//initialisation des variables
$info = "";
// debug($_SESSION['user']);

if (isset($_SESSION['user'])) {
    redirect('home.php');
}

if (!empty($_POST)) {
    //on verifie si le formulaire est correct
    $valid = true;
    foreach ($_POST as $key => $value) {

        if (empty(trim($value))) {

            $valid = false;
            //c'est à dire que le formulaire n'est pas correct
        }
    }

    if (!$valid) {
        $info .= alert("Veuillez remplir tous les champs", "danger");
    } else {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // debug($_POST['password']);


        // checkUserByEmail 
        debug(checkUserByEmail($email));
        $user = checkUserByEmail($email);
        debug($user);

        $_SESSION['user'] = checkUserByEmail($email);
        unset($_SESSION['user']['password']);

        // debug($user['password']);

        // debug(password_verify($password, $user['password']));

        if ($user) {
            // debug($user);
            if (password_verify($password, $user['password'])) {
                $info .= alert("Connexion reussie", "success");
                //   debug($_SESSION['user']);
                $_SESSION['user'] = $user;
                // je redirige vers la page profilUser.php
                redirect(BASE_URL . "home.php");
                // header("location:user/profilUser.php");
            } else {
                $info .= alert("Email ou mot de passe incorrect", "danger");
            }
        } else {
            $info .= alert(" identifiants incorrect", "danger");
        }
    }
}

?>

<!-- Formulaire de connexion -->

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Islem FOURATI">
    <meta name="description" content="Projet de soutenance de la formation de développeur web">
    <meta name="keywords" content="Projet de soutenance, reservation, HTML, CSS, JS, PHP, MySQL">
    <!-- css -->
    <link rel="stylesheet" href="<?= BASE_URL; ?>assets/css/styles.css">
    <!-- bootstrap  css link-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- favicon  -->
    <link href="<?= BASE_URL; ?>assets/images/logo/favIcon.svg" rel="icon">

    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <title>Se connecter</title>
</head>

<body>
    <header class="container mt-5">
        <a class="navbar-brand" href="#"><img src="<?= BASE_URL ?>assets/images/logo/logo.svg" style="width: 10rem;" alt="Yoopla logo"></a>
    </header>
    <main class="min-vh-100 container">
        <section class="container">
            <h6>Activités pour tous !</h6>
            <?= $info; ?>
            <!-- formulaire de connexion-->
            <div class="container w-50">
                <fieldset>
                    <legend class="display-6 mb-4">Se connecter</legend>

                    <form action="#" method="POST" class="mt-5">
                        <div class="mb-3">
                            <label for="InputEmail1" class="form-label">Adresse Email</label>
                            <input type="text" class="form-control rounded-5" id="InputEmail1" name="email" placeholder="email@example.com">
                        </div>
                        <div class="mb-3 position-relative">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control rounded-5" name="password" placeholder="exemple : Test@123" title="Au moins 8 caractères, une lettre majuscule, une lettre minuscule, un chiffre et un caractère special" id="password">
                            <i class="bi bi-eye-fill position-absolute eyeSlash text-secondary" title="afficher le mot de passe"></i>
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                            <button type="submit" class="mt-3 mx-5 text-center btn btn-yoopla-primary btn-lg fw-regular rounded-5 px-5 shadow">Se connecter</button>
                            <hr class="w-50 mt-5">
                            <button class="btn btn-light fw-regular rounded-5 px-4 py-2 shadow mx-5 text-center disabled" type="button" disabled>
                                <i class="bi bi-google m-2" style="color:#FF0000;"></i>Se connecter avec Google
                            </button>
                        </div>
                        <p class="m-3 w-100 text-center">OU</p>
                        <div class="d-flex justify-content-center align-items-center flex-column    ">
                            <p class="w-100 text-center">Vous n'avez pas de compte?<br><a href="<?= BASE_URL ?>authentication/registration.php" class="text-yoopla-blue fw-medium text-decoration-none">Inscrivez-vous</a></p>
                            <a href="#" class="text-center text-yoopla-blue fw-medium text-decoration-none disabled" type="disabled">Mot de passe oublié ?</a>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <!-- Bootstrap popper -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous"></script>

    <!-- Pooper bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous"></script>
    <!-- customized js -->
    <script src="<?= BASE_URL; ?>assets/script/script.js"></script>
</body>

</html>