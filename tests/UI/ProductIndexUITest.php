<?php declare(strict_types=1);

namespace Tests\UI;

use App\Http\State\DataObjects\ProductIndexDataObjects;
use App\Http\State\Mutations\ProductIndexMutations;
use App\Http\State\StateManager;
use Tests\Traits\TakeScreenshotTrait;

class ProductIndexUITest extends TestCase
{
    use TakeScreenshotTrait;

    public function test_default_state_index_page()
    {
        $this->takeScreenshot('/');
    }

    public function test_sort_products_mutation()
    {
        (new StateManager)->updateState('ProductIndex', ['sort' => 'desc'], ProductIndexMutations::SortProducts);
        session()->save();

        $this->takeScreenshot('/');
    }

    public function test_add_to_cart_mutation()
    {
        (new StateManager)->updateState('ProductIndex', ['add-product-id' => 2], ProductIndexMutations::AddToCart);
        session()->save();

        $this->takeScreenshot('/');
    }

    public function test_increment_cart_item_mutation()
    {
        $cartState = [
            'cart' => [
                3 => ProductIndexDataObjects::cartItem(['id' => 3, 'name' => 'Product with ID 3', 'qty' => 1]),
                5 => ProductIndexDataObjects::cartItem(['id' => 5, 'name' => 'Product with ID 5', 'qty' => 1]),
                8 => ProductIndexDataObjects::cartItem(['id' => 8, 'name' => 'Product with ID 8', 'qty' => 1]),
            ]
        ];
        (new StateManager)->updateState('ProductIndex', array_replace($cartState, ['increment-product-id' => 5]), ProductIndexMutations::IncrementCartItem);
        session()->save();

        $this->takeScreenshot('/');
    }

    public function test_delete_cart_item_mutation()
    {
        $cartState = [
            'cart' => [
                3 => ProductIndexDataObjects::cartItem(['id' => 3, 'name' => 'Product with ID 3', 'qty' => 1]),
                5 => ProductIndexDataObjects::cartItem(['id' => 5, 'name' => 'Product with ID 5', 'qty' => 1]),
                8 => ProductIndexDataObjects::cartItem(['id' => 8, 'name' => 'Product with ID 8', 'qty' => 1]),
            ]
        ];
        (new StateManager)->updateState('ProductIndex', array_replace($cartState, ['remove-product-id' => 5]), ProductIndexMutations::DeleteCartItem);
        session()->save();

        $this->takeScreenshot('/');
    }

    public function test_hide_notification_mutation()
    {
        $notificationState = [
            'notifications' => [
                ProductIndexDataObjects::notification(['text' => 'Notification with Index 0']),
                ProductIndexDataObjects::notification(['text' => 'Notification with Index 1']),
                ProductIndexDataObjects::notification(['text' => 'Notification with Index 2']),
            ]
        ];
        (new StateManager)->updateState('ProductIndex', array_replace($notificationState, ['remove-notification-index' => 1]), ProductIndexMutations::HideNotification);
        session()->save();

        $this->takeScreenshot('/');
    }
}
