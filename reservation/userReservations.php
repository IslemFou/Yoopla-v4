<?php
require_once '../inc/init.inc.php';
require_once '../inc/functions.inc.php';
$info = "";
$title = "Liste des reservations";


if (!isset($_SESSION['user'])) { // si une session n'existe pas avec un identifiant user je me redirige vers la page login.php

    redirect(BASE_URL . 'authentication/login.php');
}

$ID_User = $_SESSION['user']['ID_User'];
// debug($ID_User); // int 5
// debug($_SESSION['user']);

$UserReservations = getUserReservations($ID_User);
// debug($UserReservations);

//debug($UserReservations); // array of all reservations of the user


//---------------- Delete reservation via id --------------------

if (isset($_GET['action']) && isset($_GET['ID_reservations']) && !empty($_GET['action']) && !empty($_GET['ID_reservations']) && $_GET['action'] == 'delete') {
    if (is_numeric($_GET['ID_reservations'])) {

        $id_reservation = $_GET['ID_reservations'];

        deleteReservation($id_reservation);

        $info = alert("La reservation a bien été supprimé.", "success");
        redirect(BASE_URL . "reservation/userReservations.php");
    } else {
        $info = alert("Une erreur s'est produite lors de la suppression de la reservation.", "danger");
    }
}
//---------------- End Delete reservation via id --------------------



require_once '../inc/header.inc.php';
?>
<section>
    <h1 class="text-center p-2 m-5 display-6"><?php echo $title; ?> effectuée par <?php echo $_SESSION['user']['firstName']; ?></h1>
    <?php
    echo $info;
    ?>
    <div class="container-fluid mx-auto">
        <?php
        // Affichage du bouton qui envoie vers l'espace admin
        if ($_SESSION['user']['checkAdmin'] == 'admin') : ?>
            <div class="d-flex justify-content-end align-items-center">
                <a href="<?= BASE_URL ?>admin/dashboard.php" class="btn btn-yoopla-secondary-outlined">Espace Admin</a>
            </div>
        <?php endif; ?>
        <!-- Tableau affiche les reservations effectuée par l'utilisateur connecté -->
        <div class="w-100 container-fluid m-1 rounded-3 p-3">
            <!-- Users table start -->
            <div class="table-responsive">
                <table class="table table-hover table-borderless">
                    <thead>
                        <tr>
                            <th>Num de reservation</th>
                            <th>Titre de l'evenement</th>
                            <th>Date de reservation</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <tr>
                            <?php

                            if (empty($UserReservations)) {
                                $info = alert("Aucune reservation effectuée", "info");
                            } else {
                                // $event = showEventViaId((int)$UserReservations['ID_Event']);
                                // debug($event);
                                foreach ($UserReservations as $Ureservation) {
                                    // debug($Ureservation);
                                    $id_reservation = $Ureservation['ID_reservations'];
                                    $date_reservation = $Ureservation['date_reservation'];
                                    $status = $Ureservation['status'];
                                    $ID_Event = $Ureservation['ID_Event'];
                                    // debug($ID_Event);
                                    $event = showEventViaId($ID_Event);
                                    $title = $event['title'];
                                    $image = $event['photo'];
                            ?>
                                    <td><?= $id_reservation ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>event/showEvent.php?ID_Event=<?= $ID_Event ?>" class="text-decoration-none fw-medium text-yoopla-blue" title="voir l'evenement"><?= $title ?></a>
                                    </td>
                                    <td><?= date('d-m-Y', strtotime($date_reservation)) ?></td>
                                    <td><?= $status ?></td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="<?= BASE_URL ?>reservation/userReservations.php?action=delete&ID_reservations=<?= $id_reservation ?>">Annuler ma réservation</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                        </tr>
                <?php
                                }
                            };
                ?>
                    </tbody>
                </table>
            </div>
            <!-- Users table end -->
        </div>
    </div>
</section>











<?php

require_once '../inc/footer.inc.php';
