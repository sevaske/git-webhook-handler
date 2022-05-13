<?php
declare(strict_types=1);

namespace GitWebhookHandler\Terminal;


class Command
{
    public static function exec(string $command): CommandResult
    {
        exec("{$command} 2>&1", $output, $code);

        return (new CommandResult($output, $code));
    }
}