# Technical Task

### Run the below commands step by step to set up the project.

## Clone Project
**Folder Name:** `technical-task`
```bash
  git clone https://github.com/cygner/technical-task.git
```

**Change directory**
```bash
  cd technical-task
```

To copy the `.env.example` file to a `.env` file in a Laravel project, you can use several methods depending on your environment:
<br/>Please note sync all environments on `.env.example` and `.env` file
```bash
  cp .env.example .env
```

**Install dependencies**
```bash
  composer install
```

**Install dependencies For Production**
```bash
  composer install --no-dev --optimize-autoloader
```

**Generate a new Application Key**
```bash
  php artisan key:generate
```

**Run Migration**
```bash
  php artisan migrate --seed
```

**Link the storage file**
```bash
  php artisan storage:link
```

**Run the project**
```bash
  php artisan serve
```

<hr/>

## API Documentation

**Published Postman collection with documentation**
<br/><b>Link:<b/> https://documenter.getpostman.com/view/25519244/2sAXqy3Kg6
<br/><b>Postman Collection Path:<b/> /technical-task/postman-collection/collection.json
<hr/>

## Notes

### Laravel Version 11.9
### PHP Version 8.2
