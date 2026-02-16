<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Punto 14: email opcional; teléfono como contacto principal. */
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('email')->nullable()->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('email')->nullable(false)->unique()->change();
        });
    }
};
