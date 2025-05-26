<?php
require_once 'inc/init.inc.php';
require_once 'inc/functions.inc.php';

if (!isset($_SESSION['user'])) { // si une session existe avec un identifiant user je me redirige vers la page home.php
	header("location:index.php");
}
$title = "Mes activités";
$allEvents = getAllEvents();
$limitedEvents = getLimitedEvents();
$result = [];

// debug($_SESSION['user']);

//--------- Tous les events -----------
//affichage de tous les events de la base de données, affichage limité


// debug($_GET);


// //------------- formulaire de recherche -----------------


// if (empty($_POST)) {
// 	// $info = alert("Veuillez entrer un terme de recherche.", "danger");
// } 

if (isset($_POST) && !empty($_POST)) {

	$city = htmlspecialchars(trim($_POST['city']));
	$title = htmlspecialchars(trim($_POST['title']));

	if (empty($city) && empty($title)) {
		$info = alert("Veuillez entrer un terme de recherche.", "danger");
	} else if (!empty($city) && empty($title)) {

		$result = searchEvent($city, '');
	} else if (empty($city) && $title == '') {

		$result = searchEvent($city, '');
	} else if (empty($city) && !empty($title)) {

		$result = searchEvent('', $title);
		// debug($result);
	} else {

		$result = searchEvent($city, $title);
	}
};
//--------- fin de formulaire de recherche  -----------




require_once 'inc/header.inc.php';
?>
<!-- vidéo de fond -->
<section class="video-header container-fluid">
	<video autoplay muted loop class="bg-video">
		<source src="<?= BASE_URL . '/assets/media/banniereYoopla.mp4' ?>" type="video/mp4"> Your browser does not support HTML5 video.
	</video>
	<div
		class="header-content d-flex flex-column col-md-12 justify-content-center align-items-center">
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
<!------------------  Première Section: formulaire de recherche ------------------------------------------------->
<section>
	<?php
	echo $info;
	// echo $_SESSION['message'];
	?>
	<h5 class=" fw-medium p-4 text-center mt-5">Chercher des événement par <span>lieu</span> ou <span>événement</span></h5>
	<!-- search bar -->
	<form method="POST" action="" title="Formulaire de recherche">
		<div class="container m-5 rounded-5 shadow p-2 mx-auto d-flex justify-content-around align-items-center w-75">

			<div class="form-floating border-0">
				<input type="search" name="city" class="form-control border-0" id="searchLocation" placeholder="Paris ..." aria-label="Lieu" value="<?php if (isset($_POST['city'])) {
																	echo htmlspecialchars($_POST['city']);
																} ?>">
				<label for="searchLocation"><i class="bi bi-geo me-2"></i>Lieu souhaité</label>
			</div>
			<p class="fw-bold fs-5 text-yoopla-red">Ou</p>

			<div class="form-floating border-0">
				<input type="search" name="title" class="form-control border-0" id="searchEvent" placeholder="Activité, événement ..." aria-label="Événement" value="<?php if (isset($_POST['title'])) {
																	echo htmlspecialchars($_POST['title']);
																} ?>">
				<label for="searchEvent"><i class="bi bi-balloon me-2"></i>Événement souhaité</label>
			</div>

			<div>
				<button type="submit" class="border-0 bg-transparent">
					<i class="bi bi-search-heart text-yoopla-red fs-3"></i>
				</button>
			</div>

		</div>
	</form>
	<!-- Résultat de recherche -->
	<div class="container rounded-3 p-3">
					<?php
				if (isset($_POST) && $result && count($result) > 0) {
					echo "<h5 class=\" fw-medium text-center p-4\">Résultats de recherche :</h5>";
					echo '<div class="d-flex flex-wrap justify-content-center">';
					foreach ($result as $searchResult) :
				?>
							<!-- Debut card -->
						<div class="card col-sm-12 col-md-4 col-lg-3 rounded-4 shadow m-2 mb-5" style="height:40rem;">
		
							<img src="<?php 
							
							if (!str_contains($searchResult['photo'], 'event_')) {
								$image_event = BASE_URL . '/assets/images/default-img/default_event.png';
							} else {
								$image_event = BASE_URL . '/assets/images/' . $searchResult['photo'];
							}
							echo $image_event;
										?>" class="card-img-top rounded-top-4 img-fluid" style="height:25rem; width:100%; object-fit: cover;" alt="image evenement">
							<div class="card-body">
								<div class="mx-2 d-flex justify-content-between">
									<p class=" small fs-6 mb-0"><i class="fbi bi-geo"></i> <?= $searchResult['city'] ?></p>
									<span class="badge small mb-3 text-yoopla-blue rounded-pill p-2 fw-medium border"><?= $searchResult['categorie'] ?></span>
								</div>
								<h5 class="mb-2 fs-6 card-title"><?= $searchResult['title'] ?></h5>
								<!-- <p class="small mb-2 card-text">Organisateur: <?= $searchResult['firstName'] . $searchResult['lastName'] ?></p> -->
								<p class="small card-text text-muted"><?= $searchResult['description'] . '...' ?></p>
							</div>
							<div class="d-flex justify-content-center">
								<a href="<?= BASE_URL . 'event/showEvent.php?ID_Event=' . $searchResult['ID_Event'] ?>" class="btn yoopla-primary fw-medium rounded-5 px-4 py-2 shadow mb-3">Voir l'activité</a>
							</div>
						</div>
						<!-- fin card --> 
					<?php endforeach; ?>
					<?php
					echo '</div>';
			} 
			// else {
			// 		echo "<h5 class=\" fw-medium text-centerp-4\">Aucun résultat trouvé pour votre recherche.</h5>";
			// 	}
				?>
				<!-- fin résultat de recherche -->
		</div>
</section>
<!---------------------------------------- Deuxième section : affichage des cartes d'événements ------------------->
<section class="container" id="scrollEvent">
	<h5 class="text-yoopla-red fw-medium">Evènements en cours</h5>
	<h3>Nos incontournables</h3>
	<div class="d-flex justify-content-around flex-wrap container">
		<?php
		// $limitedEvents
		if (empty($limitedEvents)) {
			$info = alert("Aucun événement trouvé", "warning");
		} else {
			foreach ($limitedEvents as $event) :
				// --- Data Preparation & Sanitization ---
				// Sanitize output to prevent XSS attacks

				$event_id = (int) $event['ID_Event']; // Ensure ID is integer
				$event_title = $event['title'] ?? 'Titre non disponible';
				$event_description = substr($event['description'] ?? 'Pas de description.', 0, 90) . '...'; // Limit description to 100 characters and add ellipsis (...) if truncated. $event['Description'] ?? 'Pas de description;
				$event_city = $event['city'] ?? 'Ville inconnue';
				$event_zip = $event['zip_code'] ?? '';
				$organizer_name = ($event['firstName'] ?? '') . ' ' . ($event['lastName'] ?? '');
				$event_categorie = $event['categorie'] ?? 'Categorie inconnue';
				$event_date_start = $event['date_start'] ?? 'Date inconnue';
				//bouton vers l'affichage de l'event
				$detail_url = BASE_URL . 'event/showEvent.php?ID_Event=' . $event_id;


				$image_event = BASE_URL . '/assets/images/' . $event['photo'];

				if (! str_contains($event['photo'], 'event_')) {

					$image_event = BASE_URL . '/assets/images/default-img/default_event.png';
				}

					?>
				<!-- Debut card -->
				<div class="card col-sm-12 col-md-4 col-lg-3 rounded-4 shadow m-2 mb-5" style="height:41rem;">

					<img src="<?php echo $image_event ?? $image_url_default;
								?>" class="card-img-top rounded-top-4 img-fluid" style="height:25rem; width:100%; object-fit: cover;" alt="image evenement">
					<div class="card-body">
						<div class="mx-2 d-flex justify-content-between">
							<p class=" small fs-6 mb-0"><i class="fbi bi-geo"></i> <?= $event_city ?></p>
							<span class="badge small mb-3 text-yoopla-blue rounded-pill p-2 fw-medium border"><?= $event_categorie ?></span>
						</div>
						<h5 class="mb-2 fs-6 card-title"><?= $event_title ?></h5>
						<p class="small mb-2 card-text">Organisateur: <?= $organizer_name ?></p>
						<p class="small card-text text-muted"><?= $event_description ?></p>
					</div>
					<div class="d-flex justify-content-center">
						<a href="<?= $detail_url ?>" class="btn yoopla-primary fw-medium rounded-5 px-4 py-2 shadow mb-3">Voir l'activité</a>
					</div>
				</div>
				<!-- fin card -->
		<?php
			endforeach;
		}
		?>
	</div>
	<div class="text-center m-5">
		<a href="showAllEvents.php" class="btn btn-yoopla-secondary-outlined rounded-5 px-5 py-3 fw-medium shadow-sm icon-link icon-link-hover position-relative">Afficher tous les événements<i class="bi bi-arrow-right mx-3 position-absolute top-50 end-0 translate-middle-y"></i></a>
	</div>
</section>
<?php

require_once 'inc/footer.inc.php';
?>