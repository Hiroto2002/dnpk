<?php

use Utils\Option;

require_once __DIR__ . '/../DbManager.php';
class StaffRepository{
    function fetchAllStaffs(){
        try {
            $pdo = getDb();
                $sql = $pdo->prepare('SELECT DISTINCT stf_Name_1,stf_ID FROM t_stf_mst');
            $sql->execute();
            $staffs = $sql->fetchAll();
            return Option::from($staffs ?? null);
        } catch (Throwable $e) {
            error_log($e->getMessage());
            return Option::none();
        }
}
}