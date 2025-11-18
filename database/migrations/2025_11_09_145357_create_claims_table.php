<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimsTable extends Migration
{
    public function up()
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->string('claim_code')->unique();
            $table->foreignId('policy_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_id')->nullable()->constrained()->nullOnDelete();
            $table->date('loss_date')->nullable();
            $table->date('claim_date')->nullable();
            $table->decimal('claim_amount', 15, 2)->nullable();
            $table->string('claim_summary')->nullable();
            $table->foreignId('claim_status_id')->nullable()->constrained('lookup_values')->nullOnDelete();
            $table->date('close_date')->nullable();
            $table->decimal('paid_amount', 15, 2)->nullable();
            $table->string('claim_form_path')->nullable();
            $table->string('other_documents_path')->nullable();
            $table->text('settlement_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('claims');
    }
}
