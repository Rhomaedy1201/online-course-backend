<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository
{
    protected $model;

    public function __construct(Role $role)
    {
        $this->model = $role;
    }

    public function index($search, $limit = 10)
    {
        $search = strtolower($search);
        $dosen = $this->model->where("name", "like", "%" . $search . "%")
            ->paginate($limit);
        return $dosen;
    }
}