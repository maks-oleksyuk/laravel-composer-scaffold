<?php

declare(strict_types=1);

namespace MaksOleksyuk\Composer\Plugin\LaravelScaffold;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\Capability\CommandProvider as BaseCommandProvider;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;

final class Plugin implements Capable, EventSubscriberInterface, PluginInterface
{
    protected Composer $composer;

    protected IOInterface $io;

    protected Handler $handler;

    public function activate(Composer $composer, IOInterface $io): void
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    public function deactivate(Composer $composer, IOInterface $io): void {}

    public function uninstall(Composer $composer, IOInterface $io): void {}

    public function getCapabilities(): array
    {
        return [
            BaseCommandProvider::class => CommandProvider::class,
        ];
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PackageEvents::POST_PACKAGE_INSTALL => 'postPackage',
        ];
    }

    public function postPackage(PackageEvent $event): void
    {
        $operation = $event->getOperation();
        $package = $operation->getOperationType() === 'update'
            ? $operation->getTargetPackage()
            : $operation->getPackage();

        if ($package->getName() === 'laravel/framework') {
            $this->handler()->scaffold();
        }
    }

    protected function handler(): Handler
    {
        return $this->handler ??= new Handler($this->composer, $this->io);
    }
}
