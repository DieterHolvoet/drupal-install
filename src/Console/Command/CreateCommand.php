<?php

namespace DieterHolvoet\DrupalInstall\Console\Command;

use Composer\Composer;
use Composer\Installer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

class CreateCommand extends Command
{
    public const COMMAND_NAME = 'create';

    /** @var InputInterface */
    protected $input;
    /** @var OutputInterface */
    protected $output;
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
            ->setDescription('Create a new Drupal install.')
            ->addArgument('destination', InputArgument::REQUIRED, 'The directory to sync the files to.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = new SymfonyStyle($input, $output);

        $destination = $input->getArgument('destination');
        if (!$this->filesystem->exists($destination)) {
            $destination = implode(DIRECTORY_SEPARATOR, [getcwd(), $destination]);
        }

        $this->command('check-composer-version');
        $this->output->success('Composer is up to date.');

        $this->output->section('Copying scaffold files...');
        $this->command('scaffold', ['destination' => $destination]);

        $this->output->section('Installing dependencies...');
        exec("cd $destination; composer install");
    }

    protected function command(string $name, array $arguments = [])
    {
        $command = $this->getApplication()->find($name);
        $input = new ArrayInput($arguments);

        return $command->run($input, $this->output);
    }
}
