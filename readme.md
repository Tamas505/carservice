# CarService – Online Időpontfoglaló Rendszer

A CarService egy PHP és MySQL alapú időpontfoglaló rendszer autószervizek számára.

## Funkciók

- Heti naptár nézet (hétfőtől péntekig)
- Időpontfoglalás 8:00–17:00 között
- Foglalt időpontok automatikus tiltása
- Telefonszám validálás
- Email cím kezelése
- Automatikus visszaigazoló email küldése
- Modern, kártyás felület

## Használt technológiák

- PHP
- MySQL
- PDO
- HTML5
- CSS3

## Telepítés

1. Másold a projektet az XAMPP `htdocs` mappájába.
2. Importáld a `database.sql` fájlt phpMyAdmin segítségével.
3. Másold a `config.example.php` fájlt `config.php` néven.
4. Szükség esetén módosítsd az adatbázis-beállításokat.
5. Nyisd meg a böngészőben:

   http://localhost/carservice/

## Email küldés

A rendszer PHP `mail()` függvényt használ a foglalás visszaigazolásához.

Megjegyzés: helyi XAMPP környezetben az email küldés további konfigurációt igényelhet. A funkció megfelelő szerverbeállítások mellett működik.

## Adatbázis

A szükséges táblák létrehozásához használd a `database.sql` fájlt.

## Készítette

Kőműves Tamás