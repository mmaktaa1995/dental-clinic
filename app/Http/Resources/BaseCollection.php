<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseCollection extends ResourceCollection
{
    private $pagination;
    private $name;

    /**
     * BaseCollection constructor.
     *
     * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator $resource
     * @param $collects
     * @param string $name of the field data resource
     * @param array $extra_fields with the pagination
     */
    public function __construct($resource, $collects, string $name = 'entries', array $extra_fields = [])
    {
        $this->name = $name;
        $this->collects = $collects;

        $this->pagination = array_merge([
            'total' => $resource->total(),
            'last_page' => $resource->lastPage(),
            'current_page' => $resource->currentPage(),
            'per_page' => $resource->perPage()
        ], $extra_fields);

        parent::__construct($resource);
    }

    public function toArray($request)
    {
        return [
            $this->name => $this->collection,
            'pagination' => $this->pagination,
        ];
    }
}
