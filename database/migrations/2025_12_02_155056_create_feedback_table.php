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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            // Data Pengunjung
            $table->string('visitor_name');
            $table->string('visitor_email');
            
            // Data Ulasan
            $table->integer('rating')->unsigned()->default(5); 
            $table->text('comment')->nullable();
            $table->string('photo_path')->nullable(); 

            // Data Respon Admin
            $table->enum('status', ['Pending', 'Responded'])->default('Pending');
            $table->text('admin_response')->nullable();
            $table->timestamp('responded_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
