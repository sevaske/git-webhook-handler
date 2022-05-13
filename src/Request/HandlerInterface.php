<?php
declare(strict_types=1);

namespace GitWebhookHandler\Request;


interface HandlerInterface
{
    public function getChangedBranches(): array;

    public function isBranchChanged(string $branchName): bool;
}