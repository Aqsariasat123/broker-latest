<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('policies')) {
            return;
        }

        // Helper function to migrate varchar to foreign key
        $migrateColumn = function($varcharColumn, $foreignKeyColumn, $categoryName, $afterColumn = null) {
            if (!Schema::hasColumn('policies', $foreignKeyColumn)) {
                Schema::table('policies', function (Blueprint $table) use ($foreignKeyColumn, $afterColumn) {
                    if ($afterColumn && Schema::hasColumn('policies', $afterColumn)) {
                        $table->foreignId($foreignKeyColumn)->nullable()->after($afterColumn)->constrained('lookup_values')->nullOnDelete();
                    } else {
                        $table->foreignId($foreignKeyColumn)->nullable()->constrained('lookup_values')->nullOnDelete();
                    }
                });
                
                // Migrate data from old varchar column if it exists
                if (Schema::hasColumn('policies', $varcharColumn)) {
                    $policies = DB::table('policies')->whereNotNull($varcharColumn)->get();
                    
                    foreach ($policies as $policy) {
                        $lookupValue = DB::table('lookup_values')
                            ->join('lookup_categories', 'lookup_values.lookup_category_id', '=', 'lookup_categories.id')
                            ->where('lookup_categories.name', $categoryName)
                            ->where('lookup_values.name', $policy->{$varcharColumn})
                            ->first();
                        
                        if ($lookupValue) {
                            DB::table('policies')
                                ->where('id', $policy->id)
                                ->update([$foreignKeyColumn => $lookupValue->id]);
                        }
                    }
                }
            }
        };

        // Add all missing foreign key columns
        $migrateColumn('insurer', 'insurer_id', 'Insurers', 'policy_no');
        $migrateColumn('policy_class', 'policy_class_id', 'Class', 'insurer_id');
        $migrateColumn('policy_plan', 'policy_plan_id', 'Policy Plans', 'policy_class_id');
        $migrateColumn('policy_status', 'policy_status_id', 'Policy Status', 'insured_item');
        $migrateColumn('biz_type', 'business_type_id', 'Business Type', 'renewable');
        $migrateColumn('frequency', 'frequency_id', 'Frequency', 'premium');
        $migrateColumn('pay_plan', 'pay_plan_lookup_id', 'Payment Plan', 'frequency_id');
        $migrateColumn('agency', 'agency_id', 'APL Agency', 'pay_plan_lookup_id');
        
        // Add channel_id (no old column to migrate from)
        if (!Schema::hasColumn('policies', 'channel_id')) {
            Schema::table('policies', function (Blueprint $table) {
                $afterColumn = Schema::hasColumn('policies', 'agency_id') ? 'agency_id' : 'agent';
                $table->foreignId('channel_id')->nullable()->after($afterColumn)->constrained('lookup_values')->nullOnDelete();
            });
        }
        
        // Add policy_code if it doesn't exist
        if (!Schema::hasColumn('policies', 'policy_code')) {
            Schema::table('policies', function (Blueprint $table) {
                $table->string('policy_code')->nullable()->unique()->after('policy_no');
            });
            
            // Generate policy_code from policy_id if policy_id exists
            if (Schema::hasColumn('policies', 'policy_id')) {
                $policies = DB::table('policies')->whereNull('policy_code')->get();
                foreach ($policies as $policy) {
                    $policyCode = $policy->policy_id ?? 'POL' . str_pad($policy->id, 6, '0', STR_PAD_LEFT);
                    DB::table('policies')
                        ->where('id', $policy->id)
                        ->update(['policy_code' => $policyCode]);
                }
            } else {
                // Generate policy_code from id
                $policies = DB::table('policies')->whereNull('policy_code')->get();
                foreach ($policies as $policy) {
                    $policyCode = 'POL' . str_pad($policy->id, 6, '0', STR_PAD_LEFT);
                    DB::table('policies')
                        ->where('id', $policy->id)
                        ->update(['policy_code' => $policyCode]);
                }
            }
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('policies')) {
            return;
        }

        $columnsToDrop = [
            'channel_id',
            'agency_id',
            'pay_plan_lookup_id',
            'frequency_id',
            'business_type_id',
            'policy_status_id',
            'policy_plan_id',
            'policy_class_id',
            'insurer_id',
        ];
        
        Schema::table('policies', function (Blueprint $table) use ($columnsToDrop) {
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('policies', $column)) {
                    try {
                        $table->dropForeign(['policies_' . $column . '_foreign']);
                    } catch (\Exception $e) {
                        // Try alternative foreign key name
                        try {
                            $table->dropForeign([$column]);
                        } catch (\Exception $e2) {
                            // Foreign key might not exist, continue
                        }
                    }
                    $table->dropColumn($column);
                }
            }
            
            if (Schema::hasColumn('policies', 'policy_code')) {
                $table->dropUnique(['policy_code']);
                $table->dropColumn('policy_code');
            }
        });
    }
};
