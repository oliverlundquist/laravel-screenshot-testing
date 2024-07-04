<div class="card">
    <div class="card-image">
        <figure class="image">
            <img src="{{ $product['image'] }}" alt="Placeholder image" />
        </figure>
    </div>
    <header class="card-header">
        <p class="card-header-title">{{ $product['name'] }}</p>
    </header>
    <div class="card-content">
        <div class="content">
            {{ $product['description'] }}
        </div>
    </div>
    <footer class="card-footer">
        @if (isset($cart[$product['id']]))
        <button id="{{ 'increment-cart-item-' . $product['id'] }}" name="increment-product-id" value="{{$product['id']}}" class="card-footer-item">Add 1+ to Cart</button>
        @else
        <button id="{{ 'add-to-cart-' . $product['id'] }}" name="add-product-id" value="{{$product['id']}}" class="card-footer-item has-background-primary">Add to Cart</button>
        @endif
    </footer>
</div>
