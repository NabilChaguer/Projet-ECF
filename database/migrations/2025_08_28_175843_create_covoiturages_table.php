<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('covoiturages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voiture_id')->constrained('voitures')->onDelete('cascade');
            $table->date('date_depart');
            $table->time('heure_depart');
            $table->string('lieu_depart');
            $table->date('date_arrivee');
            $table->time('heure_arrivee');
            $table->string('lieu_arrivee');
            $table->unsignedSmallInteger('nb_place');
            $table->decimal('prix_personne', 8, 2);
            $table->string('statut', 50)->default('ouvert');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('covoiturages');
    }
};
