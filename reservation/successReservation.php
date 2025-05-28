<?php
require_once '../inc/init.inc.php';
require_once '../inc/functions.inc.php';


//------------------- Affichage de la réservation -------------------

if (!isset($_SESSION['user'])) { // si une session n'existe pas avec un identifiant user je me redirige vers la page login.php
    header("location:login.php");
}

//initialisation des variables
$reservation = null;
$event = null;
$info = '';

//check if the the reservation id is in the url
if (isset($_GET['ID_reservations']) && is_numeric($_GET['ID_reservations'])) {

    $idReservation = intval($_GET['ID_reservations']);

    $reservation = getReservationViaId($idReservation);


    //if reservation is found, fetch the corresponding event details
    if ($reservation && isset($reservation['ID_Event'])) {
        $idEvent = (int)$reservation['ID_Event'];
        $event = showEventViaId($idEvent); // Fetch event details using the ID from the reservation

        //récupération de la liste des participants de l'événement 
        $participantsIds = getReservationsByEventId($idEvent);
        $participants = [];
        // debug($participants);
        if (!empty($participantsIds)) {
            $participants = getUsersByIds($participantsIds);
        }


        //check if the logged in user is the owner of the event
        if (!isset($_SESSION['user']['ID_User']) || $reservation['ID_User'] != $_SESSION['user']['ID_User']) {
            $info = alert("Vous n'avez pas l'autorisation de voir cette réservation.", "danger");
            $reservation = null; // Unset reservation data if user is not authorized
            $event = null;       // Unset event data
        }
    } else {
        $info = alert("Réservation non trouvée.", "danger");
    }
} else {
    $info = alert("Réservation non trouvée.", "danger");
}



//---------------- Delete reservation via id --------------------

if (isset($_GET) && isset($_GET['action']) && isset($_GET['ID_reservations']) && !empty($_GET['action']) && !empty($_GET['ID_reservations']) && $_GET['action'] == 'delete') {
    $idReservation = htmlentities($_GET['ID_reservations']);
    if (is_numeric($idReservation)) {
        deleteReservation($idReservation);
        header('location:../home.php');
        $_SESSION['message'] = alert("La reservation a bien été supprimé.", "success");
    } else {
        $info = alert("Une erreur s'est produite lors de la suppression de la reservation.", "danger");
    }
}
//---------------- End Delete reservation via id --------------------

$image_event = BASE_URL . '/assets/images/' . $event['photo'];

if (! str_contains($event['photo'], 'event_')) {

    $image_event = BASE_URL . '/assets/images/default-img/default_event.png';
}


require_once '../inc/header.inc.php';
?>

<section class="container-fluid text-center">
    <div class="container justify-content-center mt-5">
        <?= $info ?>
        <h1 class="text-center mb-4 fw-medium">Vous êtes inscrit !</h1>
        <?php
        if ($reservation && $event) :
            // Display reservation and event details
        ?>
            <div class="row d-flex justify-content-around align-items-center mb-4 p-3 bg-secondary-yoopla rounded-4">
                <div class="position-relative col-sm-12 col-md-6 col-lg-5"><img src="<?= $image_event ?>" class="rounded-4 object-fit-cover" style="width: 30rem;" id="image-reservation">
                    <!-- Afficher le bouton "Voir l'évenement" avec du js -->
                    <div class="d-flex align-items-start d-flex justify-content-center align-items-center rounded-bottom-4 position-absolute bottom-0 w-100 d-none" style="height: 7rem;" id="gradientBgGrey">
                        <a href="<?= BASE_URL . '/event/showEvent.php?ID_Event=' . $event['ID_Event']; ?>" class="text-decoration-none text-white d-inline-flex align-items-center"><i class="bi bi-eye-fill"></i>
                            <p class="text-white fw-regular m-2 fs-6">Voir l'évenement</p>
                        </a>
                    </div>
                </div>
                <div class="align-items-start ms-3 me-3 p-3 text-start col-sm-12 col-md-6 col-lg-5">
                    <h5 class="fw-medium mb-5 fs-4"><?= htmlspecialchars($event['title'] ?? 'Titre non disponible', ENT_QUOTES, 'UTF-8'); ?></h5>
                    <p class="fw-bold"><i class="mx-1 bi bi-check2-circle"></i>Status de la réservation:<span class="text-white fw-medium p-2 px-3 fs-6 rounded-pill mx-2  badge bg-<?= $reservation['status'] == 'accepted' ? 'success' : 'warning'; ?>"><?= htmlspecialchars(ucfirst($reservation['status'] ?? 'Aucun statut'), ENT_QUOTES, 'UTF-8'); ?></span> </p>

                    <p><strong>Numéro de la réservation : </strong><?= $reservation['ID_reservations']; ?></p>

                    <p><i class="mx-1 bi bi-calendar2-check"></i><strong class="px-1">Date de réservation:</strong> <?= date('d/m/Y H:i', strtotime($reservation['date_reservation'] ?? '')); ?></p>
                    <p><i class="mx-1 bi bi-geo"></i><strong>Lieu </strong>: <?= htmlspecialchars($event['city'] ?? '', ENT_QUOTES, 'UTF-8'); ?>, <?= htmlspecialchars($event['zip_code'] ?? '', ENT_QUOTES, 'UTF-8'); ?> </p>
                    <!-- debut Modal de modification de l'inscription -->
                    <!-- Button trigger modal -->
                    <button type="button" class="btn yoopla-secondary border-0 m-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Modifier l'inscription
                    </button>
                    <h5 class="fw-medium mb-3 fs-5">Participants</h5>

                    <div class="avatar-stack m-4">
                        <?php
                        $photo_default = BASE_URL . 'assets/images/default-img/default_avatar.jpg';

                        if (!empty($participants) && is_array($participants)) {
                            $maxVisible = 4;
                            $extra = count($participants) - $maxVisible;

                            if ($extra > 0) {
                                echo '<span class="avatar z-index-1">+' . $extra . '</span>';
                            }

                            foreach (array_slice($participants, 0, $maxVisible) as $i => $p) {
                                $photo = !empty($p['photo_profil'])
                                    ? BASE_URL . 'assets/images/profils/' . ($p['photo_profil'])
                                    : $photo_default;

                                $title = $p['firstName'] . ' ' . $p['lastName'] . ($i + 1);

                                echo '<img class="avatar" style="height:3rem; width:3rem" src="' . $photo . '" alt="avatar ' . ($p['firstName']) . '" title="' . $title . '" />';
                            }

                            //  ($p['firstName'])
                        }
                        // else {
                        //     echo '<img class="avatar" style="height:3rem; width:3rem" src="' . $photo_default . '" alt="Aucun participant" />';
                        // }
                        ?>
                    </div>


                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modifier l'inscription</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Voulez-vous vraiment annuler votre inscription ?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary rounded-4" data-bs-dismiss="modal">Fermer</button>
                                    <a href="<?= BASE_URL ?>reservation/successReservation.php?action=delete&ID_reservations=<?= $reservation['ID_reservations'] ?>" type="button" class="btn border-danger-subtle text-danger fw-medium rounded-4 text-decoration-none">Annuler l'inscription</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- fin modal -->
                    <div class="d-flex mt-3 justify-content-between align-items-center">
                        <button class="btn btn-small text-yoopla-blue fw-medium disabled"> Ajouter au calendrier<i class="mx-1 bi bi-calendar-plus"></i></button>
                        <button class="btn btn-small text-yoopla-blue fw-medium disabled">Partager <i class="mx-1 bi bi-share"></i></button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <a href="<?= BASE_URL ?>home.php" class="btn btn-yoopla-secondary-outlined rounded-5 py-3 px-5 shadow m-3 text-decoration-none text-white ">Retour à l'accueil</a>
</section>

<?php
require_once '../inc/footer.inc.php';
