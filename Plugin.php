<?php

declare(strict_types=1);

namespace MaksOleksyuk\Composer\Plugin\LaravelScaffold;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\Capability\CommandProvider as BaseCommandProvider;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

final class Plugin implements Capable, EventSubscriberInterface, PluginInterface
{

    protected Composer $composer;

    protected IOInterface $io;

    /**
     * {@inheritdoc}
     */
    public function activate(Composer $composer, IOInterface $io): void
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    /**
     * {@inheritdoc}
     */
    public function deactivate(Composer $composer, IOInterface $io): void {}

    /**
     * {@inheritdoc}
     */
    public function uninstall(Composer $composer, IOInterface $io): void {}

    /**
     * {@inheritdoc}
     */
    public function getCapabilities(): array
    {
        return [
            BaseCommandProvider::class => CommandProvider::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ScriptEvents::POST_AUTOLOAD_DUMP => 'postInstall',
        ];
    }

    public function postInstall(Event $event): void
    {
        $handler = new Handler($this->composer, $this->io);
        $handler->scaffold();
    }

}
