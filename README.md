Assumption:
- The API is the token authentication software, where you can generate as many requests as you want for the mobile, but the last one will always be valid and with a maximum time of 5 minutes.

- In a request if the token is bad, that request is no longer valid.

- There is a master token 'M4s1er' that allows authenticating any request as long as it is in a valid state.

If you want to search documentation, see API http://localhost:8080/api/v1/docs

Requirements:
Place them at the height of the project's src folder and run the following commands:

/* Start containers */
make run

/* Execute backend commands */
make prepare