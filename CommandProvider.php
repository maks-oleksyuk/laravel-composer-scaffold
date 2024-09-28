<?php

declare(strict_types=1);

namespace MaksOleksyuk\Composer\Plugin\LaravelScaffold;

use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;

/**
 * List of all commands provided by this package.
 */
final class CommandProvider implements CommandProviderCapability
{
    /**
     * {@inheritdoc}
     */
    public function getCommands(): array
    {
        return [new Command];
    }
}
