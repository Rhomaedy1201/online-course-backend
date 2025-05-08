<?php

namespace App\Repositories;

use App\Http\Controllers\Traits\ApiResponse;
use App\Models\Role;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

class RoleRepository
{
    protected $model;
    use ApiResponse;

    public function __construct(Role $role)
    {
        $this->model = $role;
    }

    public function index($search, $limit = 10)
    {
        $search = strtolower($search);
        $data = $this->model->where("name", "like", "%" . $search . "%")
            ->paginate($limit);
        return $data;
    }

    public function store($request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
            ]);

            $this->model->create([
                'name' => $data['name'],
                'permissions' => json_encode([]),
            ]);
            return $this->okApiResponse("Data Role Berhasil ditambahkan!.");
        } catch (\Exception $e) {
            return $this->errorApiResponse($e->getMessage());
        } catch (QueryException $e) {
            return $this->errorApiResponse($e->getMessage());
        }
    }

    public function edit($id)
    {
        $role = $this->model->find($id);
        return $role;
    }

    public function update($id, $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
            ]);

            $this->model->where('id', $id)->update([
                "name" => $data["name"],
            ]);
            return $this->okApiResponse("Data Berhasil Diubah");
        } catch (\Exception $e) {
            return $this->errorApiResponse($e->getMessage());
        } catch (QueryException $e) {
            return $this->errorApiResponse($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->model->where("id", $id)->delete();
            return $this->okApiResponse("Data Role Berhasil Hapus");
        } catch (\Exception $e) {
            return $this->errorApiResponse($e->getMessage());
        }
    }
}