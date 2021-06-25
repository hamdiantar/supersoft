<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
class UpdateNullDeleted extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-db:deleted-at-to-null';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $result = DB::select(DB::raw("SHOW TABLES"));
        $key = "Tables_in_".env('DB_DATABASE');
        foreach($result as $table){
            $tableName = $table->$key;
            try {
                DB::table($tableName)->update(['deleted_at' => NULL]);
            } catch (\Exception $e) {
                echo "This table : $tableName isn`t softdeletes".PHP_EOL;
            }
        }
    }
}
