<?php

namespace Modules\Email\Models;

use Carbon\Carbon;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Traits\SyncTranslations;
use Modules\Email\Translations\AutomatedEmailTranslation;

class AutomatedEmail extends Model
{
    use Translatable, SyncTranslations;

    /**
     * @var string
     */
    protected $table = 'netcore_email__automated_emails';

    /**
     * @var array
     */
    protected $fillable = [
        'key',
        'period',
        'is_active',
        'last_sent_at',
        'last_user_id'
    ];

    /**
     * @var array
     */
    protected $dates = ['last_sent_at'];

    /**
     * @var string
     */
    public $translationModel = AutomatedEmailTranslation::class;

    /**
     * @var array
     */
    public $translatedAttributes = [
        'name',
        'text'
    ];

    /**
     * @var array
     */
    protected $with = ['translations'];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', '1');
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        $users = collect();

        // TODO: Get users

        return $users->reject(function ($user) {
            return $user->id <= $this->last_user_id;
        });
    }

    /**
     * @return mixed
     */
    public function getPeriod()
    {
        $number = intval(preg_replace('/[^0-9]+/', '', $this->period), 10);
        $period = substr($this->period, -1); // h, m, d etc.
        $method = $this->getMethod($period);
        if (!$method) {
            return false;
        }

        $carbon = Carbon::now()->{$method}($number);

        return $carbon;
    }

    /**
     * @return bool
     */
    public function checkPeriod()
    {
        $period = $this->getPeriod();
        if (!$period) {
            return false;
        }

        $lastSent = $this->last_sent_at ?: $period;

        return $period->gte($lastSent);
    }

    /**
     * @param $period
     * @return mixed
     */
    private function getMethod($period)
    {
        $array = [
            'h' => 'subHours',
            'd' => 'subDays',
            'w' => 'subWeeks',
            'm' => 'subMonthsNoOverflow',
            'y' => 'subYears'
        ];

        return array_get($array, $period, null);
    }
}
