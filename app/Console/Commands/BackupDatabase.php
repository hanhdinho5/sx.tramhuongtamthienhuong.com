<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST');

        $date = date('Y-m-d_H-i-s');
        $fileName = "backup-{$date}.sql";

        $backupPath = storage_path('app/backups');

        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0775, true);
        }

        $path = storage_path("app/backups/{$fileName}");

        $command = "mysqldump -h {$host} -u {$username} -p{$password} {$database} > {$path}";

        $returnVar = null;
        $output = null;

        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            $this->info("Backup successful: {$fileName}");
        } else {
            $this->error("Backup failed");
        }
    }
}
