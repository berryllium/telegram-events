<?php

namespace App\Console\Commands;

use App\Models\Place;
use App\Models\PlaceFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MoveFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:move-files';

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
        // move place images
        Place::query()->where(function ($q) {
            $q->whereNotNull('logo_image')
                ->orWhereNotNull('image')
                ->orWhereNotNull('appeal_image');
        })->chunkById(5, function ($chunk) {
            foreach ($chunk as $place) {
                if ($place->image) {
                    $basename = basename($place->image);
                    $new_path = "public/media/places/{$place->id}/{$basename}";
                    Storage::copy($place->image, $new_path);
                    $place->image = $new_path;
                }

                if ($place->logo_image) {
                    $basename = basename($place->logo_image);
                    $new_path = "public/media/places/{$place->id}/{$basename}";
                    Storage::copy($place->logo_image, $new_path);
                    $place->logo_image = $new_path;
                }

                if ($place->appeal_image) {
                    $basename = basename($place->appeal_image);
                    $new_path = "public/media/places/{$place->id}/{$basename}";
                    Storage::copy($place->appeal_image, $new_path);
                    $place->appeal_image = $new_path;
                }

                $place->update();

                $sliders = $place->sliders;

                foreach ($sliders as $slider) {
                    foreach ($slider->slides as $slide) {
                        if ($slide->filename) {
                            $basename = basename($slide->filename);
                            $new_path = "public/media/places/{$place->id}/{$basename}";
                            Storage::copy($slide->filename, $new_path);
                            $slide->filename = $new_path;
                            $slide->update();
                        }
                    }
                }
            }
        });
    }
}
