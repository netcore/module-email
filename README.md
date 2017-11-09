## Module - Email
This module was made for easy management of automated emails and email campaigns.

## Pre-installation

This package is part of Netcore CMS ecosystem and is only functional in a project that has following packages
installed:

1. https://github.com/netcore/netcore
2. https://github.com/netcore/module-admin
3. https://github.com/netcore/module-translate

### Installation

 - Require this package using composer
```
    composer require netcore/module-email
```

 - Publish assets/configuration/migrations
```
    php artisan module:publish Email
    php artisan module:publish-config Email
    php artisan module:publish-migration Email
    php artisan migrate
```

 - Add automated emails command to scheduling in "app/Console/Kernel.php"
```
    $schedule->command('automated-emails:send')->hourly();
```
 
### Configuration

 - Configuration file is available at config/netcore/module-email.php

### Usage

- Add/remove email to/from subscriptions list
```php
    email()->subscribe('example@example.com');
    email()->unsubscribe('example@example.com');
```
