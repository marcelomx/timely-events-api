## Events API

Timely events API

### Requirements

-   PHP >= 7.4
-   PDO MySQL or PDO SQLServer
-   SQLite and PDO SQLite (for unit/feature tests)

### Running

1. Install Composer dependencies `composer install`
2. Create the database en configure database credentials on `.env`
3. Run migrations `php artisan migrate`
4. Run database seeds `php artisan db:seed`
5. Run Laravel server `php artisan serve`
6. Access the API: http://localhost:8000/api/events

### Testing

Run `php artisan test`
