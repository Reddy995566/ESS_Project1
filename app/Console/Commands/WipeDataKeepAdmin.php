<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WipeDataKeepAdmin extends Command
{
    protected $signature = 'db:wipe-keep-admin';
    protected $description = 'Wipe all data except admins and roles';

    public function handle()
    {
        $this->info('Starting database cleanup...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $tables = DB::select('SHOW TABLES');
        $dbName = 'Tables_in_' . env('DB_DATABASE', 'ecom'); // Fallback or strict extraction needed if env is not set correctly, but usually Tables_in_dbname works
        // Better way to get table names independent of DB name key:
        $allTables = [];
        foreach ($tables as $table) {
            foreach ($table as $key => $value) {
                $allTables[] = $value;
            }
        }

        $tablesToKeep = ['admins', 'roles', 'migrations', 'cache', 'sessions', 'jobs', 'failed_jobs'];

        foreach ($allTables as $tableName) {
            if (!in_array($tableName, $tablesToKeep)) {
                $this->line("Truncating: {$tableName}");
                try {
                    DB::table($tableName)->truncate();
                } catch (\Exception $e) {
                    $this->error("Failed to truncate {$tableName}: " . $e->getMessage());
                }
            } else {
                $this->info("Skipping: {$tableName}");
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->info('Database cleared successfully (Admin preserved).');
    }
}
