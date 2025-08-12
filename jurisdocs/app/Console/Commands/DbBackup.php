<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SqlBackup;

class DbBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Database Backup';

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
        
        $sqlBackup = new SqlBackup();
        
        $filename = "backup_".strtotime(now()).".sql";
                
        $server_name = env("DB_HOST");
        $database_name = env("DB_DATABASE"); 
        $username = env("DB_USERNAME");
        $password = env("DB_PASSWORD");
        
        $cmd = "mysqldump --user=".env("DB_USERNAME")." --password=".env("DB_PASSWORD")." --host=".env("DB_HOST")." ".
                env("DB_DATABASE")." > ".storage_path('app/public/' . $filename);
        exec($cmd);
        
        $sqlBackup->file_path = $filename;
        $sqlBackup->data = date('Y-m-d H:i:s');
        $sqlBackup->save();
        
        unset($sqlBackup);
        
    }
}
