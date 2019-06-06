#Installation
Run the following commands in your terminal
- `git clone https://github.com/RomainDW/BileMo.git`
- `cd BileMo/`
- `composer install`

Configure the .env file with your database url :
`DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name`

Then run the following commands in your terminal
- `php bin/console doctrine:database:create`
- `php bin/console doctrine:migrations:migrate`
- `php bin/console doctrine:fixture:load`
- `php bin/console server:start`

Done. Check the API documentation on /api/doc