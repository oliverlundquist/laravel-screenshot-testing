<nav class="navbar is-primary">
    <div class="navbar-end">
        <div class="navbar-item has-dropdown is-active">
            <a class="navbar-link">Cart Contents</a>
            <div class="navbar-dropdown is-right p-2 pr-6">
                @foreach ($cart as $index => $cartItem)
                <form hx-post="/" name="delete-cart-item">
                    <input type="hidden" name="_token" value="{{ session()->token() }}" />
                    <div class="navbar-item">
                        <img src="{{ $cartItem['image'] }}" />
                        <div class="ml-3 has-text-danger">{{ $cartItem['name'] }} x {{ $cartItem['qty'] }}</div>
                        <button id="{{ 'delete-cart-item-' . $index }}" name="remove-product-id" value="{{ $index }}" class="delete is-primary ml-3"></button>
                    </div>
                </form>
                @endforeach
            </div>
        </div>
    </div>
</nav>
