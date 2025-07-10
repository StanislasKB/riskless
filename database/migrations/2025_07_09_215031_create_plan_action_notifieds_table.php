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
        Schema::create('plan_action_notifieds', function (Blueprint $table) {
            $table->id();
             $table->foreignId('plan_action_id')->constrained('plan_actions')->nullOnDelete();
              $table->boolean('is_notified')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_action_notifieds');
    }
};
