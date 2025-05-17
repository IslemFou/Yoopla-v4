<?php
require_once '../inc/init.inc.php';
require_once '../inc/functions.inc.php';
$info = "";
$hiddenClass = '';
$photo_participants = BASE_URL . 'assets/images/default-img/default_avatar.jpg';


if (empty($_SESSION['user'])) {

    header("location:" . BASE_URL . "login.php");
}


if (!isset($_SESSION['user'])) { // si une session existe avec un identifiant user je me redirige vers la page home.php
    header("location:login.php");
}
//-----------------Show event via id

if (isset($_GET) && isset($_GET['ID_Event']) && !empty($_GET['ID_Event'])) {

    $idEvent = htmlentities($_GET['ID_Event']);

    if (is_numeric($idEvent)) {


        $event = showEventViaId($_GET['ID_Event']);
        $id_user_event = $event['ID_User'];


        if (!$event) {

            header('location:index.php');
        }
    } else {

        header('location:index.php');
    }
}
//-----------------End show event via id


//---------------- Delete event via id --------------------

if (isset($_GET) && isset($_GET['action']) && isset($_GET['ID_Event']) && !empty($_GET['action']) && !empty($_GET['ID_Event']) && $_GET['action'] == 'delete') {
    $idEvent = htmlentities($_GET['ID_Event']);

    if (is_numeric($idEvent)) {
        deleteEvent($idEvent);
        header('location:../home.php');
        $_SESSION['message'] = alert("L'événement a bien été supprimé.", "success");
    } else {
        $info = alert("Une erreur s'est produite lors de la suppression de l'événement.", "danger");
    }
}
//---------------- End Delete event via id --------------------

//------- add reservation -------

if (isset($_POST['ID_Event'], $_POST['ID_User']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $idEvent = (int)htmlentities($_GET['ID_Event']);
    $id_user = (int)$_SESSION['user']['ID_User'];
    $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');

    //verification si l'utilisateur est le proprietaire de l'evenement
    if ($id_user  == $event['ID_User']) {
        header('location:index.php');
    } else {
        $result = addReservation($id_user, $idEvent, 'accepted', $message);
        if ($result) {
            redirect(BASE_URL . "reservation/successReservation.php?ID_reservations=" . $result);
        } else {
            redirect(BASE_URL . "event/showEvent.php?ID_Event=" . $idEvent . "&error=reservation_failed");
            $info = alert("Erreur lors de la réservation.", "danger");
        }
    }
}
//------- end add reservation -------

$event_city = $event['city'] ?? 'Ville inconnue';
$event_zip = $event['zip_code'] ?? 'zip_code inconnu';
$event_price = $event['price'] ?? 0.00;
$formatted_price = ($event_price > 0) ? number_format($event_price, 2, ',', ' ') . ' €' : 'GRATUIT';
/*
$event_price: The number to be formatted.
2: Specifies that the number should be formatted to two decimal places.
,: Specifies that a comma (,) should be used as the decimal point separator.
' ': Specifies that a space () should be used as the thousands separator.
For example, if $event_price is 1250.75, number_format would turn it into "1 250,75". If it's 50, it becomes "50,00".
*/
// Construct detail page URL
$detail_url = BASE_URL . 'event/showEvent.php?ID_Event=' . $idEvent;

$image_event = BASE_URL . '/assets/images/' . $event['photo'];

if (! str_contains($event['photo'], 'event_')) {

    $image_event = BASE_URL . '/assets/images/default-img/default_event.png';
}

// la récupération des participants

$idEvent = intval($_GET['ID_Event']);
$userIds = getReservationsByEventId($idEvent);
$users = [];

if (!empty($userIds)) {
    $users = getUsersByIds($userIds);
}
// $users contient maintenant un tableau des utilisateurs qui ont réservé


// récupération de l'utilisateur créateur de l'event






////

require_once '../inc/header.inc.php';
?>
<section class="container position-relative">
    <?= $info ?>
    <div class="container d-flex justify-content-around m-3">
        <h1 class="text-center p-2 fw-bold m-2"><?= $event['title'] ?? 'Titre non disponible'; ?></h1>
        <p class="text-center fw-bold m-2 p-2 text-yoopla-blue tracking-wider lh-lg fs-4"><?= $formatted_price ?></p>
    </div>
    <div class="container">
        <img src="<?php
                    echo $image_event
                    ?>" class="card-img-top rounded-4" style="height:25rem; object-fit: cover;" alt="image evenement" title="image evenement">
    </div>
    <div>
        <h5 class="fw-medium m-2">Organisateur</h5>
        <div class="d-flex align-items-center justify-content-start">

            <?php
            // debug($_SESSION['user']);
            $photo_profil_default = BASE_URL . 'assets/images/default-img/default_avatar.jpg';

            $urlfile = BASE_URL . 'assets/images/profils/';

            // debug(file_exists($urlfile . $_SESSION['user']['photo_profil']));

            // if (isset($_SESSION['user']['photo_profil']) && file_exists($urlfile . $_SESSION['user']['photo_profil'])) {
            //     $photo_profil = BASE_URL . '../assets/images/profils/' . $_SESSION['user']['photo_profil'];
            //     // debug($photo_profil);
            //     // $photo_profil ?? BASE_URL . 'assets/images/default-img/default_avatar.jpg';
            // }

            if (isset($id_user_event) && $event['photo_profil'] != '') {
                $photo_profil_org = BASE_URL . 'assets/images/profils/' . $event['photo_profil'];
            }

            ?>
            <img src="<?= $photo_profil_org ?? $photo_profil_default ?>" class="rounded-circle" width="50" height="50" alt="image de profil par défaut" title="Organisateur de l'evenement">
            <p class="fw-bold m-3"><?= ($event['firstName'] ?? '') . ' ' . ($event['lastName'] ?? ''); ?></p>
        </div>
        <div class="d-flex align-items-center flex-wrap justify-content-evenly">
            <p><i class="bi bi-geo"></i> <?= $event_city ?>, <?= $event_zip ?></p>
            <div class="d-flex justify-content-between align-items-center">
                <p class="text-light-dark"><i class="bi bi-clock"></i><?= date('H:i', strtotime($event['time_start'])) . ' - ' . date('H:i', strtotime($event['time_end'])); ?>
                </p>
                <p>
                <p class="text-light-dark ms-3"><i class="bi bi-calendar-event mx-2"></i> <?= date('d-m-Y', strtotime($event['date_start'])) ?></p>
            </div>
            <p><i class="bi bi-people"></i> Capacité: <?= $event['capacity'] ?> personnes</p>
            <div class="fs-6 btn rounded-5 px-4 fw-medium shadow-sm yoopla-secondary mb-3"><?= $event['categorie'] ?? 'Categorie inconnue' ?></div>
        </div>
        <!-- Participants -->
        <div>
            <h5 class="fw-medium m-2">Participants</h5>
            <!-- Affichage des avatars des participants -->

            <div class="avatar-stack m-4">
                <?php
                $photo_default = BASE_URL . 'assets/images/default-img/default_avatar.jpg';

                if (!empty($users) && is_array($users)) {

                    $maxVisible = 4; // Nombre max d'avatars visibles
                    $countUsers = count($users);
                    $extra = $countUsers - $maxVisible;

                    if ($extra > 0) {
                        echo '<span class="avatar z-index-1">+' . $extra . '</span>';
                    }

                    foreach (array_slice($users, 0, $maxVisible) as $index => $user) {
                        $photo = !empty($user['photo_profil'])
                            ? BASE_URL . 'assets/images/profils/' . htmlspecialchars($user['photo_profil'])
                            : $photo_default;

                        $title = $user['firstName'] . ' ' . $user['lastName'] . ($index + 1);

                        echo '<img class="avatar" style="height:3rem; width:3rem" src="' . $photo . '" title="' . $title . '" alt="image_avatar" />';
                    }
                } else {
                    // Si pas de participants
                    // ou on affiche un message indiquant qu'il n'y a pas de participants
                    echo '<p>Aucun participant pour l\'instant</p>';
                }
                ?>
            </div>
        </div>
        <div class="m-3 container bg-discovery-subtle rounded-4 p-3">
            <h6>Description</h6>
            <p><?= $event['description'] ?? 'Pas de description.'; ?></p>
        </div>
        <!-- Bouton de réservation -->
        <div class="d-flex justify-content-center">

            <!------------------------ Debut modal --------------------------------------------- -->
            <!-- Button trigger modal -->
            <?php
            if (isset($_SESSION['user']) && $_SESSION['user']['ID_User'] != $id_user_event) {

                if (reservationExists($_SESSION['user']['ID_User'], $event['ID_Event'])) {
                    $reservation = reservationExists($_SESSION['user']['ID_User'], $event['ID_Event']); //true 

                    $info = alert("Vous avez deja une reservation pour cet evenement", "warning");
            ?>
                    <!-- Bouton déja réservé -->
                    <div class="btn btn-success fw-medium rounded-5 px-4 py-2 shadow m-3 text-decoration-none btn-lg ">Déja réservé <i class="mt-0 bi bi-check text-light"></i></div>

                    <!-- update reservation message ou voir la reservation -->
                <?php } else { ?>

                    <!-- Bouton de reservation -->
                    <div class="btn btn-yoopla-primary fw-medium rounded-5 px-4 py-2 shadow m-3" data-bs-toggle="modal" data-bs-target="#exampleModal">Reserver</div>
                <?php } ?>



            <?php } elseif (isset($_SESSION['user']) && $_SESSION['user']['ID_User'] == $id_user_event) { ?>
                <!-- Bouton de suppression -->
                <div><a href=" <?= BASE_URL ?>event/showEvent.php?action=delete&ID_Event=<?= $event['ID_Event'] ?>" class="btn btn-yoopla-secondary-outlined fw-medium rounded-5 px-4 py-2 shadow m-3 text-decoration-none text-light">Supprimer</a></div>
                <!-- Bouton de modification -->
                <div><a href=" <?= BASE_URL ?>event/addEvent.php?action=update&ID_Event=<?= $event['ID_Event'] ?>" class="btn btn-yoopla-primary fw-medium rounded-5 px-4 py-2 shadow m-3 text-decoration-none text-light">Modifier l'evenement </a></div>


            <?php } else { ?>

                <div><a href=" login.php" class="btn btn-yoopla-primary fw-medium rounded-5 px-4 py-2 shadow m-3 text-decoration-none text-light">Se connecter</a></div>
            <?php } ?>


            <!-- Modal: affichage de la modale --------------------------------------------------------------->

            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5 mx-auto text-center" id="exampleModalLabel">Terminer la réservation</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-regular mx-auto text-center"><i class="bi bi-envelope-check text-yoopla-red fs-5"></i> Votre adresse e-mail sera partagé avec l’organisateur de l’évènement.</p>

                            <!------------ Envoi de la réservation ( dans le cadre d'une demande de reservation ) ---------------------------------------------------------------->

                            <form action="#" method="POST">
                                <input type="text" name="ID_Event" value="<?= $event['ID_Event'] ?>" hidden>
                                <input type="text" name="ID_User" value="<?= $_SESSION['user']['ID_User'] ?>" hidden>
                                <input type="text" name="status" value="accepted" hidden>
                                <label for="message" class="form-label mb-3">Ajouter un message de reservation</label>
                                <textarea name="message" id="message" cols="30" rows="3" placeholder="Une petite remarque ?" class="form-control"></textarea>
                                <div class="modal-footer d-flex justify-content-center">
                                    <button type="submit" name="submit" class="mx-center btn btn-yoopla-primary fw-regular rounded-5 px-5 py-2 shadow m-3 mx-auto">Confirmer</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <!------------------------ fin modal --------------------------------------------------->


        </div>
    </div>
</section>



<?php
/*
Au niveau de cette partie, on cache la bande de reservation si l'utilisateur connecté est l'organisateur de l'evenement lorsque l'utilisateur connecté est lui meme l'organisateur de l'evenement.

De ce fait, il n'y a pas de bouton de reservation et la bande de reservation est cachee.

*/
if (isset($_POST['ID_Event'], $_POST['ID_User'])) {

    $id_user = (int)$_SESSION['user']['ID_User'];

    if ($id_user  == $event['ID_User']) {
        $hiddenClass = 'd-none';

?>
        <!-- début Bande de réservation -->
        <div class="d-flex align-items-center justify-content-between border border-yoopla-primary rounded-top-4 p-3 position-sticky shadow bottom-0 bg-white <?= $hiddenClass ?>">
            <div>
                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-light-dark"><i class="bi bi-clock"></i><?= date('H:i', strtotime($event['Time_start'])) . ' - ' . date('H:i', strtotime($event['Time_end'])); ?>
                    </p>
                    <p>
                    <p class="text-light-dark"><i class="bi bi-calendar-event mx-2"></i> <?= $event['Date_start'] ?></p>
                </div>
                <h5><?= htmlspecialchars($event['Title'] ?? 'Titre non disponible', ENT_QUOTES, 'UTF-8'); ?></h5>
            </div>
            <form action="" method="POST" class="d-flex justify-content-center">
                <p class="text-center fw-bold m-3 p-2 text-yoopla-blue tracking-wider lh-lg"><?= $formatted_price ?></p>
                <button class="btn btn-yoopla-secondary-outlined fw-medium rounded-5 px-4 py-2 shadow m-3">Enregistrer<i class="m-1 bi bi-heart"></i></button>
                <!-- Bouton de réservation -->

                <!-- modal -->
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-yoopla-primary fw-medium rounded-5 px-4 py-2 shadow m-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Réserver
                </button>
                <!-- Modal: affichage de la modale -->
            </form>
        </div>
        <!-- Fin bande de réservation -->

<?php

    }
}
?>


<?php

require_once '../inc/footer.inc.php';
