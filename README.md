### Example of how to setup screenshot testing in Laravel with htmx.
This is the complete repository for a blog article that I wrote.
[https://oliverlundquist.com/2024/07/04/laravel-screenshot-testing-with-htmx.html](https://oliverlundquist.com/2024/07/04/laravel-screenshot-testing-with-htmx.html)

### How to run this app on your own machine
```
git clone git@github.com:oliverlundquist/laravel-screenshot-testing.git
docker-compose down --remove-orphans && docker compose up --force-recreate --build
docker exec -it laravel-screenshot-testing-php-1 composer install -o
docker exec -it laravel-screenshot-testing-php-1 npm ci puppeteer
docker exec -it laravel-screenshot-testing-php-1 php artisan migrate:refresh --seed
docker exec -it laravel-screenshot-testing-php-1 php artisan migrate:refresh --seed --env=testing
docker exec -it laravel-screenshot-testing-php-1 php vendor/bin/phpunit
```
