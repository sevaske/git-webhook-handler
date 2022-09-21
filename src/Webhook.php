<?php


namespace GitWebhookHandler;


use GitWebhookHandler\Git;
use GitWebhookHandler\Request\AbstractHandler;

class Webhook
{
    public AbstractHandler $requestHandler;
    public Git $git;
    public string $projectPath;
    public array $errors = [];

    public function __construct(
        AbstractHandler $requestHandler,
        string $projectPath,
        string $gitAlias = 'git'
    )
    {
        $this->projectPath = $projectPath;
        $this->git = new Git($projectPath, $gitAlias);
        $this->requestHandler = $requestHandler;
    }

    /**
     * @return bool|null Returns null if no changes
     */
    public function handlePull(): ?bool
    {
        if (!$this->requestHandler->isBranchChanged($this->git->branchName)) {
            return null;
        }

        $this->git->fetch();
        $pullResult = $this->git->pull();

        if (\GitWebhookHandler\Terminal\Git::catchNoChanges($pullResult)) {
            return null;
        }

        $errors = \GitWebhookHandler\Terminal\Git::catchErrors($pullResult);

        if ($errors) {
            array_push($this->errors, ...$errors);
            return false;
        }

        return true;
    }
}