<?php

declare(strict_types=1);

namespace Repositories\Test;

use Domain\Repositories\StaffRepository;
use PDO;
use PHPUnit\Framework\TestCase;

final class StaffRepositoryTest extends TestCase
{
    private function createPdo(): PDO
    {
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public function testFetchAllReturnsStaffCollection(): void
    {
        $pdo = $this->createPdo();
        $pdo->exec('CREATE TABLE t_stf_mst (stf_ID TEXT, stf_Name_1 TEXT)');
        $pdo->exec("INSERT INTO t_stf_mst (stf_ID, stf_Name_1) VALUES ('1', 'Alpha'), ('2', 'Beta')");
        $repository = new StaffRepository($pdo);

        $result = $repository->fetchAll();

        $this->assertTrue($result->isRight());
        $staffs = $result->get();
        $this->assertCount(2, $staffs);
        $this->assertSame('1', $staffs[0]->id());
        $this->assertSame('Alpha', $staffs[0]->name());
    }

    public function testFetchAllReturnsLeftWhenQueryFails(): void
    {
        $pdo = $this->createPdo();
        $repository = new StaffRepository($pdo);

        $result = $repository->fetchAll();

        $this->assertTrue($result->isLeft());
        $this->assertInstanceOf(\Throwable::class, $result->getLeft());
    }
}
