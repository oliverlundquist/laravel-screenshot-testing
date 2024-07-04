<?php

namespace App\Http\State;

use App\Http\State\DataObjects\ProductIndexDataObjects as DataObject;
use App\Http\State\Mutations\ProductIndexMutations as Mutations;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductIndexModule
{
    private $defaultState = [
        'sort'          => 'asc',
        'skip'          => 0,
        'take'          => 10,
        'cart'          => [],
        'products'      => [],
        'notifications' => []
    ];

    public function getDefaultState(bool $fetchProducts = true): array
    {
        return array_replace($this->defaultState, ['products' => $fetchProducts ? $this->getProducts() : []]);
    }

    public function updateState(array $newData, ?Mutations $mutation = null): array
    {
        // merge new data
        $newState = array_replace($this->getDefaultState(false), $newData);
        $newState['products'] = $this->getProducts($newState);

        // call mutations
        if (! is_null($mutation)) {
            $newState = match ($mutation) {
                Mutations::SortProducts      => $this->sortProducts($newState),
                Mutations::AddToCart         => $this->addToCart($newState),
                Mutations::IncrementCartItem => $this->incrementCartItem($newState),
                Mutations::DeleteCartItem    => $this->deleteCartItem($newState),
                Mutations::HideNotification  => $this->hideNotification($newState),
            };
        }

        // filter out any invalid keys
        return array_replace($this->defaultState, array_intersect_key($newState, $this->defaultState));
    }

    private function getProducts(array $state = []): array
    {
        $state    = count($state) ? $state : $this->defaultState;
        $cacheKey = implode('_', ['products', $state['sort'], $state['skip'], $state['take']]);

        $products = env('APP_ENV') === 'testing'
            ? Product::orderBy('name', $state['sort'])->skip($state['skip'])->take($state['take'])->get()->toArray()
            : Cache::remember($cacheKey, 180, function () use ($state) {
                return Product::orderBy('name', $state['sort'])->skip($state['skip'])->take($state['take'])->get()->toArray();
            });

        return array_map(fn ($product) => DataObject::product($product), $products);
    }

    private function findProductById(array $state, int $productId): array
    {
        foreach ($state['products'] as $product) {
            if ($product['id'] === $productId) {
                return $product;
            }
        }
    }

    /** ******** *
     * Mutations *
     * ********* */

    private function sortProducts(array $state): array
    {
        // $payload = $state['sort'];

        $state['products'] = $this->getProducts($state);

        return $state;
    }

    private function addToCart(array $state): array
    {
        $payload = $state['add-product-id'];

        if ($product = $this->findProductById($state, $payload)) {
            $state['cart'][$payload]  = array_replace(DataObject::cartItem($product), ['qty' => 1]);
            $state['notifications'][] = DataObject::notification(['text' => 'Added: ' . $product['name']]); // TODO: i18n
        }
        return $state;
    }

    private function incrementCartItem(array $state): array
    {
        $payload = $state['increment-product-id'];

        $state['cart'][$payload]['qty'] = $state['cart'][$payload]['qty'] + 1;

        return $state;
    }

    private function deleteCartItem(array $state): array
    {
        $payload = $state['remove-product-id'];

        unset($state['cart'][$payload]);

        return $state;
    }

    private function hideNotification(array $state): array
    {
        $payload = $state['remove-notification-index'];

        unset($state['notifications'][$payload]);
        $state['notifications'] = array_values($state['notifications']);

        return $state;
    }
}
