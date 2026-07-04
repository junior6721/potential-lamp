<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mouvements', function (Blueprint $table) {
            $table->foreignId('fournisseur_id')->nullable()->after('produit_id')
                  ->constrained('fournisseurs')->nullOnDelete();
            $table->foreignId('client_id')->nullable()->after('fournisseur_id')
                  ->constrained('clients')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('mouvements', function (Blueprint $table) {
            $table->dropForeign(['fournisseur_id']);
            $table->dropForeign(['client_id']);
            $table->dropColumn(['fournisseur_id', 'client_id']);
        });
    }
};
