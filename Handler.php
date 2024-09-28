<?php

declare(strict_types=1);

namespace MaksOleksyuk\Composer\Plugin\LaravelScaffold;

use Composer\Composer;
use Composer\IO\IOInterface;

final class Handler
{
    protected array $files = [
        'artisan',
        '.editorconfig',
        'public/.htaccess',
        'public/index.php',
        'public/favicon.ico',
        'public/robots.txt',
        'storage/app/.gitignore',
        'storage/app/public/.gitignore',
        'storage/app/private/.gitignore',
        'storage/framework/cache/.gitignore',
        'storage/framework/cache/data/.gitignore',
        'storage/framework/sessions/.gitignore',
        'storage/framework/testing/.gitignore',
        'storage/framework/views/.gitignore',
        'storage/framework/.gitignore',
        'storage/logs/.gitignore',
    ];

    public function __construct(
        protected readonly Composer $composer,
        protected readonly IOInterface $io
    ) {}

    /**
     * Copies all scaffold files from Laravel repo.
     */
    public function scaffold(): void
    {
        $extra = $this->composer->getPackage()->getExtra();
        $installedPackages = $this->composer->getRepositoryManager()->getLocalRepository()->getPackages();
        $laravelFrameworkPackage = current(array_filter($installedPackages, fn ($package) => $package->getName() === 'laravel/framework'));

        if (! $laravelFrameworkPackage) {
            $packageName = $this->composer->getPackage()->getName();
            $this->io->write("Laravel framework package not found. Is this package (<fg=yellow>$packageName</>) really needed here?");

            return;
        }

        if (isset($extra['laravel-scaffold'], $extra['laravel-scaffold']['files'])) {
            $this->files = $extra['laravel-scaffold']['files'];
        }

        $version = (int) strtok($laravelFrameworkPackage->getVersion(), '.');
        $this->io->write('Scaffolding files from <fg=yellow>laravel/laravel</>:');
        $rootPath = $this->composer->getConfig()->get('vendor-dir').'/../';

        foreach ($this->files as $file) {
            $destination = $rootPath.$file;
            $url = "https://raw.githubusercontent.com/laravel/laravel/$version.x/$file";

            try {
                $remoteFileContents = file_get_contents($url);
            } catch (\Exception) {
                $this->io->warning(" - File <bg=yellow;fg=black;options=bold>$file</> not found in laravel repository");

                continue;
            }

            if (! file_exists($destination)) {
                $directory = dirname($destination);
                if (! is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }
            }

            if (! file_exists($destination)) {
                file_put_contents($destination, $remoteFileContents);
                $this->io->write(" - Download <fg=green>$file</> from <fg=green>laravel/$version.x/$file</>");

                continue;
            }

            $localFileContents = file_get_contents($destination);

            if ($localFileContents === $remoteFileContents) {
                $this->io->write(" - Skip     <fg=green>$file</> from <fg=green>laravel/$version.x/$file</>");
            } else {
                file_put_contents($destination, $remoteFileContents);
                $this->io->write(" - Update   <fg=green>$file</> from <fg=green>laravel/$version.x/$file</>");
            }
        }
    }
}
