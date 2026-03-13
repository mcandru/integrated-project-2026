## First-time Setup

Before running the project for the first time, complete these two steps:

### 1. Create your `.env` file

Make a copy of `.env.example` in the same folder and rename it to `.env`. You can leave the default values as-is for local development.

## Getting Started

To get started, run:

```bash
docker compose up -d
```

To stop running the containers and delete them, run:

```bash
docker compose down
```

Once running, the application is available at:

- **http://localhost:8080** — the application
- **http://localhost:8081** — PHPMyAdmin (database management)

## Setting Up Your Database

The sample data in `src/sql/news.sql` is loaded automatically when the containers first start. To replace it with your own data from your Excel spreadsheet:

### 1. Convert your spreadsheet to CSV

Use the [Excel to CSV converter](https://gisthost.github.io/?7c1b1122c676a2efa7aeb680aec74629) to export each sheet from your spreadsheet as a separate CSV file.

### 2. Delete the existing sample data

Before importing your own data, delete the existing rows. Open PHPMyAdmin at **http://localhost:8081**, select the `news_db` database, and run the following in the **SQL tab**:

```sql
DELETE FROM stories;
DELETE FROM authors;
DELETE FROM categories;
DELETE FROM locations;
```

**The deletion order matters.** `stories` must be deleted first because it has foreign keys referencing the other three tables. If you try to delete `authors`, `categories`, or `locations` first, MySQL will reject the deletion.

### 3. Import into PHPMyAdmin

Open PHPMyAdmin at **http://localhost:8081**, select the `news_db` database, and import each CSV using the Import tab.

**Skip the header row.** When importing, set **"Skip this number of queries (for SQL) starting from the first one:"** to `1`. This prevents PHPMyAdmin from trying to insert your header row as data.

**The import order matters**. The `stories` table references the other three tables via foreign keys, so import them first or MySQL will reject the import:

1. `authors`
2. `categories`
3. `locations`
4. `stories`

### 4. Save your database

Export your database so it can be restored on another machine:

1. In PHPMyAdmin, select `news_db` and go to **Export → Quick → SQL**
2. Overwrite `src/sql/news.sql` with the downloaded file

## Making Database Changes

To add a column or a new table after the initial setup:

### Adding a column

1. Run the `ALTER TABLE` statement in PHPMyAdmin's **SQL tab**:
   ```sql
   ALTER TABLE stories ADD COLUMN featured BOOLEAN DEFAULT FALSE;
   ```
2. Add the property to the relevant model class in `src/classes/`
3. Update the `save()` method in that class to include the new column
4. Export the database and overwrite `src/sql/news.sql`

### Adding a table

1. Run a `CREATE TABLE` statement in PHPMyAdmin's **SQL tab**
2. Create a new model class in `src/classes/`
3. Export the database and overwrite `src/sql/news.sql`

## Data Models

The application uses Active Record pattern models for database access. All models are located in `src/classes/`.

See [DATA_MODELS.md](docs/DATA_MODELS.md) for full documentation.

## How it works

- Docker compose creates three containers: a MySQL database, an Apache PHP server, and a PHPMyAdmin server.
- The compose file mounts `src/sql/news.sql` into MySQL's `/docker-entrypoint-initdb.d` directory. MySQL automatically runs this file on first start to initialise the database.
- `docker/apache-php/Dockerfile` installs the PDO (PHP Data Objects) extension, which provides the API for connecting to MySQL.
- A DB connection is initialised in `src/classes/DB.php` using a Singleton to ensure the connection is created once and reused throughout the app.
- Model classes in `src/classes/` provide data access using an Active Record pattern.
