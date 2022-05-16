<?php
declare(strict_types=1);

namespace GitWebhookHandler\Terminal;


class Git
{
    /**
     * Returns an array of errors if found
     * @param CommandResult $result
     * @return array
     */
    public static function catchErrors(CommandResult $result): array
    {
        if (!$result->output) {
            return [];
        }

        $errors = [];

        foreach ($result->output as $line) {
            if (strpos($line, 'error:') === 0) {
                $errors[] = trim(substr($line, 6));
            }
        }

        return $errors;
    }

    public static function catchNoChanges(CommandResult $result): bool
    {
        foreach ($result->output as $line) {
            if (strpos($line, 'Already up to date.') === 0) {
                return true;
            }
        }

        return false;
    }
}