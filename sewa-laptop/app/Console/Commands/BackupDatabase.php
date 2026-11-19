<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Backup database to storage/app/backups';

    public function handle()
    {
        $filename = 'backup_' . now()->format('Y-m-d_His') . '.sql';
        $path = storage_path('app/backups/' . $filename);

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $db = config('database.default');
        $config = config("database.connections.{$db}");

        if ($db === 'sqlite') {
            $source = $config['database'];
            if ($source === ':memory:') {
                $this->error('Cannot backup in-memory database.');
                return 1;
            }
            copy($source, $path);
            $this->info("SQLite database backed up to: {$path}");
            return 0;
        }

        // MySQL/PostgreSQL via mysqldump/pg_dump
        $host = $config['host'] ?? '127.0.0.1';
        $port = $config['port'] ?? 3306;
        $database = $config['database'];
        $username = $config['username'];
        $password = $config['password'];

        $command = sprintf(
            'mysqldump -h %s -P %s -u %s -p%s %s > %s',
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($database),
            escapeshellarg($path)
        );

        exec($command, $output, $returnCode);

        if ($returnCode === 0) {
            $this->info("Database backed up to: {$path}");
            return 0;
        }

        $this->error("Backup failed with code {$returnCode}");
        return 1;
    }
}
