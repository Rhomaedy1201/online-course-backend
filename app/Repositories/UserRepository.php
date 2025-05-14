<?php

namespace App\Repositories;

use App\Http\Controllers\Traits\ApiResponse;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserRepository
{
    protected $model;
    use ApiResponse;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function findUser($id)
    {
        try {
            $user = $this->model->where('id', $id)->first();
            return $this->okApiResponse($user);
        } catch (\Exception $e) {
            return $this->errorApiResponse($e->getMessage());
        }
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

    public function update($request, $id)
    {
        try {
            $data = $request->validate([
                'name' => ['required', 'string'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
                'role_id' => ['required', 'exists:roles,id'],
            ]);

            $user = $this->model->findOrFail($id);
            $user->update($data);

            Log::info('update User : ', ['User :' => Auth::user()->email . " name : " . Auth::user()->name]);
            return $this->okApiResponse("Data Berhasil Diubah");
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['User :' => Auth::user()->email . " name : " . Auth::user()->name]);
            return $this->errorApiResponse($e->getMessage());
        } catch (QueryException $e) {
            Log::error($e->getMessage(), ['User :' => Auth::user()->email . " name : " . Auth::user()->name]);
            return $this->errorApiResponse($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = $this->model->findOrFail($id);
            $user->delete();

            Log::info("User Delete", ["User : " => Auth::user()->email . " name : " . Auth::user()->name]);
            return $this->okApiResponse("Data Berhasil Dihapus");
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['User :' => Auth::user()->email . " name : " . Auth::user()->name]);
            return $this->errorApiResponse($e->getMessage());
        } catch (QueryException $e) {
            Log::error($e->getMessage(), ['User :' => Auth::user()->email . " name : " . Auth::user()->name]);
            return $this->errorApiResponse($e->getMessage());
        }
    }
}