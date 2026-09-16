# ToDo aplikácia — Laravel 13

Jednoduchá ToDo aplikácia postavená na Laravel 13 s API pre správu úloh a tagov, a s webovým rozhraním postaveným na Inertia.js \+ Vue 3\.

## Požiadavky

- PHP 8.3+  
- Composer  
- Node.js \+ npm  
- SQLite

## Inštalácia a spustenie

1. Naklonuj repozitár a presuň sa do priečinka projektu:  
     
   git clone https://github.com/SamuelHejda/todo-app.git  
     
   cd todo-app  
     
2. Nainštaluj PHP závislosti:  
     
   composer install  
     
3. Nainštaluj JS závislosti:  
     
   npm install  
     
4. Skopíruj `.env.example` do `.env` a vygeneruj aplikačný kľúč:  
     
   cp .env.example .env  
     
   php artisan key:generate  
     
5. V `.env` nastav:  
     
   DB\_CONNECTION=sqlite  
     
6. Vytvor SQLite databázový súbor:  
     
   touch database/database.sqlite  
     
   (na Windows: `type nul > database\database.sqlite`)  
     
7. Spusti migrácie a naplň databázu dummy dátami:  
     
   php artisan migrate \--seed  
     
8. Zbuilduj frontend (pre produkciu):  
     
   npm run build  
     
   Pri vývoji môžeš namiesto toho nechať bežať `npm run dev` na pozadí (Vite dev server s live reloadom).  
     
9. Spusti aplikáciu:  
     
   php artisan serve

Aplikácia beží na `http://127.0.0.1:8000`.

## Webové rozhranie

Na `http://127.0.0.1:8000/` je dostupné jednoduché webové rozhranie (Inertia.js \+ Vue 3), kde je možné:

- vytvárať nové úlohy  
- označiť úlohu ako dokončenú  
- vymazať úlohu  
- pridať/zmazať tag k úlohe

Toto rozhranie beží cez bežnú Laravel session (nie cez API token).

## Autentifikácia API (Laravel Sanctum)

Všetky `/api/tasks` endpointy vyžadujú prihlásenie cez token. Postup:

1. Zaregistruj sa alebo sa prihlás — odpoveď obsahuje `token`.  
2. Pri každej ďalšej požiadavke na `tasks` endpointy pridaj hlavičku:  
     
   Authorization: Bearer {token}

| Metóda | URL | Popis |
| :---- | :---- | :---- |
| POST | `/api/register` | Registrácia (`name`, `email`, `password`) |
| POST | `/api/login` | Prihlásenie (`email`, `password`) |
| POST | `/api/logout` | Odhlásenie (vyžaduje token) |
| GET | `/api/user` | Údaje prihláseného používateľa (vyžaduje token) |

## API endpointy pre úlohy (vyžadujú token)

| Metóda | URL | Popis |
| :---- | :---- | :---- |
| GET | `/api/tasks` | Zoznam úloh, stránkované po 10\. Voliteľné parametre: `?tag=nazov`, `?search=text` (hľadá v názve aj popise), `?completed=true/false` |
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

php artisan test  
