import Swal from "sweetalert2";

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[id^="reservation-form-"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const prix = this.dataset.prix;

            Swal.fire({
                title: 'Confirmer la réservation',
                text: `Cette réservation vous coûtera ${prix} crédits.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Continuer',
                cancelButtonText: 'Annuler',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Êtes-vous sûr ?',
                        text: 'Vos crédits seront débités immédiatement.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Oui, confirmer',
                        cancelButtonText: 'Non',
                    }).then((result2) => {
                        if (result2.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        });
    });
});
