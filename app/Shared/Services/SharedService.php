<?php
namespace App\Shared\Services;

use App\Shared\Requests\GetAllRequest;
use Arr;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Str;

class SharedService {
    private int $limit = 10;
    private int $page = 1;
    private string $search = '';
    private int $gender = 0;
    private string $status = '';

    public function convertCamelToSnake(array $data): array
    {
        return Arr::mapWithKeys($data, function ($value, $key): array {
            return [Str::snake($key) => $value];
        });
    }

    public function dateFormat($date) {
        if ($date === null) {
            return null;
        }
        $date = Carbon::createFromFormat('Y-m-d h:i:s', $date);
        $date = $date->format('d/m/Y h:i:s A');
        return $date;
    }

    public function query(
        GetAllRequest  $request,
        string $entityName,
        string $modelName,
        string $columnSearch,
    ): array {
        $limit = $request->query('limit', $this->limit);
        $page = $request->query('page', $this->page);
        $search = $request->query('search', $this->search);
        $gender = $request->query('gender', $this->gender);
        $status = $request->query('status', $this->status);

        $modelClass = "App\\$entityName\\Models\\$modelName";

        $query = $modelClass::query();

        if ($search) {
            $query = $this->searchFilter($query, $search, $columnSearch);
        }

        if ($gender) {
            $query->where('gender_id', $gender);
        }

        if ($status) {
            $query->where('status', $status);
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

        return $query->where(function ($query) use ($searchTerm, $columnSearch): void {
            $query->whereRaw(
                "LOWER(CAST($columnSearch AS TEXT)) LIKE ?",
                ['%' . $searchTerm . '%']
            );
        });
    }
}
