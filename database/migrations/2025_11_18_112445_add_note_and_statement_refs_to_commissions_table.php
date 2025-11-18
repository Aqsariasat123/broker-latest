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
        Schema::table('commissions', function (Blueprint $table) {
            $table->foreignId('commission_note_id')
                ->nullable()
                ->constrained('commission_notes')
                ->nullOnDelete();

            $table->foreignId('commission_statement_id')
                ->nullable()
                ->constrained('commission_statements')
                ->nullOnDelete();

            $table->string('status')->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commissions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('commission_note_id');
            $table->dropConstrainedForeignId('commission_statement_id');
            $table->dropColumn('status');
        });
    }
};
