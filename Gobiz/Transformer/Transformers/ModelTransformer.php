<?php

namespace Gobiz\Transformer\Transformers;

use Gobiz\Transformer\TransformerInterface;
use Illuminate\Database\Eloquent\Model;

class ModelTransformer implements TransformerInterface
{
    /**
     * Transform the data
     *
     * @param Model $data
     * @return mixed
     */
    public function transform($data)
    {
        return $data->attributesToArray();
    }
}