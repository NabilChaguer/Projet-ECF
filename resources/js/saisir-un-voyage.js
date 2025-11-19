import Swal from "sweetalert2";

document.addEventListener('DOMContentLoaded', function () {

    const selectVoiture = document.querySelector('select[name="voiture_id"]');
    const blocNouveauVehicule = document.querySelector('.bg-light.border.rounded-3.p-4.shadow-sm');

    if (!selectVoiture || !blocNouveauVehicule) return;

    function toggleBloc() {
        if (selectVoiture.value !== "") {
            blocNouveauVehicule.style.display = "none";
        } else {
            blocNouveauVehicule.style.display = "block";
        }
    }

    selectVoiture.addEventListener('change', toggleBloc);

    toggleBloc();
});

    document.querySelectorAll('.ajouterPref').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const input = document.getElementById(`prefPersonnalise-${id}`);
            const liste = document.getElementById(`listePrefPersonnalise-${id}`);
            const valeur = input.value.trim();
            if (!valeur) return;

            const div = document.createElement('div');
            div.className = 'd-flex align-items-center mb-2 bg-light border rounded p-2';
            div.innerHTML = `
                <input type="hidden" name="vehicule[0][preferences][custom][]" value="${valeur}">
                <span class="me-auto">${valeur}</span>
                <button type="button" class="btn btn-sm btn-outline-danger supprimer-pref"><i class="bi bi-x-circle"></i></button>
            `;
            liste.appendChild(div);
            input.value = '';
            div.querySelector('.supprimer-pref').addEventListener('click', () => div.remove());
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("form-voyage");
    if (!form) return;

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        Swal.fire({
            title: "Confirmer l'enregistrement",
            html: `
                <p class="mb-2">L’enregistrement de votre voyage implique une retenue de :</p>
                <h3 class="fw-bold text-success">— 2 crédits —</h3>
                <p class="mt-2">Cette participation permet d’assurer le bon fonctionnement de la plateforme.</p>
            `,
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Continuer",
            cancelButtonText: "Annuler",
            customClass: {
                popup: "rounded-4",
                confirmButton: "btn btn-success me-2",
                cancelButton: "btn btn-secondary",
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Êtes-vous sûr ?",
                    text: "Les 2 crédits seront définitivement débités une fois le voyage enregistré.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Oui, valider",
                    cancelButtonText: "Non",
                    customClass: {
                        popup: "rounded-4",
                        confirmButton: "btn btn-danger me-2",
                        cancelButton: "btn btn-secondary",
                    },
                    buttonsStyling: false
                }).then((finalConfirm) => {
                    if (finalConfirm.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });
    });
});