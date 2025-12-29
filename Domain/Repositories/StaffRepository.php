<?php
declare(strict_types=1);

namespace Domain\Repositories;

use Domain\Models\Staff;
use PDO;
use Utils\Either;
use function Utils\attempt;

class StaffRepository
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /** @return Either<\Throwable, Staff[]> */
    function fetchAll(){
        return attempt(function (){
            $sql = $this->pdo->prepare('SELECT DISTINCT stf_Name_1, stf_ID FROM t_stf_mst');
            $sql->execute();
            $results = $sql->fetchAll(PDO::FETCH_ASSOC);
            $staffs = [];
            foreach ($results as $row){
                $staffs[] = new Staff($row['stf_ID'], $row['stf_Name_1']);
            }
            return $staffs;
        });
    }
}
