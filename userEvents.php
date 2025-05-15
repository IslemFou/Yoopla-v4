<?php
require_once 'inc/init.inc.php';
require_once 'inc/functions.inc.php';
$info = "";
$title = "Mes événements";


$userEvents = showUserEvents($_SESSION['user']['ID_User']);
// debug($userEvents);

if (!isset($_SESSION['user'])) { // si une session n'existe pas avec un identifiant user je me redirige vers la page login.php

  redirect(BASE_URL . 'authentication/login.php');
}

$allEvents = $userEvents;


require_once 'inc/header.inc.php';
?>
<section>
  <?php
  echo $info;
  ?>
  <h1 class="text-center p-2 fw-bold m-5"><?php echo $title; ?></h1>
  <div class="container mx-auto">
    <!-- carte -->
    <?php

    if (empty($allEvents)) {
      $info = alert("Aucun événement trouvé", "warning");
    } else {
      foreach ($allEvents as $event) :
        // --- Data Preparation & Sanitization ---
        // Sanitize output to prevent XSS attacks
        $event_id = (int) $event['ID_Event']; // Ensure ID is integer
        $event_title = $event['title'] ?? 'Titre non disponible';
        $event_description = substr($event['description'] ?? 'Pas de description.', 0, 90) . '...'; // Limit description to 100 characters and add ellipsis (...) if truncated. htmlspecialchars($event['Description'] ?? 'Pas de description.', ENT_QUOTES, 'UTF-8');
        $event_city = $event['city'] ?? 'Ville inconnue';
        $event_zip = $event['zip_code'] ?? 'zip_code inconnu';
        $organizer_name = ($event['firstName'] ?? 'Organizateur inconnu') . ' ' . ($event['lastName'] ?? '');
        $event_categorie = $event['categorie'] ?? 'Categorie inconnue';
        $detail_url = BASE_URL . 'event/showEvent.php?ID_Event=' . $event_id;

        $image_event = BASE_URL . '/assets/images/' . $event['photo'];

        if (! str_contains($event['photo'], 'event_')) {

          $image_event = BASE_URL . '/assets/images/default-img/default_event.jpg';
        }
    ?>
        <!-- horzontal card -->
        <div class="card card-userEvents flex-row overflow-hidden rounded-4 shadow m-1 mb-5 p-0">
          <div class="d-flex col-md-4" style="width: 20%; height: 100%;">
            <img src="<?php
                      echo $image_event;
                      ?>" alt="image evenement" class="img-fluid w-100 h-100" style="object-fit: cover;">
          </div>
          <div class="w-100 p-3 mb-3">

            <div class="d-flex justify-content-start align-items-center position-relative">
              <p><i class="bi bi-geo"></i> <?= $event_city ?></p>
              <span class="badge mb-3 ms-3 text-yoopla-blue rounded-pill p-2 fw-medium border"><?= $event_categorie ?></span>

              <!-- Edit button -->

              <a class=" btn border-0 position-absolute top-0 end-0 translate-middle-y btn-yoopla-primary mt-3" href="<?php echo BASE_URL . 'event/showEvent.php?ID_Event=' . $event_id; ?>" role="button" id="edit">
                <i class="bi bi-pencil-square"></i></i>
              </a>

            </div>
            <h5 class="card-title"><?= $event_title ?></h5>
            <p class="card-text"><?= $event_description ?></p>
          </div>
        </div>
        <!-- end horzontal card -->
    <?php
      endforeach;
    }
    ?>
  </div>
</section>

<?php

require_once 'inc/footer.inc.php';
