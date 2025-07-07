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
        Schema::create('quizzs', function (Blueprint $table) {
            $table->id();
             $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('formation_id')->nullable()->constrained();
            $table->string('title');
            $table->string('document_url');
            $table->string('img_url')->nullable();
            $table->text('description')->nullable(); 
            $table->enum('status',['ACTIVE','INACTIVE']);
            $table->enum('visibility',['ALL','ONLY_MEMBERS']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzs');
    }
};
