<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class MigrateSqliteToMysql extends Command
{
    protected $signature = 'app:migrate-sqlite-to-mysql
        {--sqlite= : Absolute path to source sqlite file}
        {--mysql-host=127.0.0.1 : MySQL host}
        {--mysql-port=3306 : MySQL port}
        {--mysql-database=serve_god_db : Target MySQL database name}
        {--mysql-username=root : MySQL username}
        {--mysql-password= : MySQL password}
        {--skip-truncate : Do not truncate target tables before import}';

    protected $description = 'Copy application data from SQLite to MySQL';

    public function handle(): int
    {
        $sqlitePath = $this->option('sqlite') ?: database_path('database.sqlite');

        if (! is_file($sqlitePath)) {
            $this->error("SQLite file not found: {$sqlitePath}");

            return self::FAILURE;
        }

        $host = (string) $this->option('mysql-host');
        $port = (int) $this->option('mysql-port');
        $database = (string) $this->option('mysql-database');
        $username = (string) $this->option('mysql-username');
        $password = (string) $this->option('mysql-password');

        try {
            DB::purge('mysql_target');
            config([
                'database.connections.mysql_target' => [
                    'driver' => 'mysql',
                    'host' => $host,
                    'port' => $port,
                    'database' => $database,
                    'username' => $username,
                    'password' => $password,
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix' => '',
                    'prefix_indexes' => true,
                    'strict' => true,
                    'engine' => null,
                ],
            ]);

            DB::connection('mysql_target')->getPdo();
        } catch (Throwable $exception) {
            $this->error('Unable to connect to MySQL target.');
            $this->line($exception->getMessage());

            return self::FAILURE;
        }

        DB::purge('sqlite_source');
        config([
            'database.connections.sqlite_source' => [
                'driver' => 'sqlite',
                'database' => $sqlitePath,
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
        ]);

        $source = DB::connection('sqlite_source');
        $target = DB::connection('mysql_target');

        $tables = ['users', 'categories', 'tags', 'posts', 'media', 'post_tag'];

        foreach ($tables as $table) {
            if (! Schema::connection('mysql_target')->hasTable($table)) {
                $this->error("Missing table in MySQL: {$table}");
                $this->warn('Run MySQL migrations first, then rerun this command.');

                return self::FAILURE;
            }
        }

        $truncateFirst = ! $this->option('skip-truncate');

        try {
            $target->statement('SET FOREIGN_KEY_CHECKS=0');

            if ($truncateFirst) {
                foreach (array_reverse($tables) as $table) {
                    $target->table($table)->truncate();
                }
            }

            $copied = [];

            foreach ($tables as $table) {
                $rows = $source->table($table)->get()->map(fn ($row) => (array) $row)->all();
                $count = count($rows);

                if ($count === 0) {
                    $copied[$table] = 0;
                    continue;
                }

                foreach (array_chunk($rows, 500) as $chunk) {
                    $target->table($table)->insert($chunk);
                }

                $copied[$table] = $count;
            }

            $target->statement('SET FOREIGN_KEY_CHECKS=1');
        } catch (Throwable $exception) {
            $target->statement('SET FOREIGN_KEY_CHECKS=1');
            $this->error('Migration failed.');
            $this->line($exception->getMessage());

            return self::FAILURE;
        }

        $this->info('SQLite -> MySQL data migration completed.');
        foreach ($copied as $table => $count) {
            $this->line(" - {$table}: {$count}");
        }

        return self::SUCCESS;
    }
}

