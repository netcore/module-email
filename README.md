## Module - Email
This module was made for easy management of automated emails and email campaigns.

## Pre-installation

This package is part of Netcore CMS ecosystem and is only functional in a project that has following packages
installed:

1. https://github.com/netcore/netcore
2. https://github.com/netcore/module-admin
3. https://github.com/netcore/module-translate
4. https://github.com/netcore/module-user
5. https://github.com/netcore/module-setting

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

 - Create "lock_files" folder in "storage" and add .gitignore

 - Add automated emails command to scheduling in "app/Console/Kernel.php"
```
    $schedule->command('automated-emails:send')->everyMinute();
```

- Set queue driver to redis

- In your supervisor configuration set --timeout to 3600 or larger if a project will have large user base to send campaigns to

- Add `Modules\User\Traits\ReplaceableAttributes` trait to your `User` model and set public property `$replaceable` which you want dynamically use in email templates:

```
public $replaceable = [
    'first_name',
    'last_name',
    'email'
];
``` 

and set `$replaceablePrefix` to prefix the replaceable attributes.

- Add "getFilters" and "getFilterQuery" methods to User model, like so:
```
    public function getFilters()
    {
        return [
            'is_email_verified' => [
                'name'   => 'Email verified?',
                'type'   => 'select', // Available types: text, select, multi-select, from-to
                'values' => [-1 => 'Not important', 1 => 'Yes', 0 => 'No']
            ],
            'country'           => [
                'name'   => 'Country',
                'type'   => 'multi-select', // Available types: text, select, multi-select, from-to
                'values' => Country::all()->mapWithKeys(function ($country) {
                    return [
                        $country->id => $country->name
                    ];
                })
            ],
        ];
    }
    
    public function getFilterQuery()
    {
        $filters = request()->get('filters', []);
        $query = User::select('id', 'email');
        
        foreach ($this->getFilters() as $field => $filter) {
            $data = (isset($filters[$field])) ? $filters[$field] : -1;
            
            if ($data < 0) {
                continue;
            }
    
            if ($filter['type'] == 'multi-select') {
                $query->whereHas($field, function ($q) use ($data) {
                    $q->whereIn('id', $data);
                });
            } elseif ($filter['type'] == 'from-to') {

                if (($data['from'] && $data['to']) && $data['to'] > $data['from']) {
                    $query->whereBetween($field, [$data['from'], $data['to']]);
                }

            } else {
                $query->where($field, $data);
            }
        }
    
        return $query;
    }
``` 
 
### Configuration

 - Configuration file is available at config/netcore/module-email.php
 
 ### Seeding automated emails
 
 ```php
    use Modules\Email\Models\AutomatedEmail;
    use Netcore\Translator\Helpers\TransHelper;
    
    $emails = [
         [
             'key'          => 'verify_email',
             'period'       => 'now',
             'type'         => 'static', // Available types: static, period, interval
             'is_active'    => true,
             'translations' => [
                 'name' => 'Email verification',
                 'text' => 'Please verify your email by clicking on the link: <a href="[VERIFICATION_URL]">[VERIFICATION_URL]</a>'
             ]
         ]
     ];
    
     foreach ($emails as $email) {
         $emailModel = AutomatedEmail::create(array_except($email, 'translations'));
    
         $translations = [];
         foreach (TransHelper::getAllLanguages() as $language) {
             $translations[$language->iso_code] = $email['translations'];
         }
         
         $emailModel->updateTranslations($translations);
     }
 ```

### Usage

- Add/remove email to/from subscriptions list
```php
    email()->subscribe('example@example.com');
    email()->unsubscribe('example@example.com');
```

- Send automated email
```php
    email()->send('verify_email', $user, [
        'VERIFICATION_URL' => $verificationUrl
    ]);
```
