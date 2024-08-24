<?php
namespace App\Shared\Services;

use App\Shared\Requests\GetAllRequest;
use App\User\Resources\UserResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Auth;

class SharedService {
    private int $limit = 10;
    private int $page = 1;
    private string $search = '';

    public function deleteModel(Model $model): void
    {
        $model->is_deleted = true;
        $model->deleter_user_id = Auth::id();
        $model->deletion_time = now()->format('Y-m-d H:i:s');
        $model->save();
    }

    public function query(GetAllRequest  $request, string $modelName, string $columnSearch): array {
        $limit = $request->query('limit', $this->limit);
        $page = $request->query('page', $this->page);
        $search = $request->query('search', $this->search);

        $modelClass = "App\\$modelName\\Models\\$modelName";
        $query = $modelClass::query();

        if ($search) {
            $query = $this->searchFilter($query, $search, $columnSearch);
        }

        $models = $query->where('is_deleted', false)->skip(($page - 1) * $limit)->take($limit)->get();
        $total = $query->where('is_deleted', false)->count();
        $pages = ceil($total / $limit);

        return [
            'collection' => $models,
            'total'=> $total,
            'pages' => $pages,
        ];
    }

    private function searchFilter($query, string $searchTerm, string $columnSearch)
    {
        $searchTerm = strtolower($searchTerm);
        return $query->where(function ($query) use ($searchTerm, $columnSearch) {
            $query->whereRaw("LOWER($columnSearch) LIKE ?", ['%' . $searchTerm . '%']);
        });
    }

    public function validateModel($model, string $modelName)
    {
        if ($model->is_deleted == true) {
            throw new ModelNotFoundException("$modelName does not exists.");
        }

        return $model;
    }
}
