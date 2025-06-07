<?php

namespace App\Traits;

trait SearchQuery
{
    public static function getAll($params, $count = false, $withTrashed = false)
    {
        if (isset($params['fromDate']) && $params['fromDate']) {
            $params['fromDate'] = date('Y-m-d', strtotime(
                explode('T', str_replace('"', '', $params['fromDate']))[0]
            ));
        } else {
            $params['fromDate'] = null;
        }

        if (isset($params['toDate']) && $params['toDate']) {
            $params['toDate'] = date('Y-m-d', strtotime(
                explode('T', str_replace('"', '', $params['toDate']))[0]
            ));
        } else {
            $params['toDate'] = null;
        }

        if (isset($params['date']) && $params['date']) {
            $params['date'] = date('Y-m-d', strtotime(
                explode('T', str_replace('"', '', $params['date']))[0]
            ));
        } else {
            $params['date'] = null;
        }

        // Add all your patterns and replacement in these arrays
//        $patterns     = array( "/(ا|أ|آ)/", "/(ه|ة)/" );
//        $replacements = array( "[ا|أ|آ]",   "[ة|ه]" );
//        $query_string = preg_replace($patterns, $replacements, $params['query']);
        $whereHsRelations = [];
        if (isset(static::$searchInRelations) && count(static::$searchInRelations) && $params['query']) {
            foreach (static::$searchInRelations as $searchInRelationItem) {
                $relation = explode(':', $searchInRelationItem)[0];
                $fields = explode(',', explode(':', $searchInRelationItem)[1]);

                $index = array_search($relation, static::$relationsWithForSearch);
                if ($index !== -1) {
//                    unset(static::$relationsWithForSearch[$index]);
//                    static::$relationsWithForSearch[$relation] = function ($query) use ($params, $fields, $relation) {
//                        $count = 0;
//                        foreach ($fields as $field) {
//                            if ($count == 0) {
//                                $query->where($field, 'LIKE', '%' . $params['query'] . '%');
//                                $count++;
//                            } else {
//                                $query->orWhere($field, 'LIKE', '%' . $params['query'] . '%');
//                            }
//                        }
//                    };
                    $whereHsRelations[$relation] = function ($query) use ($params, $fields, $relation) {
                        $count = 0;
                        foreach ($fields as $field) {
                            if ($count == 0) {
                                $query->where($field, 'LIKE', '%' . $params['query'] . '%');
                                $count++;
                            } else {
                                $query->orWhere($field, 'LIKE', '%' . $params['query'] . '%');
                            }
                        }
                    };
                }
            }
        }
        $dateColumnFiltered = static::$dateColumnFiltered ?? 'created_at';

        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = static::with(static::$relationsWithForSearch ?? [])
            ->orderBy($params['order_column'], $params['order_dir'])
            ->when($params['fromDate'] && !$params['toDate'], function ($query) use ($params, $dateColumnFiltered) {
                $query->whereDate($dateColumnFiltered, '>=', $params['fromDate']);
            })
            ->when($params['toDate'] && !$params['fromDate'], function ($query) use ($params, $dateColumnFiltered) {
                $query->whereDate($dateColumnFiltered, '<=', $params['toDate']);
            })
            ->when($params['toDate'] && $params['fromDate'], function ($query) use ($params, $dateColumnFiltered) {
                $query->whereBetween($dateColumnFiltered, [$params['fromDate'], $params['toDate']]);
            })
            ->when($params['date'] ?? false, function ($query) use ($params, $dateColumnFiltered) {
                $query->whereDate($dateColumnFiltered, $params['date']);
            })
            ->when($params['query'], function ($query) use ($params) {
                if (isset(static::$searchableFields)) {
                    $count = 0;
                    foreach (static::$searchableFields as $field) {
                        if ($count == 0) {
                            $query->where($field, 'LIKE', '%' . $params['query'] . '%');
                            $count++;
                        } else {
                            $query->orWhere($field, 'LIKE', '%' . $params['query'] . '%');
                        }
                    }
                }
            });

        if (isset($params['extra_filters']) && count($params['extra_filters'])) {
            foreach ($params['extra_filters'] as $field => $value) {
                $query->where($field, is_array($value) ? $value['operation'] : $value['value'], is_array($value) ? $value['value'] : null);
            }
        }

        if (count($whereHsRelations)) {
            foreach ($whereHsRelations as $relation => $callback) {
                $query->orWhereHas($relation, $callback);
            }
        }

        if (isset($params['groupBy'])) {
            $query->groupBy($params['groupBy']);
        }

        if (isset($params['distinct']) && $params['distinct']) {
            $query->distinct($params['distinct']);
        }

        if (isset(static::$columnsToSelect) && count(static::$columnsToSelect)) {
            $query->select(static::$columnsToSelect);
        }

        if ($withTrashed) {
            $query->withTrashed();
        }

        if ($count) {
            return $query->count();
        }

        return $query->paginate($params['per_page']);
    }
}
