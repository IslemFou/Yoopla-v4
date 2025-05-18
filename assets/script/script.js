// alert("js est connecté");

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

//----------------------- dark/light mode -----------------------
const body = document.querySelector("body");
const switchBtn = document.querySelector(".switchBtn");
const labelSwitch = document.querySelector(".switchBtn label");

if (switchBtn) {
    switchBtn.addEventListener("click", () => {
        const currentTheme = body.getAttribute("data-bs-theme");
        const newTheme = currentTheme === "dark" ? "light" : "dark";
        body.setAttribute("data-bs-theme", newTheme);

        if (newTheme === "dark") {
            labelSwitch.textContent = "clair";
        } else {
            labelSwitch.textContent = "sombre";
        }
    });
}

// ------------------- affichage du mot de passe ----------------

let eyeSlash = document.querySelector(".eyeSlash");
console.log(eyeSlash);

let eyeSlashConfirm = document.querySelector(".eyeSlashConfirm");

let inputPassword = document.getElementById("password");
let inputPasswordConfirm = document.querySelector(".password");


//--------------------

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

eyeSlashConfirm.addEventListener("click", () => {
    if (inputPasswordConfirm.type == "password") {
        inputPasswordConfirm.type = "text";
        eyeSlashConfirm.classList.replace("bi-eye-slash-fill", "bi-eye-fill");
    } else {
        inputPasswordConfirm.type = "password";
        eyeSlashConfirm.classList.replace("bi-eye-fill", "bi-eye-slash-fill");
    }

});


////
//The input event is commonly used for real-time validation or feedback as the user interacts with a form field. In this case, it's being used to check if the password confirmation matches the original password as the user is typing it, and then display a visual indicator (a success or error icon) next to the confirmation field.

let labelConfirm = document.querySelector(".labelConfirm");

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


///// scroll to function

function scrollToSection(id) {
    const section = document.getElementById(id);
    if (section) {
        section.scrollIntoView({ behavior: "smooth" });
    }
}
