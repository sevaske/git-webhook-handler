<?php
declare(strict_types=1);

namespace GitWebhookHandler\Git;


class Author
{
    public string $name;
    public ?string $email;
    public ?string $nickname;

    public function __construct(string $name, string $email, ?string $nickname)
    {
        $this->name = $name;
        $this->email = self::filterEmail($email);
        $this->nickname = $nickname;
    }

    public function __toString()
    {
        return "Name: {$this->name}, Email: {$this->email}, Nickname: {$this->nickname}";
    }

    public static function filterEmail(string $email): ?string
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email)) {
            return $email;
        }

        return null;
    }
}