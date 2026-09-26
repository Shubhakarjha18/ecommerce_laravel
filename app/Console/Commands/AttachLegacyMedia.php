<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Post;
use App\Models\Settings;
use App\User;

/**
 * Associates every existing (legacy) image file on disk with its owner row
 * in the Spatie media library. Old rows keep the path in the `photo`/`logo`
 * column and have no `media` record yet — this command creates the missing
 * association. The original files are preserved and stay as fallback.
 */
class AttachLegacyMedia extends Command
{
    protected $signature = 'media:attach-legacy {--dry-run : Show what would be imported without writing}';

    protected $description = 'Associate existing legacy image files with their models in the Spatie media library';

    public function handle(): int
    {
        $map = [
            Banner::class   => ['photo' => 'photo'],
            Category::class => ['photo' => 'photo'],
            Product::class  => ['photo' => 'photo'],
            Post::class     => ['photo' => 'photo'],
            Settings::class => ['photo' => 'photo', 'logo' => 'logo'],
            User::class     => ['photo' => 'photo'],
        ];

        $dryRun = (bool)$this->option('dry-run');
        $totalAttached = 0;
        $totalSkipped = 0;

        foreach ($map as $modelClass => $columns) {
            foreach ($columns as $column => $collection) {
                $rows = $modelClass::all();
                $attached = 0;
                $skipped = 0;

                foreach ($rows as $model) {
                    if ($model->getMedia($collection)->isNotEmpty()) {
                        $skipped++;
                        continue; // already associated with Spatie
                    }

                    $absolutePath = $this->resolveAbsolutePath($model->getRawOriginal($column) ?? $model->getAttributes()[$column] ?? null);

                    if ($absolutePath === null) {
                        continue; // no legacy image on this row
                    }

                    if (!is_file($absolutePath)) {
                        $this->warn(sprintf(
                            '%s #%d: file not found on disk: %s',
                            class_basename($modelClass), $model->id, $absolutePath
                        ));
                        $skipped++;
                        continue;
                    }

                    if (!$dryRun) {
                        $model->addMedia($absolutePath)
                            ->preservingOriginal()
                            ->usingFileName(basename($absolutePath))
                            ->toMediaCollection($collection);
                    }

                    $attached++;
                }

                $label = class_basename($modelClass).'.'.$collection;
                $this->line(sprintf('%s: %d attached, %d skipped%s',
                    $label, $attached, $skipped, $dryRun ? ' (dry run)' : ''
                ));

                $totalAttached += $attached;
                $totalSkipped += $skipped;
            }
        }

        $this->info($dryRun
            ? "Dry run complete. {$totalAttached} file(s) would be associated, {$totalSkipped} row(s) skipped."
            : "Done. {$totalAttached} file(s) associated with Spatie, {$totalSkipped} row(s) skipped.");

        return 0;
    }

    /**
     * Turns a legacy column value (/storage/photos/1/x.jpg, photos/1/x.jpg,
     * a full URL, ...) into an absolute path on disk, or null when unknown.
     */
    private function resolveAbsolutePath(?string $value): ?string
    {
        $value = trim((string)$value);
        if ($value === '') {
            return null;
        }

        // Full URL: keep only the path part.
        if (Str::startsWith($value, ['http://', 'https://'])) {
            $path = parse_url($value, PHP_URL_PATH);
            if (!is_string($path) || $path === '') {
                return null;
            }
            $value = ltrim($path, '/');
        } else {
            $value = ltrim($value, '/');
        }

        // "/storage/..." is served from storage/app/public.
        $candidates = [];
        if (Str::startsWith($value, 'storage/')) {
            $candidates[] = storage_path('app/public/'.substr($value, strlen('storage/')));
        }
        $candidates[] = storage_path('app/public/'.$value);
        $candidates[] = public_path($value);

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return $candidates[0];
    }
}
