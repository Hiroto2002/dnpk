<?php

declare(strict_types=1);

namespace Repositories\Test;

use Domain\Models\User;
use Domain\Repositories\UserSessionRepository;
use PHPUnit\Framework\TestCase;

final class UserSessionRepositoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
    }

    /**
     * セッションにユーザー情報がない場合、nullを返すことを確認する
     * @return void
     */
    public function testGetCurrentReturnsNullWhenSessionDoesNotHaveUser(): void
    {
        $session = [];
        $repository = new UserSessionRepository($session);

        $this->assertNull($repository->getCurrent());
    }

    /**
     * セッションにユーザー情報を登録できる
     * @return void
     */
    public function testPersistStoresUserInformation(): void
    {
        $session = [];
        $repository = new UserSessionRepository($session);
        $user = new User('99', 'Name');

        $stored = $repository->persist($user);

        $this->assertSame($user, $stored);
        $this->assertSame('99', $session['user_id']);
        $this->assertSame('Name', $session['user_name']);
    }

    /**
     * セッションにユーザー情報がある場合でも上書きする
     * @return void
     */
    public function testUpdateExistingUserInformation(): void
    {
        $session = ['user' => '42', 'user_name' => 'ExistingUser'];
        $repository = new UserSessionRepository($session);

        $user = new User('41', 'UpdatedUser');
        $repository->persist($user);

        $updatedUser = $repository->getCurrent();

        $this->assertInstanceOf(User::class, $updatedUser);
        $this->assertSame('41', $updatedUser->id());
        $this->assertSame('UpdatedUser', $updatedUser->name());
    }

    public function testClearRemovesSessionValues(): void
    {
        $session = ['user' => '99', 'user_name' => 'Someone'];
        $repository = new UserSessionRepository($session);

        $repository->clear();

        $this->assertSame([], $session);
    }
}