## Installation

Clone the repository.

```bash
git clone https://github.com/daenuli/1d1b14010aa476be05ff56c67ffd2b7b.git levartech
```

Switch to the repository folder.

```bash
cd levartech
```

Create and start containers. When running the container, a database will be imported and the Redis Queue will run automatically.

```bash
docker-compose up
```

Install required packages

```bash
docker exec -it php_engine composer install
```

Update mail config in the .env file. In this example, I use mailtrap.io

Get access token 

```bash
curl -u levartech:levartech123 http://localhost:8001/token.php -d 'grant_type=client_credentials'
```

Send email

```bash
curl http://localhost:8001/email.php -d 'access_token=7c3b76064c1767b7c62423a6a916ac3c10610741&email=obama@mail.com&title=Hello World&text=How are you ?'
```

List of sent email
```bash
http://localhost:8001/list.php
```

Please check your destination email to ensure that the email has been sent.