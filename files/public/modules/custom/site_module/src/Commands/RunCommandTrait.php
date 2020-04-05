<?php

namespace Drupal\site_module\Commands;

use Consolidation\SiteAlias\SiteAlias;
use Consolidation\SiteAlias\SiteAliasManagerAwareTrait;
use Consolidation\SiteAlias\SiteAliasManagerInterface;
use Consolidation\SiteProcess\ProcessManagerAwareTrait;
use Consolidation\SiteProcess\Util\ArgumentProcessor;
use Drush\SiteAlias\ProcessManager;
use RuntimeException;

/**
 * Provides a helper function to run another Drush command.
 *
 * @property ProcessManager $processManager
 * @property SiteAliasManagerInterface $siteAliasManager
 */
trait RunCommandTrait
{
    use SiteAliasManagerAwareTrait;
    use ProcessManagerAwareTrait;

    protected function drush(string $command, array $options = [], array $arguments = [])
    {
        $alias = $this->siteAliasManager()->getSelf();
        $process = $this->processManager->drush($alias, $command, $arguments, $options + ['yes' => true]);

        try {
            $process->setTty(true);
        } catch (RuntimeException $e) {
            // At least we tried ¯\_(ツ)_/¯
        }

        $process->realtimeStdout()->writeln(
            $this->buildCommandString($alias, $command, $options, $arguments)
        );

        $process->mustRun($process->showRealtime());
    }

    protected function buildCommandString(SiteAlias $alias, string $command, array $options = [], array $arguments = []): string
    {
        $processor = new ArgumentProcessor;

        return sprintf(
            '<comment>> %s %s</comment>',
            $command,
            implode(' ', $processor->selectArgs($alias, $arguments, $options))
        );
    }
}
