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

    public function fetch(): Terminal\CommandResult
    {
        return Command::exec("cd {$this->projectPath} && {$this->gitAlias} fetch");
    }

    public function pull(): Terminal\CommandResult
    {
        return Command::exec("cd {$this->projectPath} && {$this->gitAlias} pull origin {$this->branchName}");
    }

    protected function setBranchName(): void
    {
        $commandResult = Command::exec("cd {$this->projectPath} && {$this->gitAlias} branch");

        if (!$commandResult->output) {
            return;
        }

        foreach ($commandResult->output as $branch) {
            if (strpos($branch, '*') === 0) {
                $this->branchName = trim(substr($branch, 2));
                return;
            }
        }
    }
}