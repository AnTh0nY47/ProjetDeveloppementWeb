document.addEventListener('DOMContentLoaded', function () {
    // Sélectionner tous les formulaires présents dans les modales de convocation
    const convocationForms = document.querySelectorAll('form[action*="convoquer.php"]');

    convocationForms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            const dateInput = form.querySelector('input[name="date_entretien"]');
            
            if (dateInput) {
                const selectedDate = new Date(dateInput.value);
                const currentDate = new Date();

                // Validation : La date de l'entretien ne peut pas être antérieure à l'instant présent
                if (selectedDate < currentDate) {
                    e.preventDefault();
                    alert("Erreur : La date et l'heure de l'entretien ne peuvent pas être dans le passé.");
                    dateInput.classList.add('is-invalid');
                    return false;
                } else {
                    dateInput.classList.remove('is-invalid');
                }
            }
        });
    });
});