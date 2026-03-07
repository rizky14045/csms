<?php

use Illuminate\Pagination\LengthAwarePaginator;

if (!function_exists('getPaginate')) {
    function getPaginate($jsonResponse)
    {
        // Retrieve and decode the JSON content
        $jsonContent = $jsonResponse->getContent();
        $arrayContent = json_decode($jsonContent, false)->data;

        // Extract the data
        $items = collect($arrayContent->data);

        // Pagination details
        $total = $arrayContent->total;
        $perPage = $arrayContent->per_page;
        $currentPage = $arrayContent->current_page;
        $options = [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'pageName' => 'page',
        ];

        // Create a LengthAwarePaginator instance
        $paginatedCollection = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            $options
        );

        return $paginatedCollection;
    }
}

if (!function_exists('getData')) {
    function getData($jsonResponse)
    {
        try {
            //code...
            // Retrieve and decode the JSON content
            $jsonContent = $jsonResponse->getContent();
            $arrayContent = json_decode($jsonContent, true);

            // Extract the data
            $items = collect($arrayContent)['data'];
            return $items;
        } catch (\Throwable $th) {
            dd($jsonResponse);
            dd($th->getMessage());
        }
    }
}

if (!function_exists('getStatus')) {
    function getStatus($jsonResponse)
    {
        try {
            //code...
            // Retrieve and decode the JSON content
            $jsonContent = $jsonResponse->getContent();
            $arrayContent = json_decode($jsonContent, true);
            // Extract the status
            $items = collect($arrayContent)['success'];
            return $items;
        } catch (\Throwable $th) {
        }
    }
}

if (!function_exists('getMsg')) {
    function getMsg($jsonResponse)
    {
        try {
            //code...
            // Retrieve and decode the JSON content
            $jsonContent = $jsonResponse->getContent();
            $arrayContent = json_decode($jsonContent, true);
            // Extract the status
            $items = collect($arrayContent)['message'];
            return $items;
        } catch (\Throwable $th) {
            dd($jsonResponse);
            dd($th->getMessage());
        }
    }
}

if (!function_exists('getMsgError')) {
    function getMsgError($jsonResponse)
    {
        try {
            //code...
            // Retrieve and decode the JSON content
            $jsonContent = $jsonResponse->getContent();
            $arrayContent = json_decode($jsonContent, true);
            // Extract the status
            $items = collect($arrayContent)['errors'];
            return $items;
        } catch (\Throwable $th) {
            dd($jsonResponse);
            dd($th->getMessage());
        }
    }
}

if (!function_exists('groupPermissionsByModule')) {
    /**
     * Group permissions by module name (after dot)
     *
     * Example:
     * view.role -> Role
     * create.permission -> Permission
     *
     * @param array $permissions
     * @return array
     */
    function groupPermissionsByModule(array $permissions): array
    {
        $grouped = [];

        foreach ($permissions as $permission) {

            if (!isset($permission['name'])) {
                continue;
            }

            $parts = explode('.', $permission['name']);

            array_shift($parts);

            if (empty($parts)) {
                $moduleTitle = 'Other';
            } else {
                $moduleTitle = ucwords(str_replace('_', ' ', implode(' ', $parts)));
            }

            $grouped[$moduleTitle][] = $permission;
        }

        foreach ($grouped as $module => &$items) {
            usort($items, function ($a, $b) {
                return strcmp($a['name'], $b['name']);
            });
        }
        unset($items);

        ksort($grouped, SORT_STRING | SORT_FLAG_CASE);

        return $grouped;
    }
}
