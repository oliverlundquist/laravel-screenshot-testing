<?php declare(strict_types=1);

namespace Tests\Features;

use App\Http\State\DataObjects\ProductIndexDataObjects as DataObject;
use App\Models\Product;

class ProductIndexFeaturesTest extends TestCase
{
    public function test_index_page_render()
    {
        $this->get('/');
        $this->assertResponseOk();
    }

    public function test_sort_products_feature_by_get_request()
    {
        $expectedSession    = [
            'sort'          => 'desc',
            'skip'          => 0,
            'take'          => 10,
            'cart'          => [],
            'products'      => [
                DataObject::product(Product::find(1)->toArray()),
                DataObject::product(Product::find(3)->toArray()),
                DataObject::product(Product::find(2)->toArray()),
                DataObject::product(Product::find(4)->toArray())
            ],
            'notifications' => []
        ];

        $this->visit('/?sort=desc')
            ->see('Espresso Machine Shop')
            ->submitHxFormById('#sort-products-desc')
            ->assertSessionHas('ProductIndex', $expectedSession);
    }

    public function test_sort_products_feature()
    {
        $expectedSession    = [
            'sort'          => 'desc',
            'skip'          => 0,
            'take'          => 10,
            'cart'          => [],
            'products'      => [
                DataObject::product(Product::find(1)->toArray()),
                DataObject::product(Product::find(3)->toArray()),
                DataObject::product(Product::find(2)->toArray()),
                DataObject::product(Product::find(4)->toArray())
            ],
            'notifications' => []
        ];

        $this->visit('/')
            ->see('Espresso Machine Shop')
            ->submitHxFormById('#sort-products-desc')
            ->assertSessionHas('ProductIndex', $expectedSession);
    }

    public function test_add_to_cart_feature()
    {
        $expectedSession    = [
            'sort'          => 'asc',
            'skip'          => 0,
            'take'          => 10,
            'cart'          => [
                2 => DataObject::cartItem(array_replace(Product::find(2)->toArray(), ['qty' => 1]))
            ],
            'products'      => [
                DataObject::product(Product::find(4)->toArray()),
                DataObject::product(Product::find(2)->toArray()),
                DataObject::product(Product::find(3)->toArray()),
                DataObject::product(Product::find(1)->toArray())
            ],
            'notifications' => [
                DataObject::notification(['text' => 'Added: La Marzocco Linea Classic'])
            ]
        ];

        // load page
        $this->visit('/')
            ->see('Espresso Machine Shop')
            ->submitHxFormById('#add-to-cart-2')
            ->assertSessionHas('ProductIndex', $expectedSession);
    }

    public function test_increment_cart_item_feature()
    {
        $expectedSession    = [
            'sort'          => 'asc',
            'skip'          => 0,
            'take'          => 10,
            'cart'          => [
                2 => DataObject::cartItem(array_replace(Product::find(2)->toArray(), ['qty' => 2]))
            ],
            'products'      => [
                DataObject::product(Product::find(4)->toArray()),
                DataObject::product(Product::find(2)->toArray()),
                DataObject::product(Product::find(3)->toArray()),
                DataObject::product(Product::find(1)->toArray())
            ],
            'notifications' => [
                DataObject::notification(['text' => 'Added: La Marzocco Linea Classic'])
            ]
        ];

        $this->visit('/')
            ->see('Espresso Machine Shop')
            ->submitHxFormById('#add-to-cart-2')
            ->submitHxFormById('#increment-cart-item-2')
            ->assertSessionHas('ProductIndex', $expectedSession);
    }

    public function test_delete_cart_item_feature()
    {
        $expectedSession    = [
            'sort'          => 'asc',
            'skip'          => 0,
            'take'          => 10,
            'cart'          => [
                2 => DataObject::cartItem(array_replace(Product::find(2)->toArray(), ['qty' => 1])),
            ],
            'products'      => [
                DataObject::product(Product::find(4)->toArray()),
                DataObject::product(Product::find(2)->toArray()),
                DataObject::product(Product::find(3)->toArray()),
                DataObject::product(Product::find(1)->toArray())
            ],
            'notifications' => [
                DataObject::notification(['text' => 'Added: ' . Product::find(3)->name]),
                DataObject::notification(['text' => 'Added: ' . Product::find(2)->name])
            ]
        ];

        $this->visit('/')
            ->see('Espresso Machine Shop')
            ->submitHxFormById('#add-to-cart-3')
            ->submitHxFormById('#add-to-cart-2')
            ->submitHxFormById('#delete-cart-item-3')
            ->assertSessionHas('ProductIndex', $expectedSession);
    }

    public function test_hide_notification_feature()
    {
        $expectedSession    = [
            'sort'          => 'asc',
            'skip'          => 0,
            'take'          => 10,
            'cart'          => [
                2 => DataObject::cartItem(array_replace(Product::find(2)->toArray(), ['qty' => 1])),
                3 => DataObject::cartItem(array_replace(Product::find(3)->toArray(), ['qty' => 1])),
            ],
            'products'      => [
                DataObject::product(Product::find(4)->toArray()),
                DataObject::product(Product::find(2)->toArray()),
                DataObject::product(Product::find(3)->toArray()),
                DataObject::product(Product::find(1)->toArray())
            ],
            'notifications' => [
                DataObject::notification(['text' => 'Added: ' . Product::find(2)->name])
            ]
        ];

        $this->visit('/')
            ->see('Espresso Machine Shop')
            ->submitHxFormById('#add-to-cart-3')
            ->submitHxFormById('#add-to-cart-2')
            ->submitHxFormById('#hide-notification-0')
            ->assertSessionHas('ProductIndex', $expectedSession);
    }
}
