# Laravel Task Manager with Drag and drop feature

This is a simple laravel task manager project with drag and drop feature.
You can add / edit / update and change priorities by drag and drop.

## Initial Functions
* add task
* edit / update task
* delete task
* change priority by drag and drop

## Preparation
You should install PHP, composer and Apache/Mysql server.

## Installation

### 1. Get Source
1) Go to your document root of your apache server.
2) Get the source files in your local apache server.
#### Get project from Git
```git
git clone https://github.com/anydev1103/laravel-drag-drop-taskmanager.git
```
#### Get project from zip
Create a directory named "laravel-drag-drop-taskmanager" in the document root.
Unzip the files into the directory you created.
### 2. Run install laravel framework by the following
```cmd
composer install
```
### 3. Install DB
- Create DB named "taskmanager"
- Open .env file at root directory, and update DB settings there
```txt
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taskmanager
DB_USERNAME=root
DB_PASSWORD=root
```
- Run the command in your root directory ("laravel-drag-drop-taskmanager")
```php
php artisan migrate
```
### 4. Setup VirtualHost
Set your project "DocumentRoot" to ".../laravel-drag-drop-taskmanager/public"
Sample virtualhost settings here:
```config
<VirtualHost 127.0.0.1:80>
    DocumentRoot "/test/coalitiontechnologies/laravel-drag-drop-taskmanager/public"
    ServerName taskmanager.com
</VirtualHost>
```
## How to use
Open your browser and access http://localhost