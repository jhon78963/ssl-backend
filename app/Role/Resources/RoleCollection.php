<?php

namespace App\Role\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RoleCollection extends ResourceCollection
{
    protected $total;
    protected $pages;

    public function __construct($resource, $total, $pages)
    {
        parent::__construct($resource);
        $this->total = $total;
        $this->pages = $pages;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'paginate' => [
                'total' => $this->total,
                'pages' => $this->pages,
            ]
        ];
    }
}
