<?php
require_once 'inc/init.inc.php';
require_once 'inc/functions.inc.php';


//-------------------------- User Index --------------------------


$allEvents = getAllEvents();

require_once 'inc/header.inc.php';
?>
<!-- Section 1:banniere -->
<!-- <section class="container-fluid m-2" id="sectionBanniere">
  <div class="text-center">
    <h1 class="display-5 p-4 text-center">Découvrez <span class="text-yoopla-red">Les évènements</span> près de <span class="text-yoopla-blue">chez-vous</span> !</h1>
    <p class="fs-6 p-4"><span class="text-yoopla-red fw-bold">Yoopla</span> est un site de reservation des évènements, activités culturelles ou sportive.</p>
  </div>
  <div class="bloc-images mx-2 position-relative">
    <img src="assets/images/logo/cyclism-deco.svg" class="position-absolute bottom-0 start-10 translate-middle-x" style="width: 13rem;" alt="vecteurcyclisme"> 
    <img src="assets/images/art.jpg" class="" alt="image art">
    <img src="assets/images/dance.jpg" class="" alt="image dance">
     <img src="assets/images/logo/basket-deco.svg" class="position-absolute top-0 end-0 translate-middle-x" style="width: 13rem;" alt="vecteurbasket"> 
    <img src="assets/images/girl.jpg" class="" alt="image yoga">
    <img src="assets/images/ballet.jpg" class="" alt="image ballet">
  </div>
</section> -->

<!-- <section class="container-fluid">
  <div class="text-center mb-5 position-relative" id="hero">
    <video autoplay muted loop id="hero-video">
      <source src="<?= BASE_URL . '/assets/media/banniereYoopla.mp4' ?>" type="video/mp4">
    </video>
    <div
      class="position-absolute top-100 start-50 translate-middle d-flex flex-column col-md-12 justify-content-center align-items-center">
      <h2 class="display-4 text-capitalize lh-base">Découvrez les évènements de Yoopla</h2>
      <a href="#" class=" p-1 text-decoration-none border-bottom border-light">Découvrir</a>
    </div>
  </div>
</section> -->

<!-- div buttons  -->
<div class="row gap-3 justify-content-center align-items-center m-5" id="buttonsColor">
  <button class="btn rounded-5 px-4 mx-4 mt-2 fw-medium shadow-sm" style="color: #EA6060;" id="coloredButtons">AfterWork</button>
  <button class="btn rounded-5 px-4 mx-4 mt-2 fw-medium shadow-sm" style="color:rgb(30, 159, 127);" id="coloredButtons">Dance</button>
  <button class="btn rounded-5 px-4 mx-4 mt-2 fw-medium shadow-sm" style="color:rgb(73, 31, 170);" id="coloredButtons">Peinture</button>
  <button class="btn rounded-5 px-4 mx-4 mt-2 fw-medium shadow-sm" style="color:rgb(154, 30, 150);" id="coloredButtons">Bricolage</button>
  <button class="btn rounded-5 px-4 mx-4 mt-2 fw-medium shadow-sm" style="color:rgb(184, 103, 27);" id="coloredButtons">Evénement</button>
  <button class="btn rounded-5 px-4 mx-4 mt-2 fw-medium shadow-sm" style="color:rgb(18, 115, 74);" id="coloredButtons">Yoga</button>
</div>

<!-- Section 3: activités en cours-->
<section class="container events" id="section-activites">
  <h5 class="text-yoopla-red fw-medium">Evènements en cours</h5>
  <h3>Nos incontournables</h3>

  <!-- Début carrousel  -->
  <div id="yooplaCarousel" class="carousel slide">
    <div class="carousel-inner">
      <?php
      if (empty($allEvents)) {
        echo '<div class=""><div class="alert alert-warning text-center">Aucun événement trouvé pour le moment.</div></div>';
      } else {
        //chunk the events array into groups of three
        $eventChunks = array_chunk($allEvents, 3);
        $isFirstSlide = true; // Flag to set the 'active' class on the first item

        foreach ($eventChunks as $chunk) :
          // Determine if this is the active slide
          $activeClass = $isFirstSlide ? 'active' : ''; // Set the 'active' class on the first item
      ?>
          <div class="carousel-item <?= $activeClass ?>">
            <!-- start first slide -->
            <div class=" row g-4 justify-content-center p-3" id="ThreeCardsCarousel">
              <?php
              foreach ($chunk as $event) :
                // --- Data Preparation & Sanitization (Copied from your userEvents.php for consistency) ---
                $event_id = (int) $event['ID_Event'];
                $event_title = $event['title'] ?? 'Titre non disponible';
                // Limit description and sanitize
                $event_description_raw = $event['description'] ?? 'Pas de description.';
                $event_description = substr($event_description_raw, 0, 90) . (strlen($event_description_raw) > 90 ? '...' : '');
                $event_city = $event['city'] ?? 'Ville inconnue';
                $event_zip = $event['zip_code'] ?? '';
                $detail_url = BASE_URL . 'event/showEvent.php?ID_Event=' . $event_id;
                $event_categorie = $event['categorie'] ?? 'Categorie inconnue';

                //insertion de l'url à l'image de l'event
                $image_event = BASE_URL . 'assets/images/' . $event['photo'];

                if (! str_contains($event['photo'], 'event_')) {

                  $image_event = BASE_URL . '/assets/images/default-img/default_event.jpg';
                }

              ?>
                <!-- cards -->
                <div class="col-sm-12 col-md-6 col-lg-4 card rounded-4 shadow mx-2 p-0">
                  <img src="
                  <?php
                  echo $image_event;
                  ?>
                   " class="card-img-top rounded-top-4 img-fluid" style="height:18rem; 
                   object-fit: cover;" alt="<?= $event_title ?>">
                  <div class="card-body">
                    <span class="badge mb-2 text-yoopla-blue rounded-pill p-2 fw-medium border align-self-start"><?= $event_categorie ?></span>
                    <p class="small text-muted mb-1 text-light"><i class="bi bi-geo"></i><?= $event_city ?><?= !empty($event_zip) ? ' ' . $event_zip : '' ?></p>
                    <h5 class="card-title"><?= $event_title ?></h5>
                    <p class="card-text flex-grow-1"><?= $event_description ?></p>
                  </div>
                  <div class="mt-auto text-center">
                    <!-- bouton voir plus -->
                    <a href="<?= $detail_url ?>" class="btn yoopla-primary fw-medium rounded-5 px-4 py-2 shadow mb-3">Voir plus
                    </a>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          <!-- end carousel item -->

      <?php
          $isFirstSlide = false; // Reset the flag for the next iteration
        endforeach;
      }
      ?>

      <!-- start second slide -->
      <div class="carousel-item">
        <div class="d-flex justify-content-around p-3" id="ThreeCardsCarousel">
          <!-- cards -->
        </div>
      </div>
      <!-- end second slide -->

      <!-- start third slide -->
      <div class="carousel-item">
        <div class="d-flex justify-content-around p-3" id="ThreeCardsCarousel">
          <!-- 3 cards -->
        </div>
        <!-- end third slide -->
      </div>
    </div>

    <!-- end carousel inner -->

    <?php if (count($allEvents) > 3) : ?>

      <button class="carousel-control-prev" type="button" data-bs-target="#yooplaCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#yooplaCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    <?php endif; ?>
  </div>
  <!-- fin carousel -->

  <div class="container d-flex justify-content-center m-4">
    <a href="home.php" class="btn btn-yoopla-secondary-outlined rounded-5 px-5 py-3 fw-medium shadow-sm icon-link icon-link-hover position-relative">Afficher plus d'activités <i class="bi bi-arrow-right mx-3 position-absolute top-50 end-0 translate-middle-y"></i></a>
  </div>
</section>


<?php

require_once 'inc/footer.inc.php';
