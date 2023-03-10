<?php

namespace App\Serializers;

use League\Fractal\Serializer\ArraySerializer as ArraySerializerParent;

class ArraySerializer extends ArraySerializerParent
{
    /**
     * Serialize a collection.
     *
     * @param  string  $resourceKey
     */
    public function collection(?string $resourceKey, array $data): array
    {
        return $data;
    }
}
