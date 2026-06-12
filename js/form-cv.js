document.addEventListener('DOMContentLoaded', function () {
    const profileForm = document.querySelector('form[action*="enregistrer-cv.php?action=update"]');

    if (profileForm) {
        profileForm.addEventListener('submit', function (e) {
            let isValid = true;

            // 1. Vérification qu'au moins une case "Domaine de recherche" est cochée
            const checkboxes = document.querySelectorAll('input[name="domaines[]"]');
            let isChecked = false;
            
            checkboxes.forEach(function (checkbox) {
                if (checkbox.checked) {
                    isChecked = true;
                }
            });

            if (!isChecked) {
                e.preventDefault();
                alert("Veuillez sélectionner au moins un domaine de recherche (ex: Stage, Apprentissage...).");
                isValid = false;
                return false;
            }

            // 2. Validation de la taille de la photo de profil (Optionnel mais recommandé pour le serveur)
            const photoInput = document.querySelector('input[name="photo"]');
            if (photoInput && photoInput.files.length > 0) {
                const fileSize = photoInput.files[0].size / 1024 / 1024; // Taille en Mo
                if (fileSize > 2) {
                    e.preventDefault();
                    alert("La photo de profil est trop lourde (Maximum 2 Mo).");
                    isValid = false;
                }
            }

            return isValid;
        });
    }
});