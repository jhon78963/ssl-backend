<?php
namespace App\Shared\Services;

use App\Shared\Requests\GetAllRequest;
use Arr;
use Str;

class SharedService {
    private int $limit = 10;
    private int $page = 1;
    private string $search = '';

    public function convertCamelToSnake(array $data)
    {
        return Arr::mapWithKeys($data, function ($value, $key) {
            return [Str::snake($key) => $value];
        });
    }

    public function query(
        GetAllRequest  $request,
        string $entityName,
        string $modelName,
        string $columnSearch
    ): array {
        $limit = $request->query('limit', $this->limit);
        $page = $request->query('page', $this->page);
        $search = $request->query('search', $this->search);

        $modelClass = "App\\$entityName\\Models\\$modelName";

        $query = $modelClass::query();

        if ($search) {
            $query = $this->searchFilter($query, $search, $columnSearch);
        }

        $models = $query->where('is_deleted', false)
                    ->skip(($page - 1) * $limit)
                    ->take($limit)
                    ->orderBy('id', 'asc')
                    ->get();

        $total = $query->where('is_deleted', false)->count();
        $pages = ceil($total / $limit);

        return [
            'collection' => $models,
            'total'=> $total,
            'pages' => $pages,
        ];
    }

    private function searchFilter($query, string $searchTerm, string $columnSearch): mixed
    {
        $searchTerm = strtolower($searchTerm);
        return $query->where(function ($query) use ($searchTerm, $columnSearch) {
            $query->whereRaw("LOWER($columnSearch) LIKE ?", ['%' . $searchTerm . '%']);
        });
    }
}
