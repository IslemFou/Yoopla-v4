// alert("js est connecté");
document.addEventListener("DOMContentLoaded", () => {
    console.log("le js est bien connecté");
});

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



// ------------------- affichage du mot de passe ----------------

let eyeSlash = document.querySelector(".eyeSlash");
// console.log(eyeSlash);

let eyeSlashConfirm = document.querySelector(".eyeSlashConfirm");

let inputPassword = document.getElementById("password");
let inputPasswordConfirm = document.querySelector(".password");


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

//The input event is commonly used for real-time validation or feedback as the user interacts with a form field. In this case, it's being used to check if the password confirmation matches the original password as the user is typing it, and then display a visual indicator (a success or error icon) next to the confirmation field.

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



/////---------------- (index.php) le flèche annimée : scroll to function

function scrollToSection(id) {
    const section = document.getElementById(id);
    if (section) {
        section.scrollIntoView({ behavior: "smooth" });
    }
}

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

//------- fonction changeLogoTheme --------------------

function changeLogoTheme() {
    const body = document.querySelector("body");
    const currentTheme = body.getAttribute("data-bs-theme");
    const imageLogos = document.querySelectorAll(".logo-yoopla"); // Sélectionne tous les éléments avec la classe "logo-yoopla"

    imageLogos.forEach(imageLogo => {
        const lightLogo = imageLogo.dataset.logoLight;
        const darkLogo = imageLogo.dataset.logoDark;

        if (lightLogo && darkLogo) {
            imageLogo.setAttribute("src", currentTheme === "dark" ? darkLogo : lightLogo);
        } else {
            console.warn("Attributs manquants sur : ", imageLogo);
        }
    });
}
///------- Changer le logo en fonction du thème clair ou sombre
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


//---------- script modal CGU --------------------

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




// ----- script pour empecher le comportement par défaut du formulaire -----
document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    // console.log(form);
    if (form) {
        form.addEventListener("submit", (e) => {
            e.preventDefault(); // empêche le rechargement de la page
            console.log("Formulaire intercepté !");

        });
    }
});