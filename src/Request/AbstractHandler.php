<?php
declare(strict_types=1);

namespace GitWebhookHandler\Request;


abstract class AbstractHandler implements HandlerInterface
{
    public object $request;

    /**
     * @param string $requestContent JSON Format
     */
    public function __construct(string $requestContent)
    {
        $this->request = json_decode($requestContent, false);
    }

    public function isBranchChanged(string $branchName): bool
    {
        return in_array($branchName, $this->getChangedBranches(), true);
    }
}