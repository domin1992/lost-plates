<?php

namespace App\Serializers;

use League\Fractal\Pagination\CursorInterface;
use League\Fractal\Pagination\PaginatorInterface;
use League\Fractal\Serializer\ArraySerializer as ArraySerializerParent;

class ArraySerializer extends ArraySerializerParent
{
    public function mergeIncludes(array $transformedData, array $includedData): array
    {
        collect($includedData)
            ->each(function ($data, $key) use (&$includedData) {
                if (
                    count($data) === 1
                    && isset($data[$key])
                ) {
                    $includedData[$key] = $data[$key];
                }

                if (
                    count($data) === 1
                    && isset($data['data'])
                ) {
                    $includedData[$key] = $data['data'];
                }
            });

        // If the serializer does not want the includes to be side-loaded then
        // the included data must be merged with the transformed data.
        if (! $this->sideloadIncludes()) {
            return array_merge($transformedData, $includedData);
        }

        return $transformedData;
    }

    public function paginator(PaginatorInterface $paginator): array
    {
        $currentPage = $paginator->getCurrentPage();
        $lastPage = $paginator->getLastPage();

        $pagination = [
            'total' => $paginator->getTotal(),
            'count' => $paginator->getCount(),
            'perPage' => $paginator->getPerPage(),
            'currentPage' => $currentPage,
            'totalPages' => $lastPage,
        ];

        $pagination['links'] = [];

        if ($currentPage > 1) {
            $pagination['links']['previous'] = $paginator->getUrl($currentPage - 1);
        }

        if ($currentPage < $lastPage) {
            $pagination['links']['next'] = $paginator->getUrl($currentPage + 1);
        }

        if (empty($pagination['links'])) {
            $pagination['links'] = (object) [];
        }

        return ['pagination' => $pagination];
    }

    public function cursor(CursorInterface $cursor): array
    {
        $cursor = [
            'current' => $cursor->getCurrent(),
            'prev' => $cursor->getPrev(),
            'next' => $cursor->getNext(),
            'count' => $cursor->getCount(),
        ];

        return ['cursor' => $cursor];
    }
}
