# Installation

Run the following commands in your terminal
- `git clone https://github.com/RomainDW/BileMo.git`
- `cd BileMo/`
- `composer install`

Configuration of JWT
```bash
$ mkdir config/jwt
$ openssl genrsa -out config/jwt/private.pem -aes256 4096
$ openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
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

The fixtures provide you 2 users (SFR & Orange with the password "password"), each user has 10 customers.

Check the API documentation on `/api/doc`

# Requests
To make a request, a token is required. To have this token, you have to send a POST request on `/login_check` with login credentials in the body.
Example :
```bash
curl --request POST \
  --url http://127.0.0.1:8000/login_check \
  --header 'content-type: application/json' \
  --data '{"username":"SFR","password":"password"}'
```

Now for each request, you must fill in the token in the headers like this :

```bash
curl --request GET \
  --url http://127.0.0.1:8000/api \
  --header 'authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1NjAxNzUyOTAsImV4cCI6MTU2MDE3ODg5MCwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiU0ZSIn0.40t8XvZMgvv0NMlmCfxDy-5q_8s3BcsvlH_XUFJhuv827LCrNUVxNNYko_5FtHlXWd8W97F8dDWdDiRsOUYn_EYDXepLSjBVGYRWzdgkFRIon5KyNuM7DwG2FIPeJGFJ6jooPuDiQDqFoky4nNoVq7dlVpXm8-H4xMb75S71EQIG_84BFmETv_yJ7q4QNgPVJ_nFTnPwE4aMASr8fzqxGsEPsWI_rdZachpQYsLoX6jFmbkWoxVF3jTTmeXZLoasBrlWLbo-OpepSh70HaXLDGJ3zSzNEvwLGTUCVWGEo-jC7OC_dRDUIVDH6r_bY9Yc1Q2xz5UneR5mDSPAetjsHC8Wkfx7GawPFORkxkuzXoC1h2Jb0B-XpXp0E7-aW5-Syj0iXKQ4uH-_e666U3bZTAP2cromFOO_f-jmy-WptCiNR38-J927A4Zo0nS5-g-JAvRs44pogVg-qdv0XNYV3ZtansVoRoZ_yUy_tMrzeRWpTwVvA4Ben5XFOnRtBYe8jCzGqWNW8-0EDkQwbo91stcwjdsqT3LoURl88JlUyRZRwH5kvKypDjA24DuVwxKlEmJ-pS7TRZF8TxX3ZMmQH7pzNV6NT85VHkDJiccgIUW_3Wrke5p7CY-5MBDZMjzpxLjp5kOHvDWLCCplUc9u0SxhI7E1o_Jt-cFxbzvREuc'
```

# Tests
To run tests, you have to configure the test environment :
in .dev.test, update the `DATABASE_URL` with your database information and the `BASE_URI` with your base uri.

Now you can run tests with the following commands :
- `vendor/bin/behat` : run all tests
- `vendor/bin/behat --tags '@tag_name'` : run specific scenarios
- `vendor/bin/behat --name 'element of feature'` : Only execute the feature elements which match part of the given name or regex.

The test files are located in /features/features/api/
