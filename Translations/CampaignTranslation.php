<?php

namespace Modules\Email\Translations;

use Illuminate\Database\Eloquent\Model;

class CampaignTranslation extends Model
{
    /**
     * @var string
     */
    protected $table = 'netcore_form__campaign_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'text',
        'locale' // This is very important
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

}
