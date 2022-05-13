<?php
declare(strict_types=1);

namespace GitWebhookHandler\Terminal;


class Command
{
    final private function __construct(){}
    final private function __clone(){}

    public static function exec(string $command): string
    {
        return exec($command);
    }

    public static function outputExec(string $command): CommandResult
    {
        exec("{$command} 2>&1", $output, $code);

        return (new CommandResult($output, $code));
    }
}