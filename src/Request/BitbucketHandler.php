<?php
declare(strict_types=1);

namespace GitWebhookHandler\Request;


class BitbucketHandler extends AbstractHandler
{
    protected const REQUEST_PUSH = 'push';
    protected const REQUEST_CHANGES = 'changes';
    protected const REQUEST_CHANGES_OLD = 'old';
    protected const REQUEST_CHANGES_NEW = 'new';

    public function getChangedBranches(): array
    {
        if (!isset($this->request->{self::REQUEST_PUSH}->{self::REQUEST_CHANGES})) {
            return [];
        }

        $branches = [];

        foreach ($this->request->{self::REQUEST_PUSH}->{self::REQUEST_CHANGES} as $change) {
            if (isset($change->{self::REQUEST_CHANGES_NEW})) {
                $branches[] = $change->{self::REQUEST_CHANGES_NEW}->name;
            }
        }

        return $branches;
    }
}