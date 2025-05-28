// alert("js est connecté");
document.addEventListener("DOMContentLoaded", () => {
    console.log("le js est bien connecté");
});


//####### PAGE RESERVATION ########## 
//---------------- affichage de l'icone de voir l'evenement -----------------------------------
const imageReservation = document.getElementById("image-reservation");
// console.log(imageReservation);

const divShadow = document.getElementById("gradientBgGrey");
// console.log(divShadow);

if (imageReservation) {
    imageReservation.addEventListener("mouseenter", () => {
        divShadow.classList.remove("d-none");
        divShadow.style.transition = "all 0.5s ease-in-out";
    });

    imageReservation.addEventListener("mouseout", () => {
        divShadow.classList.add("d-none");
    });
}


//### PAGE INSCRIPTION / MODIFICATION PROFIL ########## 
// ------------------- affichage du mot de passe ----------------

let eyeSlash = document.querySelector(".eyeSlash");
// console.log(eyeSlash);

let eyeSlashConfirm = document.querySelector(".eyeSlashConfirm");

let inputPassword = document.getElementById("password");
let inputPasswordConfirm = document.querySelector("#confirmMdp");


//--------------------

if (eyeSlash) {
    eyeSlash.addEventListener("click", () => {
        //on teste le password
        if (inputPassword.type == "password") {
            inputPassword.type = "text";
            eyeSlash.classList.replace("bi-eye-fill", "bi-eye-slash-fill");
        } else {
            inputPassword.type = "password";
            eyeSlash.classList.replace("bi-eye-slash-fill", "bi-eye-fill");
        }

    });
}

if (eyeSlashConfirm) {
    eyeSlashConfirm.addEventListener("click", () => {
        if (inputPasswordConfirm.type == "password") {
            inputPasswordConfirm.type = "text";
            eyeSlashConfirm.classList.replace("bi-eye-slash-fill", "bi-eye-fill");
        } else {
            inputPasswordConfirm.type = "password";
            eyeSlashConfirm.classList.replace("bi-eye-fill", "bi-eye-slash-fill");
        }

    });
}

////--------------- Ajout d'icon dans le cas de confirmation de mot de passe -----------------

//L'événement input est couramment utilisé pour la validation en temps réel ou pour fournir un retour visuel à l'utilisateur lors de l'interaction avec un champ de formulaire. Ici, il sert à vérifier si la confirmation du mot de passe correspond au mot de passe original pendant la saisie, puis à afficher un indicateur visuel (icône de succès ou d'erreur) à côté du champ de confirmation.

let labelConfirm = document.querySelector(".labelConfirm");

if (inputPasswordConfirm) {

    inputPasswordConfirm.addEventListener("input", () => {

        const oldIconTriangle = document.querySelector(".bi-exclamation-triangle-fill");
        if (oldIconTriangle) oldIconTriangle.remove();

        const oldIconSuccess = document.querySelector(".bi-check-circle-fill");
        if (oldIconSuccess) oldIconSuccess.remove();

        if (inputPassword.value != inputPasswordConfirm.value) {

            let i = document.createElement("i");
            i.classList.add("bi", "mx-2", "bi-exclamation-triangle-fill", "text-danger");
            labelConfirm.insertAdjacentElement("afterend", i);

            if (oldIconSuccess) oldIconSuccess.remove();

        } else if (inputPassword.value == inputPasswordConfirm.value) {

            let i = document.createElement("i");
            i.classList.add("bi", "bi-check-circle-fill", "text-success", "mx-2");
            labelConfirm.insertAdjacentElement("afterend", i);
            if (oldIconTriangle) oldIconTriangle.remove();

        }
    });

}

//---------- script modal CGU --------------------

// Script pour la modal des CGU
document.addEventListener("DOMContentLoaded", function () {
    const acceptBtn = document.getElementById("acceptTerms");
    const checkbox = document.getElementById("flexCheckDefault");
    const modalElement = document.getElementById("termsModal");

    if (modalElement) {
        // Crée une instance Bootstrap Modal
        const bsModal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement); // permet de cibler et manipuler le modal selon l’API Bootstrap 5.

        acceptBtn.addEventListener("click", function () {
            // Cocher la case
            checkbox.checked = true; //coche la case automatiquement

            // Fermer la modal
            bsModal.hide();
        });
    }
});


//#### ### PAGE INDEX ########## 
/////---------------- index.php & home.php --------  le flèche annimée : scroll to function

// Fonction pour faire défiler vers une section donnée
function scrollToSection(id) {
    const section = document.getElementById(id);
    if (section) {
        section.scrollIntoView({ behavior: "smooth" });
    }
}


//##### radio button pour se basculer entre light et dark mode ###### 
//----------------------- dark/light mode -----------------------
const body = document.querySelector("body");
const switchBtn = document.querySelector(".switchBtn");
const labelSwitch = document.querySelector(".switchBtn label");

const bgRegistration = document.querySelector(".bgRegistration");

if (switchBtn) {
    switchBtn.addEventListener("click", () => {
        const currentTheme = body.getAttribute("data-bs-theme");

        console.log(currentTheme);
        const newTheme = currentTheme === "dark" ? "light" : "dark";
        body.setAttribute("data-bs-theme", newTheme);

        if (newTheme === "dark") {
            labelSwitch.textContent = "clair";

            if (bgRegistration) {

                bgRegistration.style.backgroundColor = "rgb(73, 54, 54)";
            }

        } else {
            labelSwitch.textContent = "sombre";

            if (bgRegistration) {

                bgRegistration.style.backgroundColor = "rgb(255, 225, 225)";
            }
        }
    });
}

// suite au changement de thème, on change le logo en fonction du thème clair ou sombre 
//------- fonction changeLogoTheme --------------------

// Fonction pour changer le logo selon le thème (clair ou sombre)
/**
 * Met à jour la source de tous les éléments ayant la classe "logo-yoopla" en fonction du thème Bootstrap actuel.
 * 
 * La fonction vérifie l'attribut "data-bs-theme" sur l'élément <body> pour déterminer si le thème est "dark" ou non.
 * Pour chaque élément logo, elle modifie l'attribut "src" vers l'URL d'image appropriée, spécifiée dans les attributs
 * "data-logo-light" et "data-logo-dark".
 * 
 * Si l'un de ces attributs de données est manquant sur un logo, un avertissement est affiché dans la console.
 *
 * @functions
 * @returns {void}
 */
function changeLogoTheme() {
    const body = document.querySelector("body");
    const currentTheme = body.getAttribute("data-bs-theme");
    const imageLogos = document.querySelectorAll(".logo-yoopla"); // Sélectionne tous les éléments avec la classe "logo-yoopla"

    imageLogos.forEach(imageLogo => {
        const lightLogo = imageLogo.dataset.logoLight;
        //Cette ligne récupère la valeur de l’attribut data-logo-light de l’élément HTML imageLogo. Cela correspond à l’URL du logo à utiliser pour le thème clair.
        const darkLogo = imageLogo.dataset.logoDark;
        //Cette ligne récupère la valeur de l’attribut data-logo-dark de l’élément HTML imageLogo. Cela correspond à l’URL du logo à utiliser pour le thème sombre.

        
        //Cette ligne vérifie si les attributs de données lightLogo et darkLogo existent. Si les deux sont présents, elle met à jour l’attribut src de l’élément imageLogo avec l’URL appropriée en fonction du thème actuel.

        if (lightLogo && darkLogo) {
            imageLogo.setAttribute("src", currentTheme === "dark" ? darkLogo : lightLogo);
        } else {
            console.warn("Attributs manquants sur : ", imageLogo);
        }
    });
}

document.addEventListener("DOMContentLoaded", () => {
    const themeSwitch = document.getElementById("themeSwitch");
    const labelSwitch = document.querySelector("label[for='themeSwitch']");
    const body = document.querySelector("body");

    // Charger le thème sauvegardé ou mettre 'light' par défaut
    const savedTheme = localStorage.getItem('theme') || 'light';

    body.setAttribute("data-bs-theme", savedTheme); // Appliquer le thème chargé

    // Initialiser l'état du switch et le label
    themeSwitch.checked = savedTheme === "light";
    labelSwitch.textContent = savedTheme === "light" ? "sombre" : "clair";

    if (bgRegistration) {
        if (savedTheme === "dark") {
            bgRegistration.style.backgroundColor = "rgb(73, 54, 54)";
        } else {
            bgRegistration.style.backgroundColor = "rgb(255, 225, 225)";
        }
    }

    // Quand on change le switch
    themeSwitch.addEventListener("change", () => {
        const newTheme = themeSwitch.checked ? "light" : "dark";
        body.setAttribute("data-bs-theme", newTheme);
        labelSwitch.textContent = newTheme === "light" ? "sombre" : "clair";
        changeLogoTheme();
        // Sauvegarder le choix dans localStorage
        localStorage.setItem('theme', newTheme);

        // Met à jour le background de .bgRegistration selon le nouveau thème
        if (bgRegistration) {
            bgRegistration.style.backgroundColor = newTheme === "dark" ? "rgb(73, 54, 54)" : "rgb(255, 225, 225)";
        }
    });

    // Appliquer le logo dès le chargement
    changeLogoTheme();
});



//#### PAGE PROFIL ########## 
//// ----------------- script pour la page profil : pourque lors de l'insertion de l'image sur l'input elle s'affiche directement ------- 

// Ce code JavaScript permet d'afficher un aperçu de l'image dès qu'un utilisateur sélectionne un fichier image via un champ de type fichier (input type="file").
// C'est une fonctionnalité couramment utilisée sur les pages de profil ou les formulaires de téléchargement d'images.
const input = document.getElementById('photo_profil');
const preview = document.getElementById('photoPreview');

input.addEventListener('change', function () {
    const file = this.files[0];
    if (file && file.type.startsWith("image/")) {
        const reader = new FileReader(); // Si le fichier sélectionné est une image, on crée un nouvel objet FileReader.
        // L'API FileReader permet aux applications web de lire de façon asynchrone le contenu des fichiers (ou des tampons de données brutes) stockés sur l'ordinateur de l'utilisateur.
        reader.onload = function (e) {
            preview.src = e.target.result;
            // Cette ligne prend l'URL de données (les données de l'image) et la définit comme source (src) de l'élément de prévisualisation (<img>). Cela permet d'afficher l'image dans le navigateur.
        };
        reader.readAsDataURL(file);
        // Cette méthode démarre le processus de lecture du contenu du fichier spécifié.
        // Elle lit le fichier et, une fois terminé, le gestionnaire d'événement reader.onload (défini à l'étape précédente) sera déclenché, et l'attribut result contiendra les données sous forme d'URL.
    }
});

///-------------------------------