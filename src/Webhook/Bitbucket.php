<?php


namespace GitWebhookHandler\Webhook;


use GitWebhookHandler\Git;
use GitWebhookHandler\Request\AbstractHandler;
use GitWebhookHandler\Request\BitbucketHandler;

class Bitbucket
{
    public AbstractHandler $requestHandler;
    public Git $git;
    public string $projectPath;
    public array $errors = [];

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