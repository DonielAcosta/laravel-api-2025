<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array{

        return $this->collection->map(function($category){
            return [
                'id' => $category->id,
                'type' => 'category',
                'attributes' =>[
                    'name' => $category->name,
                ]
            ];
        })
        ->toArray();//metodo de clases
        // ->all();
    }
}
