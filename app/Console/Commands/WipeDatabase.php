<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WipeDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:wipe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate all tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Schema::disableForeignKeyConstraints();

        $tables = DB::select('SHOW TABLES');
        $dbName = 'Tables_in_' . DB::getDatabaseName();
    
        foreach ($tables as $table) {
            DB::table($table->$dbName)->truncate();
        }
    
        Schema::enableForeignKeyConstraints();
    
        $this->info('Database wiped!');
    }
}
