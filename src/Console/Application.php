<?php

namespace DieterHolvoet\DrupalInstall\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Wieni\wmcodestyle\Console\Command\ScaffoldCommand;

class Application extends BaseApplication
{
    public const VERSION = '1.0.0';

    public function __construct()
    {
        parent::__construct('drupal-install', self::VERSION);

        $this->add(new ScaffoldCommand());
    }
}
