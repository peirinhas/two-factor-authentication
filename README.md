Assumption:
- The API is the token authentication software, where you can generate as many requests as you want for the mobile, but the last one will always be valid and with a maximum time of 5 minutes.

- In a request if the token is bad, that request is no longer valid.

- There is a master token 'M4s1er' that allows authenticating any request as long as it is in a valid state.

Documentación API:
If you want to search documentation, see API http://localhost:8080/api/v1/docs

Requirements:
After downloading the code, before testing the api, we have to perform the following steps:

/* Start containers */
make run

/* Execute backend commands */
make prepare


Test:
After to do two steps requirements, we can execute the test. There are 10 tests spread over 4 files.

/*comands to execute every file */
php vendor/bin/simple-phpunit tests/Functional/Action/CheckToken.php

php vendor/bin/simple-phpunit tests/Functional/Action/CheckTokenMaster.php

php vendor/bin/simple-phpunit tests/Functional/Action/Verify.php

php vendor/bin/simple-phpunit tests/Functional/Action/VerifyTwoTwiceSameMobile.php                                                             


