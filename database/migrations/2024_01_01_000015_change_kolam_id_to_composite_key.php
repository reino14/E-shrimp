<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Drop all foreign keys that reference kolam_id
        // We need to drop them before changing the primary key
        
        // Drop foreign key from monitoring_sessions
        if (Schema::hasTable('monitoring_sessions')) {
            try {
                Schema::table('monitoring_sessions', function (Blueprint $table) {
                    $table->dropForeign(['kolam_id']);
                });
            } catch (\Exception $e) {
                // Try with constraint name
                try {
                    DB::statement('ALTER TABLE monitoring_sessions DROP FOREIGN KEY monitoring_sessions_kolam_id_foreign');
                } catch (\Exception $e2) {
                    // Constraint might not exist or have different name
                }
            }
        }

        // Drop foreign key from sensor_data
        if (Schema::hasTable('sensor_data')) {
            try {
                Schema::table('sensor_data', function (Blueprint $table) {
                    $table->dropForeign(['kolam_id']);
                });
            } catch (\Exception $e) {
                try {
                    DB::statement('ALTER TABLE sensor_data DROP FOREIGN KEY sensor_data_kolam_id_foreign');
                } catch (\Exception $e2) {
                    // Constraint might not exist or have different name
                }
            }
        }

        // Drop foreign key from notifikasis
        if (Schema::hasTable('notifikasis')) {
            try {
                Schema::table('notifikasis', function (Blueprint $table) {
                    $table->dropForeign(['kolam_id']);
                });
            } catch (\Exception $e) {
                try {
                    DB::statement('ALTER TABLE notifikasis DROP FOREIGN KEY notifikasis_kolam_id_foreign');
                } catch (\Exception $e2) {
                    // Constraint might not exist or have different name
                }
            }
        }

        // Drop foreign key from thresholds
        if (Schema::hasTable('thresholds')) {
            try {
                Schema::table('thresholds', function (Blueprint $table) {
                    $table->dropForeign(['kolam_id']);
                });
            } catch (\Exception $e) {
                try {
                    DB::statement('ALTER TABLE thresholds DROP FOREIGN KEY thresholds_kolam_id_foreign');
                } catch (\Exception $e2) {
                    // Constraint might not exist or have different name
                }
            }
        }

        // Step 2: Drop existing primary key constraint
        Schema::table('dashboard_monitorings', function (Blueprint $table) {
            $table->dropPrimary(['kolam_id']);
        });

        // Step 3: Add id column as new primary key
        Schema::table('dashboard_monitorings', function (Blueprint $table) {
            $table->id()->first();
        });

        // Step 4: Add unique constraint on (kolam_id, email_peternak)
        Schema::table('dashboard_monitorings', function (Blueprint $table) {
            $table->unique(['kolam_id', 'email_peternak'], 'kolam_peternak_unique');
        });

        // Note: We don't recreate foreign keys because kolam_id is no longer unique by itself
        // Foreign keys would need to reference the composite unique key (kolam_id, email_peternak)
        // but that would require adding email_peternak to all referencing tables
        // For now, we rely on application-level enforcement
    }

    public function down(): void
    {
        // Remove unique constraint
        Schema::table('dashboard_monitorings', function (Blueprint $table) {
            $table->dropUnique('kolam_peternak_unique');
        });

        // Drop id column
        Schema::table('dashboard_monitorings', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        // Restore original primary key
        Schema::table('dashboard_monitorings', function (Blueprint $table) {
            $table->primary('kolam_id');
        });

        // Recreate foreign keys
        Schema::table('monitoring_sessions', function (Blueprint $table) {
            $table->foreign('kolam_id')->references('kolam_id')->on('dashboard_monitorings')->onDelete('cascade');
        });

        Schema::table('sensor_data', function (Blueprint $table) {
            $table->foreign('kolam_id')->references('kolam_id')->on('dashboard_monitorings')->onDelete('set null');
        });

        Schema::table('notifikasis', function (Blueprint $table) {
            $table->foreign('kolam_id')->references('kolam_id')->on('dashboard_monitorings')->onDelete('cascade');
        });

        Schema::table('thresholds', function (Blueprint $table) {
            $table->foreign('kolam_id')->references('kolam_id')->on('dashboard_monitorings')->onDelete('cascade');
        });
    }
};

