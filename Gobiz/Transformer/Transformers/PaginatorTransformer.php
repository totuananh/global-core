<?php

namespace Gobiz\Transformer\Transformers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Gobiz\Transformer\TransformerInterface;

class PaginatorTransformer implements TransformerInterface
{
    /**
     * Transform the data
     *
     * @param LengthAwarePaginator $paginator
     * @return array
     */
    public function transform($paginator)
    {
        return [
            'items' => $paginator->items(),
            'pagination' => [
                'total' => $paginator->total(),
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
            ],
        ];
    }
}