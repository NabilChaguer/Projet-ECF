<h2>Un passager s'est désisté</h2>

<p>Un passager a annulé sa réservation pour le trajet :</p>

<p>
    <strong>{{ $covoiturage->lieu_depart }} → {{ $covoiturage->lieu_arrivee }}</strong><br>
    Le {{ $covoiturage->date_depart->format('d/m/Y') }} à {{ $covoiturage->heure_depart }}
</p>

<p>Une place a été libérée automatiquement.</p>
