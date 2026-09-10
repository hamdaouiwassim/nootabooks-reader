<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use MatthiasMullie\Minify\CSS as CssMinifier;
use MatthiasMullie\Minify\JS as JsMinifier;
use Symfony\Component\Finder\Finder;

class MinifyAssets extends Command
{
    protected $signature = 'assets:minify';

    protected $description = 'Generate .min.css/.min.js counterparts for every stylesheet/script in public/assets, served in production only (see asset_min() helper)';

    public function handle(): int
    {
        $this->minifyGroup('css', CssMinifier::class);
        $this->minifyGroup('js', JsMinifier::class);

        return self::SUCCESS;
    }

    private function minifyGroup(string $extension, string $minifierClass): void
    {
        $directory = public_path("assets/{$extension}");

        $files = Finder::create()->files()->in($directory)->name("*.{$extension}")
            ->notName("*.min.{$extension}");

        foreach ($files as $file) {
            $source = $file->getRealPath();
            $target = $file->getPath().'/'.$file->getFilenameWithoutExtension().".min.{$extension}";

            $before = filesize($source);

            (new $minifierClass($source))->minify($target);

            $after = filesize($target);

            $this->line(sprintf(
                '%s -> %s  (%s KB -> %s KB, -%s%%)',
                $file->getFilename(),
                basename($target),
                number_format($before / 1024, 1),
                number_format($after / 1024, 1),
                $before > 0 ? number_format((1 - $after / $before) * 100) : 0
            ));
        }
    }
}
