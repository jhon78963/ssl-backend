<?php

namespace App\Book\Services;

use App\Book\Models\Book;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Services\ModelService;
use App\Shared\Services\SharedService;

class BookService {
    private int $limit = 10;
    private int $page = 1;
    private string $schedule = '';
    private string $startDate = '';
    private string $endDate = '';
    protected ModelService $modelService;
    protected SharedService $sharedService;

    public function __construct(ModelService $modelService, SharedService $sharedService)
    {
        $this->modelService = $modelService;
        $this->sharedService = $sharedService;
    }

    public function create(array $newBook): Book
    {
        return $this->modelService->create(new Book(), $newBook);
    }

    public function getAll(
        GetAllRequest  $request,
        string $entityName,
        string $modelName,
        ?string $startDate = null,
        ?string $endDate = null,
        ?string $schedule = null,
    ): array {
        $limit = $request->query('limit', $this->limit);
        $page = $request->query('page', $this->page);
        $startDate = $request->query('startDate', $this->startDate);
        $endDate = $request->query('endDate', $this->endDate);
        $schedule = $request->query('schedule', $this->schedule);

        $modelClass = "App\\$entityName\\Models\\$modelName";

        $query = $modelClass::query();

        if ($schedule) {
            $query->where('schedule_id', $schedule);
        }

        if ($startDate || $endDate) {
            $query = $this->sharedService->dateFilter($query, $startDate, $endDate);
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

    public function update(Book $book, array $editBook): Book
    {
        $editBook['total_paid'] += $book->total_paid;
        return $this->modelService->update($book, $editBook);
    }

    public function validate(Book $book, string $modelName): Book
    {
        return $this->modelService->validate($book, $modelName);
    }
}
