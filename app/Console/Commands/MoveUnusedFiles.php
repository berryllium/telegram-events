<?php

namespace App\Console\Commands;

use App\Models\MessageFile;
use FilesystemIterator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Move files which are not listed in the DB
 */
class MoveUnusedFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:move-unused-files';

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
        $count = 0;
        $dir = new FilesystemIterator(storage_path('app/public/media'), FilesystemIterator::SKIP_DOTS);

        foreach ($dir as $file) {
            $filename = $file->getFilename();
            if ($file->isDir()) {
                continue;
            }

            $row = MessageFile::query()->where('filename', "public/media/{$filename}")->first();
            if($row === null) {
                Log::info('moving unlinked file', ['file' => $filename, 'count' => $count]);
                $count ++;
                rename($file->getPathname(), storage_path('app/archive/') . $filename);
            }
        }
        dd($count);
    }
}
