<?php
require_once '../inc/init.inc.php';
require_once '../inc/functions.inc.php';
//-------------------------- Dashboard Admin --------------------------
$title = "utilisateurs";
$info = '';





if (!isset($_SESSION['user'])) { // si une session n'existe pas avec un identiafaint user je me redérige vers la page de connexion

    header('location:' . BASE_URL . 'authentication/login.php');
} else {

    if ($_SESSION['user']['checkAdmin'] == "user") {

        header('location:' . BASE_URL . 'profil.php');
    }
}


//get all users
$allUsers = getAllUsers();

// get user by id 

// debug($_GET['action']);

if (isset($_GET['action']) && isset($_GET['ID_User'])) {
    $idUser = (int) $_GET['ID_User']; // Sécurisation

    debug($idUser);

    // Récupérer les infos de l'utilisateur cible
    $userToUpdate = getUserById($idUser);

    // debug($userToUpdate);

    if ($userToUpdate) {
        if ($_GET['action'] === "update") {

            debug($_GET['action']);
            // Inverser le rôle
            $newRole = ($userToUpdate['checkAdmin'] === "admin") ? "user" : "admin";
            updateRole($newRole, $idUser);
            $info .= alert("Rôle mis à jour avec succès", "success");

            // Redirection pour éviter resoumission du formulaire
            header("Location: users.php");
            exit();
        }

        if ($_GET['action'] === "delete") {
            if ($userToUpdate['checkAdmin'] !== "admin") { // éviter de supprimer un admin
                deleteUser($idUser);
                $info .= alert("Utilisateur supprimé avec succès", "success");
                header("Location: users.php");
                exit();
            } else {
                $info .= alert("Impossible de supprimer un admin", "danger");
            }
        }
    } else {
        $info .= alert("Utilisateur introuvable", "danger");
    }
}


?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Admin dashboard Yoopla">
    <meta name="author" content="Islem FOURATI">

    <!-- Bootstrap core CSS -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <!-- Fast Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fastbootstrap@2.2.0/dist/css/fastbootstrap.min.css">
    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.12.1/font/bootstrap-icons.min.css">
    <!-- customized css -->
    <!-- <link href="<?= BASE_URL; ?>./assets/css/styles.css" rel="stylesheet"> -->
    <!-- link lottie -->
    <link rel="stylesheet" href="https://lottie.host/09c5e65d-1f86-4978-aaad-b1c3e5eb6ad0/yBcjAsynaq.lottie">

    <title>AdminYoopla <?= $title; ?></title>

</head>

<body class="bg-discovery-subtle">
    <div class="container-fluid d-flex justify-content-start min-vh-100">
        <header class="w-25 bg-discovery m-1 rounded-3 p-3">
            <!-- menu bar -->
            <nav class="nav flex-column mb-auto p-3 mb-8">
                <a class="nav-link text-light fw-meduim" href="<?= BASE_URL . 'admin/dashboard.php'; ?>">Dashboard</a>
                <hr class="bg-light">
                <a class="nav-link text-light fw-meduim active" href="<?= BASE_URL . 'admin/Users.php'; ?>">Gestion des utilisateurs</a>
                <hr class="bg-light">
                <a class="nav-link text-light fw-meduim" href="<?= BASE_URL . 'admin/Events.php'; ?>">Gestion des événements</a>
                <hr class="bg-light">
                <a class="nav-link text-light fw-meduim" href="<?= BASE_URL . 'admin/reservations.php'; ?>">Gestion des réservations</a>
                <hr class="bg-light">
                <div class="mx-auto mt-10">
                    <dotlottie-player
                        src="https://lottie.host/09c5e65d-1f86-4978-aaad-b1c3e5eb6ad0/yBcjAsynaq.lottie"
                        background="transparent"
                        speed="1"
                        style="width: 200px; height: 200px"
                        loop
                        autoplay></dotlottie-player>
                </div>
            </nav>
        </header>
        <main class="w-100 container-fluid">
            <!-- profile -->
            <?php if (isset($_SESSION['admin'])) {  ?>
                <div>
                    <div class="d-flex align-items-end justify-content-end m-1 rounded-3 p-3 bg-danger-subtle shadow mt-O">
                        <div>
                            <h5 class="fs-6 fw-meduim m-3">
                                Bonjour <?= $_SESSION['user']['firstName']; ?>
                            </h5>
                        </div>
                        <div class="avatar-container">
                            <?php

                            $photo_profil_default = BASE_URL . 'assets/images/default-img/default_avatar.jpg';

                            if (isset($_SESSION['user']) && !empty($_SESSION['user']['photo_profil'])) {
                                $photo_profil = BASE_URL . 'assets/images/profils/' . $_SESSION['user']['photo_profil'];
                            }
                            ?>
                            <img src="<?= $photo_profil ?? $photo_profil_default;  ?>" alt="image avatar" class="rounded-circle border border-2 border-white" width="50" height="50">
                            <span class="status-indicator position-absolute top-100 start-50 connected-span translate-middle p-2 border border-light rounded-circle bg-success-yoopla">
                                <span class="visually-hidden">connecté</span>
                            </span>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <!-- profile end -->
            <?php

            echo $info;

            ?>
            <!-- contenu principal -->
            <h1 class="display-6 text-center fw-regular mb-3 fs-2 m-5">Gestion des utilisateurs</h1>

            <div class="w-100 container-fluid m-1 rounded-3 p-3">
                <!-- Users table start -->

                <div class="table-responsive">
                    <table class="table table-hover table-borderless">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prenom</th>
                                <th>Civilité</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <!-- php code -->
                            <?php
                            if (empty($allUsers)) {
                                $info = alert("Aucun utilisateur rencontré", "warning");
                            } else {
                                foreach ($allUsers as $user) {
                                    // --- Data Preparation & Sanitization ---
                                    // Sanitize output to prevent XSS attacks
                                    $user_id = (int) $user['ID_User']; // Ensure ID is integer
                                    $user_firstName = htmlspecialchars($user['firstName'] ?? 'First Name non disponible', ENT_QUOTES, 'UTF-8');
                                    $user_lastName = htmlspecialchars($user['lastName'] ?? 'Last Name non disponible', ENT_QUOTES, 'UTF-8');
                                    $civilite = htmlspecialchars($user['civility'] ?? 'Civilite non disponible', ENT_QUOTES, 'UTF-8');
                                    $user_email = htmlspecialchars($user['email'] ?? 'Email non disponible', ENT_QUOTES, 'UTF-8');
                                    $user_role = htmlspecialchars($user['checkAdmin'] ?? 'Role non disponible', ENT_QUOTES, 'UTF-8');

                            ?>
                                    <tr>
                                        <td><?= $user_id; ?></td>
                                        <td><?= $user_firstName; ?></td>
                                        <td><?= $user_lastName; ?></td>
                                        <td><?= $civilite; ?></td>
                                        <td><?= $user_email; ?></td>
                                        <td><?= $user_role; ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">Editer</button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="<?= BASE_URL ?>admin/Users.php?action=update&ID_User=<?= $user_id ?>">Changer le role</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" onclick="return confirm('Êtes-vous sûr ?')" href="<?= BASE_URL ?>admin/Users.php?action=delete&ID_User=<?= $user_id ?>">Supprimer</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>
                    </table>
                </div>
                <!-- Users table end -->
            </div>
        </main>
    </div>
    <footer class="container d-flex justify-content-around py-3 border-top">
        <a class="navbar-brand" href="#"><img src="<?= BASE_URL . './assets/images/logo/logo.svg'; ?>" class="w-50" alt="Yoopla logo"></a>
        <p class="mb-3 mb-md-0 text-body-secondary w-100">© Admin Dashboard Yoopla - 2025 Islem FOURATI, Inc</p>
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <!-- Bootstrap popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>


    <!-- lottie script -->
    <script
        src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs"
        type="module"></script>

    <!-- fast bootstrap link start -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- fast bootstrap link end -->

</body>

</html>