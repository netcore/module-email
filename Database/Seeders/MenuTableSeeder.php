<?php

namespace Modules\Email\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Models\Menu;
use Netcore\Translator\Helpers\TransHelper;

class MenuTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $menuItems = [
            'leftAdminMenu' => [
                [
                    'name'            => 'Emails',
                    'icon'            => 'fa fa-envelope',
                    'type'            => 'url',
                    'value'           => '#',
                    'module'          => 'Email',
                    'is_active'       => 1,
                    'parameters'      => json_encode([]),
                    'active_resolver' => 'admin::automated_emails.*,admin::campaigns.*,admin::subscribers.*',
                    'children'        => [
                        [
                            'name'            => 'Automated emails',
                            'type'            => 'route',
                            'value'           => 'admin::automated_emails.index',
                            'module'          => '',
                            'is_active'       => 1,
                            'active_resolver' => 'admin::automated_emails.*',
                            'parameters'      => json_encode([])
                        ],
                        [
                            'name'            => 'Campaigns',
                            'type'            => 'route',
                            'value'           => 'admin::campaigns.index',
                            'module'          => '',
                            'is_active'       => 1,
                            'active_resolver' => 'admin::campaigns.*',
                            'parameters'      => json_encode([])
                        ],
                        [
                            'name'            => 'Subscribers',
                            'type'            => 'route',
                            'value'           => 'admin::subscribers.index',
                            'module'          => '',
                            'is_active'       => 1,
                            'active_resolver' => 'admin::subscribers.*',
                            'parameters'      => json_encode([])
                        ],
                    ]
                ],
            ]
        ];

        foreach ($menus as $key => $items) {
            $menu = Menu::firstOrCreate([
                'key' => $key
            ]);

            $translations = [];
            foreach (TransHelper::getAllLanguages() as $language) {
                $translations[$language->iso_code] = [
                    'name' => ucwords(preg_replace(array('/(?<=[^A-Z])([A-Z])/', '/(?<=[^0-9])([0-9])/'), ' $0', $key))
                ];
            }
            $menu->updateTranslations($translations);

            foreach ($items as $item) {
                $row = $menu->items()->firstOrCreate(array_except($item, ['name', 'value', 'parameters', 'children']));

                $translations = [];
                foreach (TransHelper::getAllLanguages() as $language) {
                    $translations[$language->iso_code] = [
                        'name'       => $item['name'],
                        'value'      => $item['value'],
                        'parameters' => $item['parameters']
                    ];
                }
                $row->updateTranslations($translations);

                foreach ($item['children'] as $child) {
                    $child['menu_id'] = $menu->id;

                    $c = $row->children()->firstOrCreate(array_except($child, ['name', 'value', 'parameters']));
                    $translations = [];
                    foreach (TransHelper::getAllLanguages() as $language) {
                        $translations[$language->iso_code] = [
                            'name'       => $child['name'],
                            'value'      => $child['value'],
                            'parameters' => $child['parameters']
                        ];
                    }
                    $c->updateTranslations($translations);
                }
            }
        }
    }
}
