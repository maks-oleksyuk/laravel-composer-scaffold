<?php

declare(strict_types=1);

namespace MaksOleksyuk\Composer\Plugin\LaravelScaffold;

use Composer\Composer;
use Composer\IO\IOInterface;

final readonly class Handler
{
    public function __construct(
        protected Composer $composer,
        protected IOInterface $io
    ) {}

    /**
     * Copies all scaffold files from source to destination.
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

        $version = (int) strtok($laravelFrameworkPackage->getVersion(), '.');

        if (isset($extra['laravel-scaffold'], $extra['laravel-scaffold']['files'])) {
            $files = $extra['laravel-scaffold']['files'];
            $this->io->write('Scaffolding files from <fg=yellow>laravel/laravel</>:');
            $rootPath = $this->composer->getConfig()->get('vendor-dir').'/../';

            foreach ($files as $file) {

                $destination = $rootPath.$file;
                $url = "https://raw.githubusercontent.com/laravel/laravel/$version.x/$file";
                $remoteFileContents = file_get_contents($url);

                if (! file_exists($destination)) {
                    $directory = dirname($destination);
                    if (! is_dir($directory)) {
                        mkdir($directory, 0755, true);
                    }
                }

                if (! file_exists($destination)) {
                    file_put_contents($destination, $remoteFileContents);
                    $this->io->write(" - Download <fg=green>$file</> from <fg=green>laravel/$version.x/$file</>");
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
}
