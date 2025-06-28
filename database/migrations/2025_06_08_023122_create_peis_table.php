<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
         if (!Schema::hasTable('peis')) {
            Schema::create('peis', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->year('initialYear');
                $table->year('finalYear');
      
                $table->foreignId('idInstitucion')
                      ->constrained('institucions')
                      ->onDelete('cascade');

                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('peis');
    }
};
