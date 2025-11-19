<h2>Covoiturage annulé</h2>

<p>Le chauffeur a annulé le covoiturage prévu :</p>

<p>
    <strong>{{ $covoiturage->lieu_depart }} → {{ $covoiturage->lieu_arrivee }}</strong><br>
    Le {{ $covoiturage->date_depart->format('d/m/Y') }} à {{ $covoiturage->heure_depart }}
</p>

<p>Vous avez été remboursé automatiquement.</p>

<p>Merci de votre compréhension.</p>


