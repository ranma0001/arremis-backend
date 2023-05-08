<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function paginate($request, $query)
    {
        $q = $request->input('q', null);

        $total = count(\DB::select($query->toSql(), $query->getBindings())); //$query->count();
        $page = (int) request('page', 1);
        $take = (int) request('take', $total);
        $skip = ($page - 1) * $take;
        $query = $query->skip($skip)->take($take);

        $sort_key = $request->input('sort_key', 'id');
        $sort_dir = $request->input('sort_dir', 'ascend') === 'ascend' ? 'asc' : 'desc';
        $data = $query->orderBy($sort_key, $sort_dir)->get();

        $filters = $request->all();
        unset($filters['skip']);
        unset($filters['page']);
        unset($filters['take']);
        unset($filters['total']);
        unset($filters['sort_dir']);
        unset($filters['sort_key']);
        unset($filters['q']);

        return array(
            'filters' => (object) $filters,
            'sort_dir' => $sort_dir === 'desc' ? 'descend' : 'ascend',
            'sort_key' => $sort_key,
            'page' => $page,
            'take' => $take,
            'total' => $total,
            'q' => $q,
            'data' => $data,
        );
    }

    public function paginate_filter_sort_search($query, $ALLOWED_FILTERS, $JSON_FIELDS = [], $BOOL_FIELDS = [], $SEARCH_FIELDS = [], $IDS_COLUMN = "", $USE_OR_WHERE = false)
    {
        $q = request('q', null);
        if (request('q')) {
            $filter_queries = [];
            foreach ($SEARCH_FIELDS as $search_field) {
                $search_field = implode('`.`', explode('.', $search_field));
                array_push($filter_queries, "`$search_field` LIKE '%$q%' ");
            }
            if (sizeof($filter_queries) > 0) {
                $all_query = '(' . implode(' or ', $filter_queries) . ')';
                $query = $query->whereRaw($all_query);
            }
        }

        $all_filter_queries = [];
        $filters = [];

        foreach ($ALLOWED_FILTERS as $allowed_filter) {
            $filters[$allowed_filter] = request($allowed_filter);
            if (request($allowed_filter, null)) {
                $filter_queries = [];
                foreach (request($allowed_filter) as $filter) {
                    array_push($filter_queries, "`$allowed_filter` = '$filter' ");
                }
                if (sizeof($filter_queries) > 0) {
                    $filter_query = '(' . implode(' or ', $filter_queries) . ')';
                    array_push($all_filter_queries, $filter_query);
                }
            }
        }
        if (sizeof($all_filter_queries) > 0) {
            $all_query = '(' . implode(' and ', $all_filter_queries) . ')';
            if ($USE_OR_WHERE) {
                $query = $query->orWhereRaw($all_query);
                clock('use_or_where: ', $query->toSql());
            } else {
                $query = $query->whereRaw($all_query);
            }
        }

        // pagination
        $total = count(\DB::select($query->toSql(), $query->getBindings())); //$query->count();
        $page = (int) request('page', 1);
        $take = (int) request('take', $total);
        $skip = ($page - 1) * $take;
        $query = $query->skip($skip)->take($take);

        // sorting
        $sort_key = request('sort_key', 'id', 'created_at', 'updated_at', 'last_name', 'total', 'start_date', 'file_name', 'file_date', 'name', 'email', 'created_by', 'company_name', 'applicant_firstname', 'user_id');
        $sort_dir = request('sort_dir', 'descend') == 'descend' ? 'desc' : 'asc';
        if (is_array($sort_key)) {
            foreach ($sort_key as $item) {
                $query = $query->orderBy($item, $sort_dir);
            }
        } else {
            $query = $query->orderBy($sort_key, $sort_dir);
        }
        $data = $query->get();

        if (sizeof($JSON_FIELDS) > 0) {
            $data->transform(function ($item, $key) use ($JSON_FIELDS) {
                foreach ($JSON_FIELDS as $json_field) {
                    $item->{$json_field} = json_decode($item->{$json_field});
                }
                return $item;
            });
        }

        if (sizeof($BOOL_FIELDS) > 0) {
            $data->transform(function ($item, $key) use ($BOOL_FIELDS) {
                foreach ($BOOL_FIELDS as $bool_field) {
                    $item->{$bool_field} = boolval($item->{$bool_field});
                }
                return $item;
            });
        }

        return array(
            'filters' => $filters,
            'sort_dir' => $sort_dir === 'desc' ? 'descend' : 'ascend',
            'sort_key' => $sort_key,
            'page' => $page,
            'take' => $take,
            'total' => $total,
            'q' => $q,
            'data' => $data,
        );
    }

}
