<?php

namespace Drupal\site_module\Commands;

use Consolidation\SiteAlias\SiteAliasManagerAwareInterface;
use Drush\Commands\DrushCommands;
use Symfony\Component\Process\Exception\ProcessFailedException;

class FreshCommands extends DrushCommands implements SiteAliasManagerAwareInterface
{
    use RunCommandTrait;

    /**
     * Bring your site up to date with the latest file changes
     *
     * @command fresh
     * @aliases fr
     * @bootstrap none
     */
    public function fresh()
    {
        try {
            $this->drush('cache-rebuild');
            $this->drush('updatedb', ['no-post-updates' => true]);
            $this->drush('config-import');
            $this->drush('updatedb');
            $this->drush('cache-rebuild');
        } catch (ProcessFailedException $e) {
            return $e->getProcess()->getExitCode();
        }
    }

    /**
     * Bring your site up to date with the latest file changes
     * and export any new configuration from module update hooks.
     *
     * @command post-update
     * @aliases pu
     * @bootstrap none
     */
    public function postUpdate()
    {
        try {
            $this->drush('cache-rebuild');
            $this->drush('updatedb');
            $this->drush('config-export');
            $this->drush('cache-rebuild');
        } catch (ProcessFailedException $e) {
            return $e->getProcess()->getExitCode();
        }
    }
}
