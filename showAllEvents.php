<?php
require_once 'inc/init.inc.php';
require_once 'inc/functions.inc.php';
$title = "Mes activités";


if (!isset($_SESSION) && !isset($_SESSION['user']) && empty($_SESSION)) { // si une session existe avec un identifiant user je me redirige vers la page home.php
    header("location:authentication/login.php");
}


$allEvents = getAllEvents();

//--------- Tous les events -----------
//affichage de tous les events de la base de données, affichage limité


require_once 'inc/header.inc.php';
?>
<section class="container">
    <h1 class="display-6 p-4 m-4">Tous les Evènements</h1>
    <!-- debug(count(getLimitedEvents())); -->
    <div class="d-flex justify-content-around flex-wrap container align-items-stretch h-100">
        <?php
        // $limitedEvents
        if (empty($allEvents)) {
            $info = alert("Aucun événement trouvé", "warning");
        } else {
            foreach ($allEvents as $event) :
                // --- Data Preparation & Sanitization ---
                // Sanitize output to prevent XSS attacks

                $event_id = (int) $event['ID_Event']; // Ensure ID is integer
                $event_title = $event['title'] ?? 'Titre non disponible';


                $event_description = ucfirst(strtolower(substr($event['description'] ?? 'Pas de description.', 0, 90) . '...'));

                // Limit description to 100 characters and add ellipsis (...) if truncated. $event['Description'] ?? 'Pas de description;
                $event_city = $event['city'] ?? 'Ville inconnue';
                $event_zip = $event['zip_code'] ?? '';
                $organizer_name = ($event['firstName'] ?? '') . ' ' . ($event['lastName'] ?? '');
                $event_categorie = $event['categorie'] ?? 'Categorie inconnue';
                $event_date_start = $event['date_start'] ?? 'Date inconnue';


                $image_event = BASE_URL . '/assets/images/' . $event['photo'];

                if (! str_contains($event['photo'], 'event_')) {

                    $image_event = BASE_URL . '/assets/images/default-img/default_event.png';
                }

                //bouton vers l'affichage de l'event
                $detail_url = BASE_URL . 'event/showEvent.php?ID_Event=' . $event_id;
        ?>
                <!-- Debut card -->
                <div class="card col-sm-12 col-md-4 col-lg-3 rounded-4 shadow m-2 mb-5 h-auto" style="min-height:40rem;">

                    <img src="<?php echo $image_event ?? $image_url_default;
                                ?>" class="card-img-top rounded-top-4 img-fluid" style="height:25rem; width:100%; object-fit: cover;" alt="image evenement">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="mx-2 d-flex justify-content-between">
                            <p class=" small fs-6 mb-0"><i class="fbi bi-geo"></i> <?= $event_city ?></p>
                            <span class="badge small mb-3 text-yoopla-blue rounded-pill p-2 fw-medium border"><?= $event_categorie ?></span>
                        </div>
                        <h5 class="mb-2 fs-5 card-title"><?= $event_title ?></h5>
                        <p class="">Organisateur: <?= $organizer_name ?></p>
                        <p class="small card-text text-muted"><?= $event_description ?></p>
                        <div class="d-flex justify-content-center">
                            <a href="<?= $detail_url ?>" class="btn yoopla-primary fw-medium rounded-5 px-4 py-2 shadow mb-1">Voir l'activité</a>
                        </div>
                    </div>
                </div>
                <!-- fin card -->
        <?php
            endforeach;
        }
        ?>
    </div>
</section>






<?php

require_once 'inc/footer.inc.php';
?>