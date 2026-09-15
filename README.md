# ToDo aplikácia — Laravel 13

Jednoduchá ToDo aplikácia postavená na Laravel 13 s API pre správu úloh a tagov.

## Požiadavky

- PHP 8.3+
- Composer
- SQLite

## Inštalácia a spustenie

1. Naklonuj repozitár a presuň sa do priečinka projektu:
   ```
   git clone <link-na-repo>
   cd todo-app
   ```

2. Nainštaluj závislosti:
   ```
   composer install
   ```

3. Skopíruj `.env.example` do `.env` a vygeneruj aplikačný kľúč:
   ```
   cp .env.example .env
   php artisan key:generate
   ```

4. V `.env` nastav:
   ```
   DB_CONNECTION=sqlite
   ```

5. Vytvor SQLite databázový súbor:
   ```
   touch database/database.sqlite
   ```
   (na Windows: `type nul > database\database.sqlite`)

6. Spusti migrácie a naplň databázu dummy dátami:
   ```
   php artisan migrate --seed
   ```

7. Spusti aplikáciu:
   ```
   php artisan serve
   ```

Aplikácia beží na `http://127.0.0.1:8000`.

## Autentifikácia (Laravel Sanctum)

Všetky `tasks` endpointy vyžadujú prihlásenie. Postup:

1. Zaregistruj sa alebo sa prihlás — odpoveď obsahuje `token`.
2. Pri každej ďalšej požiadavke na `tasks` endpointy pridaj hlavičku:
   ```
   Authorization: Bearer {token}
   ```

| Metóda | URL | Popis |
|---|---|---|
| POST | `/api/register` | Registrácia (`name`, `email`, `password`) |
| POST | `/api/login` | Prihlásenie (`email`, `password`) |
| POST | `/api/logout` | Odhlásenie (vyžaduje token) |
| GET | `/api/user` | Údaje prihláseného používateľa (vyžaduje token) |

## API endpointy pre úlohy (vyžadujú token)

| Metóda | URL | Popis |
|---|---|---|
| GET | `/api/tasks` | Zoznam úloh, stránkované po 10. Voliteľné parametre: `?tag=nazov`, `?search=text` (hľadá v názve aj popise), `?completed=true/false` |
| POST | `/api/tasks` | Vytvorenie novej úlohy (`name`, `description`) |
| GET | `/api/tasks/{id}` | Detail úlohy |
| PUT/PATCH | `/api/tasks/{id}` | Úprava úlohy (`name`, `description`, `completed`) |
| DELETE | `/api/tasks/{id}` | Zmazanie úlohy |
| POST | `/api/tasks/{id}/tags` | Pridanie tagu (`tag`) |
| DELETE | `/api/tasks/{id}/tags` | Zmazanie tagu (`tag`) |

## Modely a vzťahy

- **Task** — úloha s poľami `name`, `description`, `completed`
- **Tag** — tag s poľom `name`
- Vzťah medzi nimi je many-to-many cez pivot tabuľku `task_tag`

## Testy

Projekt obsahuje Pest testy pokrývajúce vytvorenie, úpravu, zmazanie úlohy a odmietnutie neprihláseného prístupu:

```
php artisan test
```
