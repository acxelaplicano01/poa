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
        Schema::table('tipo_proceso_compra', function (Blueprint $table) {
            $table->foreignId('idPoa')->nullable()->after('id')->constrained('poas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipo_proceso_compra', function (Blueprint $table) {
            $table->dropForeign(['idPoa']);
            $table->dropColumn('idPoa');
        });
    }
};
