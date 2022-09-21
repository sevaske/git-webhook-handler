<?php

declare(strict_types=1);

namespace GitWebhookHandler\Request;

use GitWebhookHandler\Git\Author;

class GithubHandler extends AbstractHandler
{
    public array $changes = [];
    public array $changedBranches = [];
    public array $authors = [];

    public function __construct(string $requestContent)
    {
        parent::__construct($requestContent);

        if (!isset($this->request->{'ref'})) {
            return;
        }

        //getting branch name
        $matches = [];
        if (preg_match("/refs\/(tags|heads)\/(.*)/", $this->request->{'ref'}, $matches)) {
            $this->changedBranches[] = $matches[2];
        }

        // changes authors
        if (isset($this->request->{'head_commit'}->author)) {
            $nickname = $this->request->{'head_commit'}->author->username;
            $name = $this->request->{'head_commit'}->author->username;
            $email = trim($this->request->{'head_commit'}->author->email);

            if (!(filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email))) {
                $email = null;
            }

            $this->authors[] = (new Author($name, $email, $nickname));
        }

        $this->authors = array_unique($this->authors);
    }

    public function getChangedBranches(): array
    {
        return $this->changedBranches;
    }
}
