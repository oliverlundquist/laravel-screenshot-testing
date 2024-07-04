<?php

namespace App\Http\State\DataObjects;

class ProductIndexDataObjects
{
    public static function notification(array $data = []): array
    {
        $keys = [
            'text'   => 'hello',
            'closed' => false
        ];
        return array_replace($keys, array_intersect_key($data, $keys));
    }

    public static function product(array $data = []): array
    {
        $keys = [
            'id'          => 1,
            'name'        => '',
            'description' => '',
            'qty'         => 0,
            'image'       => ''
        ];
        return array_replace($keys, array_intersect_key($data, $keys));
    }

    public static function cartItem(array $data = []): array
    {
        $keys = [
            'id'    => 1,
            'name'  => '',
            'qty'   => 1,
            'image' => ''
        ];
        return array_replace($keys, array_intersect_key($data, $keys));
    }
}
