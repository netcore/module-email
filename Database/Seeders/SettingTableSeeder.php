<?php

namespace Modules\Email\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Setting\Models\Setting;
use Netcore\Translator\Helpers\TransHelper;

class SettingTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $settings = [
            [
                'group' => 'global',
                'name'  => 'Email address from which to send automated emails/campaigns',
                'key'   => 'send_emails_from',
                'type'  => 'text',
                'meta'  => []
            ]
        ];

        foreach ($settings as $setting) {
            $row = Setting::create($setting);

            $translations = [];
            foreach (TransHelper::getAllLanguages() as $language) {
                $translations[] = [
                    'value' => ''
                ];
            }
            $row->storeTranslations($translations);
        }
    }
}
