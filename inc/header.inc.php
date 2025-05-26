<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Islem FOURATI">
  <meta name="description" content="Projet de soutenance de la formation de développeur web">
  <meta name="keywords" content="Projet de soutenance, reservation, HTML, CSS, JS, PHP, MySQL">
  <title>Yoopla</title>

  <!-- fast bootstrap link start -->
  <link href="https://cdn.jsdelivr.net/npm/fastbootstrap@2.2.0/dist/css/fastbootstrap.min.css" rel="stylesheet" integrity="sha256-V6lu+OdYNKTKTsVFBuQsyIlDiRWiOmtC8VQ8Lzdm2i4=" crossorigin="anonymous">
  <!-- fast bootstrap link end -->


  <!-- favicon  -->
  <link href="<?= BASE_URL ?>assets/images/logo/favIcon.svg" rel="icon">

  <!-- Bootstrap icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Bootstrap JS -->
  <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

  <!-- AOS -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <!-- css -->
  <link rel="stylesheet" href="<?= BASE_URL; ?>assets/css/styles.css">

  <!-- customized js -->
  <script src="<?= BASE_URL; ?>assets/script/script.js" defer></script>
</head>

<body id="gradientBg" data-bs-theme="light" class="vh-100">
  <header class="container-fluid">
    <nav class="navbar navbar-expand-lg">
      <div class="container position-relative">
        <?php
        if (!isset($_SESSION['user']) && empty($_SESSION['user'])) {
          $yooplaLink = BASE_URL . 'index.php';
        } else {
          $yooplaLink = BASE_URL . 'home.php';
        }
        ?>
        <a class="navbar-brand" href="<?= $yooplaLink ?>"><img src="<?= BASE_URL ?>assets/images/logo/logo.svg" style="width: 7rem;" alt="Yoopla logo" class="logo-yoopla" data-logo-light="<?= BASE_URL ?>assets/images/logo/logo.svg"
            data-logo-dark="<?= BASE_URL ?>assets/images/logo/logoYooplaWhite.svg"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navYoppla" aria-controls="navYoppla" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navYoppla">
          <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
            <?php
            // --------- si l'utilisateur est connecté
            if (isset($_SESSION['user'])) {
            ?>
              <li class="nav-item">
                <a class="nav-link fw-medium p-3" href="<?= BASE_URL ?>userEvents.php">Mes evenements</a>
              </li>
              <li class="nav-item">
                <a class="nav-link fw-medium p-3 mx-2" href="<?= BASE_URL ?>reservation/userReservations.php">Mes réservations</a>
              </li>
              <div class="mt-2">
                <a href="<?= BASE_URL ?>event/addEvent.php" class="btn rounded-5 btn-yoopla-primary px-3 py-2 mx-4" id="addEventBtn" type="submit"><i class="bi bi-plus-circle"></i> Créer un evenement</a>
              </div>
            <?php
            }
            ?>
          </ul>
        </div>
        <?php
        // --------- si l'utilisateur n'est pas connecté
        if (!isset($_SESSION) && empty($_SESSION) || !isset($_SESSION['user']) || empty($_SESSION['user'])) {
        ?>
          <div class="d-flex m-4">
            <a href="<?= BASE_URL ?>authentication/login.php" class="btn rounded-5 btn-yoopla-primary px-4" type="submit">Se connecter</a>
          </div>
        <?php
        }
        ?>
        <!-- dark/light mode -->
        <div class="form-check form-switch switchBtn"
          style="--bs-form-switch-width:34px;--bs-form-switch-height:20px"
          title="mode sombre/clair">
          <input class="form-check-input" type="checkbox" role="switch" id="themeSwitch" checked />
          <label class="form-check-label fw-medium fs-6 mt-1" for="themeSwitch">clair</label>
        </div>
        <!-- end switch button -->
        <?php
        if (isset($_SESSION['user'])) {
          $firstName = htmlspecialchars($_SESSION['user']['firstName'], ENT_QUOTES, 'UTF-8');
        ?>
          <div
            class="btn mx-4 connected end-0  nav-link fw-medium text-dark z-index-1"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasWithBothOptions"
            aria-controls="offcanvasWithBothOptions"
            title="Voir le profil">
            <!--menu profile start-->
            <div class="position-relative mb-2">
              <?php

              // debug($_SESSION['user']);
              if (isset($_SESSION['user']['photo_profil'])) {
                $photo_profil = BASE_URL . 'assets/images/profils/' . $_SESSION['user']['photo_profil'];
              }

              if (! str_contains($_SESSION['user']['photo_profil'], 'profil_')) {

					      $photo_profil = BASE_URL . '/assets/images/default-img/default_avatar.jpg';
				        }
              ?>
              <img src="<?= $photo_profil ?? BASE_URL . 'assets/images/default-img/default_avatar.jpg';  ?>" alt="photo de profil" class="rounded-circle border border-2 border-white" width="50" height="50">
              <span class="position-absolute top-100 start-50 connected-span translate-middle-x translate-middle-y p-2 border border-light rounded-circle bg-success-yoopla">
                <span class="visually-hidden">connecté</span>
              </span>
            </div><?= $firstName ?>
            <!-- menu profile end -->
          </div>
      </div>
    </nav>



    <div
      class="offcanvas offcanvas-start"
      data-bs-scroll="true"
      tabindex="-1"
      id="offcanvasWithBothOptions"
      aria-labelledby="offcanvasWithBothOptionsLabel">
      <!-- la page profile affiché -->
      <div class="offcanvas-header">
        <div class="d-flex align-items-start justify-content-start profileImg position-relative">
          <img src="<?= $photo_profil ?? BASE_URL . 'assets/images/default-img/default_avatar.jpg'; ?>" class="rounded-circle" width="50" height="50">
          <div>
            <h5 class="offcanvas-title m-3" id="offcanvasWithBothOptionsLabel">
              Bonjour, <?php echo $_SESSION['user']['firstName']; ?>
            </h5>
          </div>

        </div>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="offcanvas"
          aria-label="Close"></button>
      </div>
      <div class="offcanvas-body d-flex flex-column align-items-start justify-content-between">
      <div>

        <p class="fw-medium mb-3"><span class="fw-bold">Votre email: </span><?php echo $_SESSION['user']['email']; ?></p>
        <p><span class="fw-bold">Votre role: </span> <?php echo $_SESSION['user']['checkAdmin']; ?></p>
        <?php
          // Affichage du bouton qui envoie vers l'espace admin
          if ($_SESSION['user']['checkAdmin'] == 'admin') : ?>
          <div class="mb-3">
            <a href="<?= BASE_URL ?>admin/dashboard.php" class="yoopla-secondary text-decoration-none"><i class="bi bi-arrow-up-right-square ms-2"></i> Espace Admin</a>
          </div>
        <?php endif; ?>
        <div class="mb-3"><a href="<?= BASE_URL ?>profil.php" class="yoopla-secondary text-decoration-none"><i class="bi bi-arrow-up-right-square ms-2 "></i>Mon profil</a></div>
        <div>
          <a href="?action=logout" class="btn btn-yoopla-secondary-outlined rounded rounded-4 px-4 py-2">Déconnexion <i class="bi bi-box-arrow-right"></i></a>
  
        </div>

      </div>



        <div class="container text-center">
          <a class="navbar-brand mt-3" href="<?= BASE_URL ?>home.php"><img src="<?= BASE_URL ?>assets/images/logo/logo.svg" style="width: 7rem" alt="Yoopla_logo"></a>
          <p class="fs-6 text-body-secondary">© 2025 Islem FOURATI</p>
        </div>
      </div>
    <?php
        }
    ?>
  </header>
  <main class="min-vh-100">