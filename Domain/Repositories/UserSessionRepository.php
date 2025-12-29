<?php

declare(strict_types=1);

namespace Domain\Repositories;

use Domain\Models\User;

class UserSessionRepository
{
    private const USER_SESSION_KEY = 'user';
    private const USER_NAME_SESSION_KEY = 'user_name';

    /** @var array<string,mixed> */
    private $session;

    public function __construct(array &$session)
    {
        $this->session = &$session;
    }

    public function getCurrent(): ?User
    {
        $id = $this->session[self::USER_SESSION_KEY] ?? null;
        if ($id === null || $id === '') {
            return null;
        }
        $name = $this->session[self::USER_NAME_SESSION_KEY] ?? null;
        return new User((string)$id, $name !== null ? (string)$name : null);
    }

    public function persist(User $user): User
    {
        $this->session[self::USER_SESSION_KEY] = $user->id();
        $name = $user->name();
        if ($name !== null && $name !== '') {
            $this->session[self::USER_NAME_SESSION_KEY] = $name;
        } else {
            unset($this->session[self::USER_NAME_SESSION_KEY]);
        }
        return $user;
    }

    public function clear(): void
    {
        foreach (array_keys($this->session) as $key) {
            unset($this->session[$key]);
        }

        if (session_status() === PHP_SESSION_ACTIVE) {
            @session_regenerate_id(true);
            session_destroy();
        }
    }
}
