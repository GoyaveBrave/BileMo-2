#Installation
Run the following commands in your terminal
- `git clone https://github.com/RomainDW/BileMo.git`
- `cd BileMo/`
- `composer install`

Configuration of JWT
```bash
mkdir config/jwt
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

In case first openssl command forces you to input password use following to get the private key decrypted
```bash
$ openssl rsa -in config/jwt/private.pem -out config/jwt/private2.pem
$ mv config/jwt/private.pem config/jwt/private.pem-back
$ mv config/jwt/private2.pem config/jwt/private.pem
```
Configure the .env file with your database url :
`DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name`

Then run the following commands in your terminal
- `php bin/console doctrine:database:create`
- `php bin/console doctrine:migrations:migrate`
- `php bin/console doctrine:fixture:load`
- `php bin/console server:start`

Done. Check the API documentation on /api/doc

#Tests
To run tests, you have to configure the test environment :
in .dev.test, update the `DATABASE_URL` with your database information and the `BASE_URI` with your base uri.

Now you can run tests with the following commands :
- `vendor/bin/behat` : run all tests
- `vendor/bin/behat --tags '@tag_name'` : run specific scenarios
- `vendor/bin/behat --name 'element of feature'` : Only execute the feature elements which match part of the given name or regex.

The test files are located in /features/features/api/