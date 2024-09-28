<?php

declare(strict_types=1);

namespace MaksOleksyuk\Composer\Plugin\LaravelScaffold;

use Composer\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Command extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('laravel:scaffold')
            ->setAliases(['scaffold'])
            ->setDescription('Update the Laravel scaffold files.')
            ->setHelp(
                <<<'EOT'
The <info>laravel:scaffold</info> command places the scaffold files in their
respective locations according to the layout stipulated in the composer.json
file.

It is usually not necessary to call <info>laravel:scaffold</info> manually,
because it is called automatically as needed, e.g. after an <info>install</info>
or <info>update</info> command.

EOT
            );

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $handler = new Handler($this->requireComposer(), $this->getIO());
        $handler->scaffold();

        return self::SUCCESS;
    }
}
