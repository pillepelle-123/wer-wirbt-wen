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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->enum('offered_by', ['referrer', 'referred'])->default('referrer');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('offer_title');
            $table->text('offer_description');
            $table->integer('reward_total_cents');
            // $table->percent('reward_split_percent')->default(50);
            $table->decimal('reward_split_percent', 3, 2)->default(0.5);
            // $table->string('communication_channel')->nullable();
            $table->enum('status', ['active', 'inactive', 'matched', 'closed'])->default('active');
            $table->timestamps();
            // $table->percent('reward_split_referee_percent')->default(50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_offers');
    }
};
