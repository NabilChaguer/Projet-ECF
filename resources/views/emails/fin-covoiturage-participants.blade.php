<title>Fin du covoiturage</title>

    <h2>Votre covoiturage est terminé</h2>

    <p>Bonjour {{ $reservation->utilisateur->prenom }} {{ $reservation->utilisateur->nom }},</p>

    <p>Le trajet suivant vient d'être clôturé :</p>

    <ul>
        <li><strong>Départ :</strong> {{ $reservation->covoiturage->lieu_depart }}</li>
        <li><strong>Arrivée :</strong> {{ $reservation->covoiturage->lieu_arrivee }}</li>
        <li><strong>Date :</strong> {{ $reservation->covoiturage->date_depart->format('d/m/Y') }}</li>
    </ul>

    <p>
        Merci de vous rendre dans votre espace utilisateur afin de 
        <strong>confirmer que tout s’est bien passé</strong> 
        ou signaler un problème.
    </p>

    <p>Merci d’utiliser Ecoride 🚗</p>