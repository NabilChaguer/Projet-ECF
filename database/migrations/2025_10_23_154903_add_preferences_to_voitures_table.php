<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('voitures', function (Blueprint $table) {
            $table->json('preferences')->nullable()->after('couleur');
        });
    }

    public function down()
    {
        Schema::table('voitures', function (Blueprint $table) {
            $table->dropColumn('preferences');
        });
    }

};
