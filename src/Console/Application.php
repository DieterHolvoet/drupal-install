<?php

namespace DieterHolvoet\DrupalInstall\Console;

use DieterHolvoet\DrupalInstall\Console\Command\CheckComposerVersionCommand;
use DieterHolvoet\DrupalInstall\Console\Command\CreateCommand;
use DieterHolvoet\DrupalInstall\Console\Command\ScaffoldCommand;
use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    public const VERSION = '1.0.0';

    public function __construct()
    {
        parent::__construct('drupal-install', self::VERSION);

        $this->add(new CheckComposerVersionCommand());
        $this->add(new CreateCommand());
        $this->add(new ScaffoldCommand());
    }
}
