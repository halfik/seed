Netinteractive\Seed
=====================


#Usługi
- SeedServiceProvider - usługa rejestruje komendy seedujace

##Komendy
- seed:ni-test-data - na podstawie config/test.php seeduje testowa baze danych. trzeba odpalac z parameterm --env=testing
- seed:ni-data - uruchamia komende seedująca dane na podstawie configu simple-data-seeder.php

## Przykłady
Przykładowy plik konfiguracyjny seedera znajduje się w simple-data-seeder.php

## Installation

Dodajemy w composer.json w sekcji required
```
#!php
"netinteractive/seed": "0.0.*,
```

w respositories:
```
#!php
{
    "type": "git",
    "url": "git@bitbucket.org:niteam/laravel-seed"
},
```

w app/config/app.php dodajemy provider:

Netinteractive\Seed\SeedServiceProvider

## Changelog

### 0.0.2
    - paczka wydaje sie stabilna

### 0.0.1
 -  dodanie Commands\TestDataSeedCommand