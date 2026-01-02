<?php

declare(strict_types=1);

namespace Domain\Repositories;

use Domain\Models\User;

class UserSessionRepository
{
    private const USER_ID_SESSION_KEY = 'user_id';
    private const USER_NAME_SESSION_KEY = 'user_name';

    /** @var array<string,mixed> */
    private $session;

    public function __construct(array &$session)
    {
        $this->session = &$session;
    }

    public function getCurrent(): ?User
    {
        $id = $this->session[self::USER_ID_SESSION_KEY] ?? null;
        if ($id === null || $id === '') {
            return null;
        }
        
        $name = $this->session[self::USER_NAME_SESSION_KEY] ?? null;
        return new User((string)$id, (string)$name);
    }

    public function persist(User $user): User
    {
        $this->session[self::USER_ID_SESSION_KEY] = $user->id();
        
        $this->session[self::USER_NAME_SESSION_KEY] = $user->name();
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