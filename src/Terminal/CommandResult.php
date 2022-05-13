<?php
declare(strict_types=1);

namespace GitWebhookHandler\Terminal;


class CommandResult
{
    public ?array $output;
    public ?int $code;

    public function __construct(?array $output, ?int $code)
    {
        $this->output = $output;
        $this->code = $code;
    }
}