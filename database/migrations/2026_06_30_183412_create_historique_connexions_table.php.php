<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('historique_connexions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('adresse_ip')->nullable();
            $table->timestamp('connecte_a')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historique_connexions');
    }
};