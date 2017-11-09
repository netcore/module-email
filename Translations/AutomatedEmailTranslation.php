<?php

namespace Modules\Email\Translations;

use Illuminate\Database\Eloquent\Model;

class AutomatedEmailTranslation extends Model
{
    /**
     * @var string
     */
    protected $table = 'netcore_email__automated_email_translations';

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
