<?php
declare(strict_types=1);

namespace Domain\Services;

use Domain\Repositories\StaffRepository;
use Domain\Models\Staff;
use Utils\Either;

class StaffService
{
    /** @var StaffRepository */
    private $repo;

    public function __construct(StaffRepository $repo)
    {
        $this->repo = $repo;
    }

    /** @return Either<\Throwable, Staff[]> */
    public function findAll(): Either
    {
        return $this->repo->fetchAll();
    }
}
