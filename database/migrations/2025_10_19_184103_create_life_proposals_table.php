<?php
// database/migrations/2024_01_01_create_life_proposals_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLifeProposalsTable extends Migration
{
    public function up()
    {
        Schema::create('life_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('insurer_id')->nullable()->constrained('lookup_values')->nullOnDelete();
            $table->foreignId('policy_plan_id')->nullable()->constrained('lookup_values')->nullOnDelete();
            $table->decimal('sum_assured', 15, 2)->nullable();
            $table->integer('term')->nullable();
            $table->string('add_ons')->nullable();
            $table->date('offer_date')->nullable();
            $table->decimal('premium', 15, 2)->nullable();
            $table->foreignId('frequency_id')->nullable()->constrained('lookup_values')->nullOnDelete();
            $table->foreignId('proposal_stage_id')->nullable()->constrained('lookup_values')->nullOnDelete();
            $table->date('status_date')->nullable();
            $table->integer('age')->nullable();
            $table->foreignId('status_id')->nullable()->constrained('lookup_values')->nullOnDelete();
            $table->foreignId('source_of_payment_id')->nullable()->constrained('lookup_values')->nullOnDelete();
            $table->string('mcr')->nullable();
            $table->string('doctor')->nullable();
            $table->date('date_sent')->nullable();
            $table->date('date_completed')->nullable();
            $table->text('notes')->nullable();
            $table->string('agency')->nullable();
            $table->string('prid')->unique();
            $table->foreignId('class_id')->nullable()->constrained('lookup_values')->nullOnDelete();
            $table->boolean('is_submitted')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('life_proposals');
    }
}