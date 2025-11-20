<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'validation_passager')) {
                $table->enum('validation_passager', ['en_attente', 'ok', 'probleme'])
                    ->default('en_attente')
                    ->after('statut');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'validation_passager')) {
                $table->dropColumn('validation_passager');
            }
        });
    }
};
