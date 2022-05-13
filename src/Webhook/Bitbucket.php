<?php


namespace GitWebhookHandler\Webhook;


use GitWebhookHandler\Git;
use GitWebhookHandler\Request\AbstractHandler;
use GitWebhookHandler\Request\BitbucketHandler;

class Bitbucket
{
    protected string $projectPath;
    protected AbstractHandler $requestHandler;
    protected Git $git;

    public function __construct(
        string $requestContent,
        string $projectPath,
        string $gitAlias
    )
    {
        $this->projectPath = $projectPath;
        $this->git = new Git($projectPath, $gitAlias);
        $this->requestHandler = new BitbucketHandler($requestContent);
    }

    /**
     * @return bool|null Returns null if no changes
     */
    public function handlePull(): ?bool
    {
        if (!$this->requestHandler->isBranchChanged($this->git->branchName)) {
            return null;
        }

        return $this->git->fetch() && $this->git->pull();
    }
}