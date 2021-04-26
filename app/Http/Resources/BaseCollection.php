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
     * @param $resource
     * @param $collects
     * @param string $name of the field data resource
     * @param array $extra_fields with the pagination
     */
    public function __construct($resource, $collects, $name = 'entries', $extra_fields = [])
    {
        $this->name = $name;
        $this->collects = $collects;

        $this->pagination = array_merge([
            'total' => $resource->total(),
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
