<?php

namespace App\Repositories;

use App\Http\Controllers\Traits\ApiResponse;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserRepository
{
    protected $model;
    use ApiResponse;

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

    public function store($request)
    {
        try {
            $data = $request->validate([
                'name' => ['required', 'string'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
                'role_id' => ['required', 'exists:roles,id'],
            ]);
            $user = $this->model->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt("12345678"),
                'role_id' => $data['role_id'],
            ]);
            Log::info('Create User : ', ['User :' => Auth::user(), 'id' => $user->id]);
            return $this->okApiResponse($user);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['User :' => Auth::user()]);
            return $this->errorApiResponse($e->getMessage());
        } catch (QueryException $e) {
            Log::error($e->getMessage(), ['User :' => Auth::user()]);
            return $this->errorApiResponse($e->getMessage());
        }
    }
}