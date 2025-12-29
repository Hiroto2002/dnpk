<?php

declare(strict_types=1);

namespace Domain\Services;

use Domain\Models\User;
use Domain\Repositories\UserSessionRepository;

class UserService
{
    /** @var UserSessionRepository */
    private $repository;

    public function __construct(UserSessionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function current(): ?User
    {
        return $this->repository->getCurrent();
    }

    public function login(string $userId, ?string $userName = null): User
    {
        $user = new User($userId, $userName);
        return $this->repository->persist($user);
    }

    public function logout(): void
    {
        $this->repository->clear();
    }
}
