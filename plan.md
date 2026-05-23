# SQLite Setup via Doctrine (No Supabase, No Docker)
To run the app locally without relying on Supabase or an external Docker container, we will switch Doctrine to SQLite in the `.env` file. SQLite stores the database locally in a single file located in your project's `var/` folder. For this to work, PHP must have the built-in SQLite3 PDO driver.

Here are the exact string changes and commands I'll run.

## 1. Edit `.env`
Change `$DATABASE_URL` in your `.env` so it points to the generic `sqlite://` connection provided by default in Symfony instead of PostgreSQL. 

**Removing:**
```env
DATABASE_URL="postgresql://postgres.cgaitpdfkbioaqpcjnzp:Travolt1980%2F@aws-1-eu-west-3.pooler.supabase.com:6543/postgres?serverVersion=15&charset=utf8"
```

**Replacing with:**
```env
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
```

## 2. Generate the SQLite File Structure
With the database configuration fixed, I will trigger Doctrine to build the actual target structure. Since SQLite databases are implicitly loaded on their first access, this works natively in one line: 

```bash
php bin/console doctrine:schema:update --force 
```

*Note: Since earlier we encountered a 'could not find driver' error for sqlite, ensure you have ran `sudo apt install php-sqlite3` before executing this.*

## 3. Verify Routes
When you tried `http://localhost:8000/progress`, you got a 404 because the routes weren't mapped correctly. I will write a simple override for `config/routes.yaml` using pure `echo` to properly index your `src/Controller/` using attributes, so that `#[Route]` annotations will apply nicely.

```bash
echo 'controllers:
    resource:
        path: ../src/Controller/
        namespace: App\Controller
    type: attribute' > config/routes.yaml

php bin/console cache:clear
php bin/console debug:router
```