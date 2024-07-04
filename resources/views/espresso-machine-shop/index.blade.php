<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Espresso Machine Shop</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css">
    </head>
    @fragment('app-contents')
    <body id="app-contents">
        @include('espresso-machine-shop.components.navbar', compact('cart'))
        <section class="section">
            <div class="container">
                <div class="columns">
                    @foreach ($notifications as $index => $notification)
                    <div class="column is-one-quarter">
                        <form hx-post="/" name="hide-notification">
                            <div class="notification is-primary">
                                <input type="hidden" name="_token" value="{{ session()->token() }}" />
                                <button id="{{ 'hide-notification-' . $index  }}" name="remove-notification-index" value="{{ $index }}" class="delete"></button>
                                {{ $notification['text'] }}
                            </div>
                        </form>
                    </div>
                    @endforeach
                </div>
                <div class="columns">
                    <div class="column">
                        <h1 class="title">Espresso Machine Shop</h1>
                        <p class="subtitle">Laravel UI Screenshot Testing</p>
                    </div>
                    <div class="column">
                        <form hx-post="/" name="sort-products">
                            <input type="hidden" name="_token" value="{{ session()->token() }}" />
                            <div class="buttons has-addons is-justify-content-flex-end">
                                @if ($sort === 'asc')
                                    <button id="sort-products-asc" name="sort" value="asc" class="button is-success is-selected">ASC</button>
                                    <button id="sort-products-desc" name="sort" value="desc" class="button">DESC</button>
                                @else
                                    <button id="sort-products-asc" name="sort" value="asc" class="button">ASC</button>
                                    <button id="sort-products-desc" name="sort" value="desc" class="button is-success is-selected">DESC</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
                <div class="columns">
                    @foreach ($products as $product)
                    <div class="column">
                        @if (isset($cart[$product['id']]))
                        <form hx-post="/" name="increment-cart-item">
                        @else
                        <form hx-post="/" name="add-to-cart">
                        @endif
                        <input type="hidden" name="_token" value="{{ session()->token() }}" />
                            @include('espresso-machine-shop.components.card', compact('product', 'cart'))
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
    </body>
    @endfragment
    <script src="https://unpkg.com/htmx.org@1.9.12" integrity="sha384-ujb1lZYygJmzgSwoxRggbCHcjc0rB2XoQrxeTUQyRjrOnlCoYta87iKBWq3EsdM2" crossorigin="anonymous"></script>
</html>
