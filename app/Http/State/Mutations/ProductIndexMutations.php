<?php

namespace App\Http\State\Mutations;

enum ProductIndexMutations: string
{
    case SortProducts      = 'sort-products';
    case AddToCart         = 'add-to-cart';
    case IncrementCartItem = 'increment-cart-item';
    case DeleteCartItem    = 'delete-cart-item';
    case HideNotification  = 'hide-notification';
}
