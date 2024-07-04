### Example of how to setup screenshot testing in Laravel with htmx.
This is the complete repository for a blog article that I wrote.
[https://oliverlundquist.com/2023/10/05/run-code-from-another-php-app.html](https://oliverlundquist.com/2023/10/05/run-code-from-another-php-app.html)

### How to run this on your own machine
docker-compose down --remove-orphans && docker compose up --force-recreate --build
docker exec -it laravel-screenshot-testing-php-1 composer install -o
docker exec -it laravel-screenshot-testing-php-1 npm ci puppeteer
docker exec -it laravel-screenshot-testing-php-1 php artisan migrate:refresh --seed
docker exec -it laravel-screenshot-testing-php-1 php artisan migrate:refresh --seed --env=testing
docker exec -it laravel-screenshot-testing-php-1 php vendor/bin/phpunit
