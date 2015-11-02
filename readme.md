Netinteractive\Seed
=====================


## Service Providers
- SeedServiceProvider - provider register all commands

## Commands
- seed:ni-test-data - command that based on config/test.php seeds data base. run it with --env=testing parameter

## Examples
- seed:ni-test-data - example seeder config file can be cound in w config/test.dist.php

## Installation

Add to composer.json to required section
```
#!php
"netinteractive/seed": "1.0.*,
```

in repositories:
```
#!php
{
    "type": "git",
    "url": "git@bitbucket.org:niteam/laravel-seed"
},
```

Add provider to app/config/app.php:
```
Netinteractive\Seed\SeedServiceProvider
```

## Changelog

### 1.0.0 - 0.1
    init
