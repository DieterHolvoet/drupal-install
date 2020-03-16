<?php

namespace DieterHolvoet\DrupalInstall\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class ScaffoldCommand extends Command
{
    public const COMMAND_NAME = 'scaffold';

    /** @var Filesystem */
    protected $filesystem;

    public function __construct()
    {
        parent::__construct();
        $this->filesystem = new Filesystem();
    }

    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('Sync files to the root of the project requiring this package.')
            ->addArgument('destination', InputArgument::REQUIRED, 'The directory to sync the files to.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $packageRoot = __DIR__ . '/../../..';
        $filesRoot = $packageRoot . '/files';
        $destination = $input->getArgument('destination');

        $this->filesystem->mirror($filesRoot, $destination);
    }
}
