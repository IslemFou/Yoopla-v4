<?php
require_once 'inc/init.inc.php';
require_once 'inc/functions.inc.php';


//-------------------------- User Index --------------------------

$allEvents = getAllEvents();
// debug($_SESSION['user']); 

require_once 'inc/header.inc.php';
?>
<!-- vidéo de fond -->
<section class="video-header container-fluid">
  <video autoplay muted loop class="bg-video">
    <source src="<?= BASE_URL . '/assets/media/banniereYoopla.mp4' ?>" type="video/mp4"> Your browser does not support HTML5 video.
  </video>
  <div
    class="w-100 header-content d-flex flex-column col-md-12 justify-content-center align-items-center">
    <h2 class="display-4 text-capitalize lh-base">Réservez, Organisez, Kiffez...</h2>
    </span>
    <span class="scroll-down" onclick="scrollToSection('scrollEvent')">


      <svg width="70px" height="70px" viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="0.00024000000000000003">
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.048"></g>
        <g id="SVGRepo_iconCarrier">
          <g id="style=doutone">
            <g id="arrow-short-down">
              <path id="vector (Stroke)" fill-rule="evenodd" clip-rule="evenodd" d="M4.47455 8.21481C4.77525 7.92994 5.24995 7.94277 5.53482 8.24347L11.0829 14.0998C11.576 14.6202 12.4048 14.6202 12.8978 14.0998L18.4459 8.24347C18.7308 7.94277 19.2055 7.92994 19.5062 8.21482C19.8069 8.49969 19.8197 8.97439 19.5348 9.27509L13.9867 15.1314C12.9021 16.2763 11.0787 16.2763 9.99399 15.1314L4.44589 9.27509C4.16102 8.97439 4.17385 8.49969 4.47455 8.21481Z" fill="#EA6060"></path>
              <path id="vector (Stroke)_2" fill-rule="evenodd" clip-rule="evenodd" d="M19.5062 8.21481C19.8069 8.49969 19.8197 8.97439 19.5348 9.27509L13.9867 15.1314C13.4446 15.7037 12.716 15.9901 11.9904 15.9901C11.5761 15.9901 11.2404 15.6543 11.2404 15.2401C11.2404 14.8259 11.5761 14.4901 11.9904 14.4901C12.3222 14.4901 12.6511 14.3602 12.8978 14.0998L18.4459 8.24347C18.7308 7.94277 19.2055 7.92994 19.5062 8.21481Z" fill="#5A61FF"></path>
            </g>
          </g>
        </g>
      </svg>
    </span>
    <!-- end span scroll -->
  </div>
</section>
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
<section class="container events" id="scrollEvent">
  <div>
    <h5 class="text-yoopla-red fw-medium">Evènements en cours</h5>
    <h3>Nos incontournables</h3>
  </div>

  <!-- Début carrousel  -->
  <div id="yooplaCarousel" class="carousel slide">
    <div class="carousel-inner p-4">
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
                $event_description = ucfirst(strtolower(substr($event_description_raw, 0, 90) . (strlen($event_description_raw) > 90 ? '...' : '')));
                $event_city = $event['city'] ?? 'Ville inconnue';
                $event_zip = $event['zip_code'] ?? '';


                $detail_url = BASE_URL . 'event/showEvent.php?ID_Event=' . $event_id;


                $event_categorie = $event['categorie'] ?? 'Categorie inconnue';

                //insertion de l'url à l'image de l'event
                $image_event = BASE_URL . 'assets/images/' . $event['photo'];

                if (! str_contains($event['photo'], 'event_')) {

                  $image_event = BASE_URL . '/assets/images/default-img/default_event.png';
                }

              ?>
                <!-- cards -->
                <div class="col-sm-12 col-md-4 col-lg-4 card rounded-4 shadow mx-2 p-0">
                  <img src="
                  <?php
                  echo $image_event;
                  ?>
                   " class="card-img-top rounded-top-4 img-fluid" style="height:18rem; 
                   object-fit: cover;" alt="<?= $event_title ?>">
                  <div class="card-body">
                    <div class="mx-2 d-flex justify-content-between">

                      <p class="small text-muted mb-1 text-light"><i class="bi bi-geo"></i><?= $event_city ?><?= !empty($event_zip) ? ' ' . $event_zip : '' ?></p>
                      <span class="badge mb-2 text-yoopla-blue rounded-pill p-2 fw-medium border align-self-start"><?= $event_categorie ?></span>

                    </div>

                    <h5 class="card-title"><?= $event_title ?></h5>
                    <p class="card-text text-description-carrousel small flex-grow-1"><?= $event_description ?></p>
                    <div class="mt-auto text-center">
                      <!-- bouton voir plus -->
                      <a href="<?= $detail_url ?>" class="btn yoopla-primary fw-medium rounded-5 px-4 py-2 shadow mb-3">Voir plus
                      </a>
                    </div>
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

    </div>

    <!-- end carousel inner -->

    <?php if (count($allEvents) > 3) : ?>

      <button class="carousel-control-prev" type="button" data-bs-target="#yooplaCarousel" data-bs-slide="prev">
        <span class="yoopla-primary rounded rounded-4  carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#yooplaCarousel" data-bs-slide="next">
        <span class="yoopla-primary rounded  rounded-4 carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    <?php endif; ?>
  </div>
  <!-- fin carousel -->

  <div class="container d-flex justify-content-center m-4">
    <a href="showAllEvents.php" class="btn btn-yoopla-secondary-outlined rounded-5 px-7 py-3 fw-medium shadow-sm icon-link icon-link-hover position-relative">Afficher plus d'activités <i class="bi bi-arrow-right mx-2 position-absolute top-50 end-0 translate-middle-y"></i></a>
  </div>
</section>


<?php

require_once 'inc/footer.inc.php';
