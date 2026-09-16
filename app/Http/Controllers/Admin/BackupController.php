<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class BackupController extends Controller
{
    public function index(): View
    {
        return view('admin.backup', ['activeNav' => 'backup']);
    }

    /**
     * Builds one zip containing a full SQL dump of the database plus every
     * uploaded file (covers, writer photos, book PDFs) and streams it back
     * as a download. Runs synchronously in the request — there's no queue
     * worker in production to hand this off to — so for a very large
     * library this can take a while and, on some hosts, may hit a
     * webserver-level timeout outside PHP's own control (set_time_limit(0)
     * only lifts PHP's own limit, not nginx/Apache's).
     */
    public function create(): BinaryFileResponse|RedirectResponse
    {
        if (! class_exists(ZipArchive::class)) {
            return back()->withErrors(['backup' => 'إضافة PHP الخاصة بضغط الملفات (zip) غير مفعّلة على الخادم، تواصل مع مزود الاستضافة.']);
        }

        set_time_limit(0);

        $backupDir = storage_path('app/private/backups');
        if (! is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $timestamp = now()->format('Y-m-d_His');
        $sqlPath = "{$backupDir}/database-{$timestamp}.sql";
        $zipPath = "{$backupDir}/nootabooks-backup-{$timestamp}.zip";

        try {
            $this->dumpDatabase($sqlPath);
            $this->buildZip($zipPath, $sqlPath);
        } finally {
            if (is_file($sqlPath)) {
                unlink($sqlPath);
            }
        }

        return response()->download($zipPath, "nootabooks-backup-{$timestamp}.zip")
            ->deleteFileAfterSend(true);
    }

    /**
     * A pure-PHP mysqldump equivalent (no shelling out to the mysqldump
     * binary, which shared hosting often doesn't expose) — writes straight
     * to a file handle, one table at a time, so memory use stays bounded to
     * roughly one table's worth of rows rather than the whole database.
     */
    private function dumpDatabase(string $path): void
    {
        $handle = fopen($path, 'w');

        fwrite($handle, "-- نوته بوك database backup\n-- Generated: ".now()->toDateTimeString()."\n\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\nSET NAMES utf8mb4;\n\n");

        $pdo = DB::connection()->getPdo();

        // Scope explicitly to this app's own database — Schema::getTableListing()
        // with no schema argument lists tables across every database the DB
        // user can see on the server (everything except the built-in MySQL
        // system schemas), which on shared hosting can include unrelated
        // sites' databases if the same DB user has grants on more than one.
        $database = DB::connection()->getDatabaseName();
        $tables = Schema::getTableListing($database, schemaQualified: false);

        foreach ($tables as $table) {
            $createRow = (array) DB::selectOne("SHOW CREATE TABLE `{$table}`");
            $createSql = $createRow['Create Table'] ?? reset($createRow);

            fwrite($handle, "-- ----------------------------\n-- Table: {$table}\n-- ----------------------------\n");
            fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n{$createSql};\n\n");

            $rows = DB::table($table)->get();

            foreach ($rows as $row) {
                $row = (array) $row;
                $columns = '`'.implode('`, `', array_keys($row)).'`';
                $values = implode(', ', array_map(
                    fn ($value) => $value === null ? 'NULL' : $pdo->quote((string) $value),
                    $row
                ));

                fwrite($handle, "INSERT INTO `{$table}` ({$columns}) VALUES ({$values});\n");
            }

            fwrite($handle, "\n");
            unset($rows);
        }

        fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
        fclose($handle);
    }

    /**
     * Zips the SQL dump plus every file under the public storage disk
     * (covers/, writers/, books/ — whatever exists there, uploaded content
     * only, never the app's own static assets in public/assets).
     */
    private function buildZip(string $zipPath, string $sqlPath): void
    {
        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFile($sqlPath, 'database.sql');

        $filesRoot = storage_path('app/public');

        if (is_dir($filesRoot)) {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($filesRoot, RecursiveDirectoryIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                if ($file->isDir()) {
                    continue;
                }

                $relative = substr($file->getPathname(), strlen($filesRoot) + 1);
                $zip->addFile($file->getPathname(), 'files/'.str_replace('\\', '/', $relative));
            }
        }

        $zip->close();
    }
}
