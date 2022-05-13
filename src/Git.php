<?php
declare(strict_types=1);

namespace GitWebhookHandler;


use GitWebhookHandler\Terminal\Command;

class Git
{
    /**
     * @var string|null Current branch
     */
    public ?string $branchName;

    /**
     * @var string Full path to the project
     */
    protected string $projectPath;

    /**
     * @var string Full path to the git OR alias
     */
    private string $gitAlias;

    public function __construct(
        string $projectPath,
        string $gitAlias = 'git'
    )
    {
        $this->projectPath = $projectPath;
        $this->gitAlias = $gitAlias;
        $this->setBranchName();
    }

    /**
     * TODO result checking
     */
    public function fetch(): bool
    {
        Command::exec("cd {$this->projectPath} && {$this->gitAlias} fetch");

        return true;
    }

    /**
     * TODO result checking
     */
    public function pull(): bool
    {
        Command::exec("cd {$this->projectPath} && {$this->gitAlias} pull origin {$this->branchName}");

        return true;
    }

    protected function setBranchName(): void
    {
        $commandResult = Command::exec("cd {$this->projectPath} && {$this->gitAlias} branch");
        $branchName = trim($commandResult);
        $this->branchName = $branchName ?: null;
    }
}