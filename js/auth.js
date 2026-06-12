document.addEventListener('DOMContentLoaded', function () {
    const registerForm = document.getElementById('registerForm');
    
    if (registerForm) {
        const emailInput = document.getElementById('email');
        const consentCheckbox = document.getElementById('consent');

        registerForm.addEventListener('submit', function (e) {
            let isValid = true;
            const emailValue = emailInput.value.trim();

            // 1. Validation de l'adresse email JUNIA
            if (!emailValue.endsWith('@junia.com')) {
                e.preventDefault();
                emailInput.classList.add('is-invalid');
                isValid = false;
            } else {
                emailInput.classList.remove('is-invalid');
                emailInput.classList.add('is-valid');
            }

            // 2. Validation du consentement RGPD
            if (!consentCheckbox.checked) {
                e.preventDefault();
                consentCheckbox.classList.add('is-invalid');
                isValid = false;
            } else {
                consentCheckbox.classList.remove('is-invalid');
            }

            return isValid;
        });

        // Retrait des classes d'erreur lors de la saisie
        emailInput.addEventListener('input', function () {
            if (this.value.trim().endsWith('@junia.com')) {
                emailInput.classList.remove('is-invalid');
            }
        });
    }
});