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
        Schema::create('members', function (Blueprint $table) {
            $table->increments('member_id');
            $table->unsignedInteger('branch_id')->nullable();
            $table->unsignedInteger('tier_id')->nullable();
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('email', 100)->unique();
            $table->string('phone', 20)->nullable();
            $table->date('join_date')->nullable();
            $table->enum('status', ['Active', 'Inactive', 'Suspended'])->default('Active');
            $table->timestamps();

            $table->foreign('branch_id')->references('branch_id')->on('branches')->onDelete('set null');
            $table->foreign('tier_id')->references('tier_id')->on('membership_tiers')->onDelete('set null');

            $table->index('status', 'idx_member_status');
            $table->index('branch_id', 'idx_branch');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
