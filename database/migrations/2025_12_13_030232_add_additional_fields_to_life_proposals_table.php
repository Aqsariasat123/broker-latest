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
        Schema::table('life_proposals', function (Blueprint $table) {
            // Proposer's details (will use contact_id, but add sex and anb)
            $table->string('sex', 1)->nullable()->after('age'); // M, F
            $table->integer('anb')->nullable()->after('sex'); // Age Next Birthday
            
            // Riders - store as JSON
            $table->json('riders')->nullable()->after('add_ons'); // Array of rider names
            $table->json('rider_premiums')->nullable()->after('riders'); // Array of premiums
            
            // Premium details
            $table->decimal('annual_premium', 15, 2)->nullable()->after('premium');
            $table->decimal('base_premium', 15, 2)->nullable()->after('annual_premium');
            $table->decimal('admin_fee', 15, 2)->nullable()->after('base_premium');
            $table->decimal('total_premium', 15, 2)->nullable()->after('admin_fee');
            
            // Medical examination
            $table->boolean('medical_examination_required')->default(false)->after('date_completed');
            $table->string('clinic')->nullable()->after('medical_examination_required');
            $table->date('date_referred')->nullable()->after('clinic');
            $table->text('exam_notes')->nullable()->after('date_referred');
            
            // Application details
            $table->string('policy_no')->nullable()->after('status');
            $table->decimal('loading_premium', 15, 2)->nullable()->after('policy_no');
            $table->date('start_date')->nullable()->after('loading_premium');
            $table->date('maturity_date')->nullable()->after('start_date');
            
            // Payment method (separate from source_of_payment)
            $table->string('method_of_payment')->nullable()->after('source_of_payment');
            
            // Source name (can link to client_id or contact_id)
            $table->string('source_name')->nullable()->after('agency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('life_proposals', function (Blueprint $table) {
            $table->dropColumn([
                'sex', 'anb', 'riders', 'rider_premiums',
                'annual_premium', 'base_premium', 'admin_fee', 'total_premium',
                'medical_examination_required', 'clinic', 'date_referred', 'exam_notes',
                'policy_no', 'loading_premium', 'start_date', 'maturity_date',
                'method_of_payment', 'source_name'
            ]);
        });
    }
};
