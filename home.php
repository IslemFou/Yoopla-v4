<?php
require_once 'inc/init.inc.php';
require_once 'inc/functions.inc.php';

$title = "Mes activités";
$allEvents = getAllEvents();
$limitedEvents = getLimitedEvents();
$result = [];

//--------- Tous les events -----------
//affichage de tous les events de la base de données, affichage limité

if (!isset($_SESSION['user'])) { // si une session existe avec un identifiant user je me redirige vers la page home.php
	header("location:index.php");
}

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
				<input type="search" name="city" class="form-control border-0" id="searchLocation" placeholder="Paris ..." aria-label="Lieu">
				<label for="searchLocation"><i class="bi bi-geo me-2"></i>Lieu souhaité</label>
			</div>
			<p class="fw-bold fs-5 text-yoopla-red">Ou</p>

			<div class="form-floating border-0">
				<input type="search" name="title" class="form-control border-0" id="searchEvent" placeholder="Activité, événement ..." aria-label="Événement">
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
		if ($result && count($result) > 0) {
			echo "<h5 class=\" fw-medium text-centerp-4\">Résultats de recherche :</h5>";
			foreach ($result as $searchResult) {
				echo "
				<p><strong>" . ($searchResult['title']) . "</strong> à <strong>" . ($searchResult['city']) . "</strong></p>";
			}
		}
		?>
	</div>

	<!--  end of search bar-->
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

					$image_event = BASE_URL . '/assets/images/default-img/default_event.jpg';
				}

		?>
				<!-- Debut card -->
				<div class="card col-sm-12 col-md-4 col-lg-3 rounded-4 shadow m-2 mb-5" style="height:40rem;">

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