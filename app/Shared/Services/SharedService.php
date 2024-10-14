<?php
namespace App\Shared\Services;

use App\Shared\Requests\GetAllRequest;
use Arr;
use Illuminate\Contracts\Database\Eloquent\Builder;
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

        $total = $query->count();
        $pages = ceil($total / $limit);

        $models = $query->where('is_deleted', false)
                    ->skip(($page - 1) * $limit)
                    ->take($limit)
                    ->orderBy('id', 'asc')
                    ->get();

        return [
            'collection' => $models,
            'total'=> $total,
            'pages' => $pages,
        ];
    }

    private function searchFilter(Builder $query, string $searchTerm, string $columnSearch): Builder
    {
        $searchTerm = strtolower($searchTerm);
        return $query->where(function ($query) use ($searchTerm, $columnSearch) {
            $query->whereRaw("LOWER($columnSearch) LIKE ?", ['%' . $searchTerm . '%']);
        });
    }
}
