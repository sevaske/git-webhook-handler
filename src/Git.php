<?php
declare(strict_types=1);

namespace GitWebhookHandler;


use GitWebhookHandler\Terminal\Command;
use RuntimeException;

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
        $this->projectPath = realpath($projectPath);

        if (!file_exists($this->projectPath)) {
            throw new RuntimeException("The project path {$this->projectPath} is not correct.");
        }

        $this->gitAlias = $gitAlias;
        $this->setBranchName();

        if (!$this->branchName) {
            throw new RuntimeException("The branch is undefined.");
        }
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