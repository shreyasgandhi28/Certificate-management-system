<?php

namespace App\Http\Traits;

trait HasSortableColumns
{
    /**
     * Apply sorting to the query
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param array $validSortFields
     * @param string $defaultSortField
     * @param string $defaultSortDirection
     * @return array
     */
    protected function applySorting($query, $request, array $validSortFields, string $defaultSortField = 'id', string $defaultSortDirection = 'asc')
    {
        $sortField = $request->input('sort', $defaultSortField);
        $sortDirection = $request->input('direction', $defaultSortDirection);
        
        // Validate sort field to prevent SQL injection
        $sortField = in_array($sortField, $validSortFields) ? $sortField : $defaultSortField;
        $sortDirection = $sortDirection === 'desc' ? 'desc' : 'asc';
        
        // Apply sorting
        $query->orderBy($sortField, $sortDirection);
        
        return [
            'field' => $sortField,
            'direction' => $sortDirection,
            'nextDirection' => $sortDirection === 'asc' ? 'desc' : 'asc'
        ];
    }
    
    /**
     * Get the sort parameters for the view
     *
     * @param array $sortData
     * @param array $additionalParams
     * @return array
     */
    protected function getSortParameters(array $sortData, array $additionalParams = [])
    {
        return array_merge([
            'sort' => [
                'field' => $sortData['field'] ?? 'id',
                'direction' => $sortData['direction'] ?? 'asc',
                'nextDirection' => $sortData['nextDirection'] ?? 'desc'
            ]
        ], $additionalParams);
    }
}
