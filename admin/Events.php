<?php
require_once '../inc/init.inc.php';
require_once '../inc/functions.inc.php';
//--------------------------  Admin --------------------------
$title = "evenements";

$_SESSION['admin']['role'] = 'admin'; // role admin

$allEvents = getAllEvents();

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

    <!-- HEAD : Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


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
            <nav class="nav flex-column mb-auto p-3">
                <a class="nav-link text-light fw-meduim" href="<?= BASE_URL . 'admin/dashboard.php'; ?>">Dashboard</a>
                <hr class="bg-light">
                <a class="nav-link text-light fw-meduim active" href="<?= BASE_URL . 'admin/users.php'; ?>">Gestion des utilisateurs</a>
                <hr class="bg-light">
                <a class="nav-link text-light fw-meduim" href="<?= BASE_URL . 'admin/events.php'; ?>">Gestion des événements</a>
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
                            <img src="<?= $photo_profil ?? $photo_profil_default;  ?>" alt="image avatar" style="object-fit: cover;" class="rounded-circle border border-2 border-white" width="50" height="50">
                            <!-- <span class="status-indicator position-absolute top-100 start-50 connected-span translate-middle p-2 border border-light rounded-circle bg-success-yoopla">
                                <span class="visually-hidden">connecté</span>
                            </span> -->
                        </div>
                    </div>
                </div>
            <?php } ?>
            <!-- contenu principal -->
            <h1 class="display-6 text-center fw-regular mb-3 fs-2 m-5">Gestion des événements</h1>
            <!-- event table start -->
            <div class="table-responsive mt-3">
                <table class="table table-hover table-borderless">
                    <thead>
                        <tr>
                            <th>ID event</th>
                            <th>Organisateur</th>
                            <!-- <th>Photo</th> -->
                            <th>Description</th>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Date de début</th>
                            <th>Date de fin</th>
                            <th>Horaires de début</th>
                            <th>Horaires de fin</th>
                            <th>Code postale de l'evenement</th>
                            <th>Ville</th>
                            <th>Pays</th>
                            <th>Capacité maximale</th>
                            <th>Tarifs billet</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <?php
                        if (empty($allEvents)) {
                            $info = alert("Aucun évènement rencontré", "warning");
                        } else {
                            foreach ($allEvents as $event) {
                                // --- Data Preparation & Sanitization ---
                                // Sanitize output to prevent XSS attacks
                                $event_id = (int) $event['ID_Event']; // Ensure ID is integer
                                $event_title = htmlspecialchars($event['title'] ?? 'Titre non disponible', ENT_QUOTES, 'UTF-8');

                                $event_description = htmlspecialchars($event['description'] ?? 'Description non disponible', ENT_QUOTES, 'UTF-8');
                                $event_category = htmlspecialchars($event['categorie'] ?? 'Catégorie non disponible', ENT_QUOTES, 'UTF-8');
                                $event_startDate = htmlspecialchars($event['date_start'] ?? 'Date de début non disponible', ENT_QUOTES, 'UTF-8');
                                $event_endDate = htmlspecialchars($event['date_end'] ?? 'Date de fin non disponible', ENT_QUOTES, 'UTF-8');
                                $event_startTime = htmlspecialchars($event['time_start'] ?? 'Horaires de début non disponible', ENT_QUOTES, 'UTF-8');
                                $event_endTime = htmlspecialchars($event['time_end'] ?? 'Horaires de fin non disponible', ENT_QUOTES, 'UTF-8');
                                $event_postalCode = htmlspecialchars($event['zip_code'] ?? 'Code postale de l\'evenement non disponible', ENT_QUOTES, 'UTF-8');
                                $event_city = htmlspecialchars($event['city'] ?? 'Ville non disponible', ENT_QUOTES, 'UTF-8');
                                $event_country = htmlspecialchars($event['country'] ?? 'Pays non disponible', ENT_QUOTES, 'UTF-8');
                                $event_capacity = htmlspecialchars($event['capacity'] ?? 'Capacité maximale non disponible', ENT_QUOTES, 'UTF-8');
                                $event_price = htmlspecialchars($event['price'] ?? 'Tarifs billet non disponible', ENT_QUOTES, 'UTF-8');
                                $event_organizer = htmlspecialchars(($event['firstName'] ?? '') . ' ' . ($event['lastName'] ?? ''), ENT_QUOTES, 'UTF-8');

                                // Construct detail page URL
                                $event_image = htmlspecialchars($event['photo'] ?? 'Image non disponible', ENT_QUOTES, 'UTF-8');
                                $image_url = BASE_URL . '/assets/images/' . htmlspecialchars($event['photo'], ENT_QUOTES, 'UTF-8'); // Ajout for default image

                                $image_full_path = $_SERVER['DOCUMENT_ROOT'] . '/Yoopla/assets/images/' . $event_image;

                                if (!file_exists($image_full_path) || empty($event_image)) {
                                    $image_url_default = BASE_URL . '/assets/images/default-img/default_event.jpg'; // Path to your default image
                                }

                        ?>
                                <tr>
                                    <td><?= $event_id  ?></td>
                                    <td><?= $event_organizer ?></td>
                                    <!-- <td>
                                        <img src="<?php
                                                    // if (file_exists($image_full_path) && !empty($image_event)) {
                                                    //echo $event_image;
                                                    // } else {
                                                    //     echo $image_url_default;
                                                    // }
                                                    ?>" class=" img-fluid" style="height:5rem; width:100%; object-fit: cover;" alt="image evenement">
                                    </td> -->
                                    <td><?= $event_description ?></td>
                                    <td><?= $event_title ?></td>
                                    <td><?= $event_category ?></td>
                                    <td><?= $event_startDate ?></td>
                                    <td><?= $event_endDate ?></td>
                                    <td><?= $event_startTime ?></td>
                                    <td><?= $event_endTime ?></td>
                                    <td><?= $event_postalCode ?></td>
                                    <td><?= $event_city ?></td>
                                    <td><?= $event_country ?></td>
                                    <td><?= $event_capacity ?></td>
                                    <td><?= $event_price ?></td>

                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#">Editer</a></li>
                                                <li>
                                                    <a class="dropdown-item" href="#">Supprimer</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#">Voir</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>

                    </tbody>
                </table>
            </div>
            <!-- event table end -->
        </main>
    </div>
    <footer class="container d-flex justify-content-around py-3 border-top">
        <a class="navbar-brand" href="#"><img src="<?= BASE_URL . './assets/images/logo/logo.svg'; ?>" class="w-50" alt="Yoopla logo"></a>
        <p class="mb-3 mb-md-0 text-body-secondary w-100">© Admin Dashboard Yoopla - 2025 Islem FOURATI, Inc</p>
    </footer>
    <!-- Avant la fermeture de BODY : Bootstrap JS + Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <!-- Bootstrap popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous"></script>
    <!-- Bootstrap core JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

    <!-- lottie script -->
    <script
        src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs"
        type="module"></script>

    <!-- fast bootstrap link start -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- fast bootstrap link end -->

</body>

</html>