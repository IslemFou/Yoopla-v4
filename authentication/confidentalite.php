<?php
require_once '../inc/init.inc.php';
require_once '../inc/functions.inc.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Islem FOURATI">
    <meta name="description" content="Projet de soutenance de la formation de développeur web">
    <meta name="keywords" content="Projet de soutenance, HTML, CSS, JS, PHP, MySQL">
    <!-- bootstrap  css link-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- favicon  -->
    <link href="assets/images/logo/favIcon.svg" rel="icon">

    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- css -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/styles.css">
    <title>Politique de Confidentialité – Yoopla</title>
</head>
<!-- css -->
<link rel="stylesheet" href="<?= BASE_URL; ?>assets/css/styles.css">

<!-- customized js -->
<script src="<?= BASE_URL; ?>assets/script/script.js" defer></script>

<body>
    <main class="mt-3 container">
        <h1>Politique de Confidentialité – Yoopla</h1>
        <p><strong>Dernière mise à jour :</strong> 25/05/2025</p>

        <p>Chez <strong>Yoopla</strong>, la protection de vos données personnelles est une priorité. Cette politique a pour objectif de vous informer sur la manière dont nous collectons, utilisons et protégeons vos données.</p>

        <h2>1. Qui sommes-nous ?</h2>
        <p>Yoopla est une plateforme de création et de réservation d’événements, accessible via application web et mobile.</p>
        <p><strong>Responsable du traitement :</strong> [Nom de la société ou du développeur], [adresse], [email de contact RGPD]</p>

        <h2>2. Données collectées</h2>
        <ul>
            <li><strong>Données d’identification</strong> : nom, prénom, adresse e-mail, mot de passe, photo de profil.</li>
            <li><strong>Données de réservation</strong> : événements réservés, participations, historiques.</li>
            <li><strong>Données de paiement</strong> : traitées par nos partenaires (ex : Stripe, PayPal). Nous ne stockons pas vos données bancaires.</li>
            <li><strong>Données techniques</strong> : adresse IP, type d’appareil, navigateur, cookies.</li>
        </ul>

        <h2>3. Finalités de traitement</h2>
        <p>Nous utilisons vos données pour :</p>
        <ul>
            <li>Gérer vos comptes et réservations</li>
            <li>Permettre la création d’événements</li>
            <li>Envoyer des notifications et emails liés aux événements</li>
            <li>Assurer la sécurité de la plateforme</li>
            <li>Améliorer votre expérience utilisateur</li>
        </ul>

        <h2>4. Base légale du traitement</h2>
        <p>Vos données sont collectées sur la base de votre consentement, de l’exécution du contrat (utilisation de l’application), et du respect de nos obligations légales.</p>

        <h2>5. Durée de conservation</h2>
        <ul>
            <li>Compte utilisateur : tant que le compte est actif</li>
            <li>Données de réservation : 3 ans après la dernière activité</li>
            <li>Emails marketing : 3 ans après le dernier contact</li>
            <li>Cookies : selon leur nature (voir politique de cookies)</li>
        </ul>

        <h2>6. Destinataires des données</h2>
        <p>Vos données peuvent être partagées avec :</p>
        <ul>
            <li>Nos prestataires techniques (hébergement, emailing, paiement)</li>
            <li>Les organisateurs d’événements (dans le cadre des réservations uniquement)</li>
        </ul>
        <p>Nous ne vendons jamais vos données personnelles.</p>

        <h2>7. Sécurité</h2>
        <p>Nous utilisons des protocoles de sécurité et des mesures techniques pour protéger vos données contre tout accès non autorisé ou perte accidentelle.</p>

        <h2>8. Vos droits</h2>
        <p>Conformément au RGPD, vous disposez des droits suivants :</p>
        <ul>
            <li>Droit d’accès à vos données</li>
            <li>Droit de rectification et de suppression</li>
            <li>Droit d’opposition et de limitation</li>
            <li>Droit à la portabilité</li>
            <li>Droit de retirer votre consentement à tout moment</li>
            <li>Droit d’introduire une réclamation auprès de la CNIL</li>
        </ul>
        <p>Pour exercer vos droits, contactez-nous à : <a href="mailto:yoopla@gmail.com">yoopla@gmail.com</a></p>

        <h2>9. Cookies</h2>
        <p>Nous utilisons des cookies pour améliorer votre expérience, mesurer l’audience et proposer des fonctionnalités personnalisées. Vous pouvez modifier vos préférences à tout moment. <a href="/cookies">En savoir plus</a>.</p>

        <h2>10. Modifications</h2>
        <p>Cette politique peut être mise à jour à tout moment. En cas de modifications importantes, nous vous en informerons par email ou via l’application.</p>

        <hr>

        <p><strong>En utilisant Yoopla, vous acceptez la présente Politique de Confidentialité.</strong></p>
        <a href="<?= BASE_URL ?>authentication/registration.php" class="text-center text-decoration-none btn-yoopla-secondary-outlined rounded-5 px-5 py-3 fw-medium shadow-sm icon-link icon-link-hover"><i class=" bi bi-arrow-left-square"></i>Retour à la page d'inscription</a>
    </main>
    <footer class="container d-flex justify-content-around py-3">
        <p>&copy; 2025 Yoopla. Tous droits réservés</p>
    </footer>
    <!-- Bootstrap popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous"></script>
    <!-- customized js -->
    <script src="<?= BASE_URL; ?>assets/script/script.js"></script>
</body>

</html>