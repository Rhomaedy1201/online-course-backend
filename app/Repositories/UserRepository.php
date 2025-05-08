<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function index($search, $limit = 10)
    {
        $search = strtolower($search);
        $data = $this->model->where(function ($query) use ($search) {
            $query->where("name", "like", "%" . $search . "%")
                ->orWhere("email", "like", "%" . $search . "%");
        })
            ->orWhereHas('role', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->with('role')
            ->paginate($limit);
        return $data;
    }
}