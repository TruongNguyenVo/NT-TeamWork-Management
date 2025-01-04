Start Project:
Open Powershell
```
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
Invoke-RestMethod -Uri https://get.scoop.sh | Invoke-Expression
```
Install Symfony
```
scoop install symfony-cli
```
Setting Database in .env file
```
DATABASE_URL="mysql://root:@127.0.0.1:3306/trainingproject?serverVersion=8.0.32&charset=utf8mb4"
```
Create database
```
php bin/console doctrine:database:create
```
Create Entity
```
php bin/console make:entity category
```
Make migration
```
php bin/console make:migration
```
Migrate
```
php bin/console doctrine:migrations:migrate
```
Create Controller
```
php bin/console make:crud
```

Error
+ Add serverVersion=mariadb-10.4.11
```
DATABASE_URL="mysql://root:@127.0.0.1:3306/trainingproject?serverVersion=mariadb-10.4.11&charset=utf8mb4"
```