# Torfs Web Scraping 

## Docker + Laravel + Python + MySQL

## About

## Usage

Navigate to the web-scraping directory.
```text
cd web-scraping/
```

Add .env file based on [.env.example](https://github.com/rokussadee/web_scraping/blob/development/web_scraping/.env.example).

Run the Docker container.
```bash
docker-compose up --build

```

The application is accessed via [http://0.0.0.0:8000](http://0.0.0.0:8000).

The database can be viewed using an application such as [TablePlus](https://tableplus.com/), and is access with the following credentials:
```text
Host: 127.0.0.1 
DB: EL_P4_WebScraping_SQL
User: root
Password: password
```
