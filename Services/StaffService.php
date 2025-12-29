<?php
namespace Services;

class StaffServices {
    private $repository;
    public function __construct($repository) {
        $this->repository = $repository;
    }

    public function findMe(string $id){

    
        
    }

    public function getAllStaffs() {
    }

    public function login($user) {
        // ログイン処理
    }

    public function logout() {
        // ログアウト処理
    }

}