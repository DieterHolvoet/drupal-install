<?php

namespace DieterHolvoet\DrupalInstall\Console\Command;

use Composer\Composer;
use Composer\Semver\Comparator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Checks if the installed version of Composer is compatible.
 *
 * Composer 1.0.0 and higher consider a `composer install` without having a
 * lock file present as equal to `composer update`. We do not ship with a lock
 * file to avoid merge conflicts downstream, meaning that if a project is
 * installed with an older version of Composer the scaffolding of Drupal will
 * not be triggered. We check this here instead of in drupal-scaffold to be
 * able to give immediate feedback to the end user, rather than failing the
 * installation after going through the lengthy process of compiling and
 * downloading the Composer dependencies.
 *
 * @see https://github.com/composer/composer/pull/5035
 */
class CheckComposerVersionCommand extends Command
{
    public const COMMAND_NAME = 'check-composer-version';

    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('Checks if the installed version of Composer is compatible.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $version = Composer::VERSION;

        // The dev-channel of composer uses the git revision as version number,
        // try to the branch alias instead.
        if (preg_match('/^[0-9a-f]{40}$/i', $version)) {
            $version = Composer::BRANCH_ALIAS_VERSION;
        }

        // If Composer is installed through git we have no easy way to determine if
        // it is new enough, just display a warning.
        if ($version === '@package_version@' || $version === '@package_branch_alias_version@') {
            $io->error('<warning>You are running a development version of Composer. If you experience problems, please update Composer to the latest stable version.</warning>');
        } elseif (Comparator::lessThan($version, '1.0.0')) {
            $io->error('<error>Drupal-project requires Composer version 1.0.0 or higher. Please update your Composer before continuing</error>.');
            exit(1);
        }
    }
}
