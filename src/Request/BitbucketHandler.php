<?php
declare(strict_types=1);

namespace GitWebhookHandler\Request;


use GitWebhookHandler\Git\Author;

class BitbucketHandler extends AbstractHandler
{
    protected const REQUEST_PUSH = 'push';
    protected const REQUEST_CHANGES = 'changes';
    protected const REQUEST_CHANGES_OLD = 'old';
    protected const REQUEST_CHANGES_NEW = 'new';

    public array $changes = [];
    public array $changedBranches = [];
    public array $authors = [];

    public function __construct(string $requestContent)
    {
        parent::__construct($requestContent);

        if (!isset($this->request->{self::REQUEST_PUSH}->{self::REQUEST_CHANGES})) {
            return;
        }

        $this->changes = (array)$this->request->{self::REQUEST_PUSH}->{self::REQUEST_CHANGES};

        foreach ($this->changes as $change) {
            if (isset($change->{self::REQUEST_CHANGES_NEW})) {
                $this->changedBranches[] = $change->{self::REQUEST_CHANGES_NEW}->name;

                // changes authors
                if (isset($change->{self::REQUEST_CHANGES_NEW}->target->author)) {
                    $nickname = $change->{self::REQUEST_CHANGES_NEW}->target->author->user->nickname;
                    $raw = $change->{self::REQUEST_CHANGES_NEW}->target->author->raw;
                    $rawExplode = explode(' <', $raw);

                    if ($rawExplode) {
                        $name = trim($rawExplode[0]);
                        $email = trim(substr($rawExplode[1], 0, -1));

                        if (!(filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email))) {
                            $email = null;
                        }

                        $this->authors[] = (new Author($name, $email, $nickname));
                    }
                }
            }
        }

        $this->authors = array_unique($this->authors);
    }

    public function getChangedBranches(): array
    {
        return $this->changedBranches;
    }
}