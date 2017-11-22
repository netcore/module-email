<?php

namespace Modules\Email\Models;

use Carbon\Carbon;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\Translate\Traits\SyncTranslations;
use Modules\Email\Emails\AutomatedEmails;
use Modules\Email\Models\AutomatedEmailJob;
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
        'type',
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

    /* ---------------- Relations -------------------- */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobs(): HasMany
    {
        return $this->hasMany(AutomatedEmailJob::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function logs(): MorphMany
    {
        return $this->morphMany(EmailLog::class, 'loggable');
    }

    /* ---------------- Query scopes -------------------- */

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', '1');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopePeriod($query)
    {
        return $query->where('type', 'period');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeStatic($query)
    {
        return $query->where('type', 'static');
    }

    /* ---------------- Other methods -------------------- */

    /**
     * @return bool
     */
    public function isStatic()
    {
        return $this->type === 'static';
    }

    /**
     * @return bool
     */
    public function now(): bool
    {
        return $this->period === 'now';
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        $users = collect();

        // TODO: Get users
        $filters = $this->filters;

        return $users->reject(function ($user) {
            return $user->id <= $this->last_user_id;
        });
    }

    /**
     * @return mixed
     */
    public function getPeriod()
    {
        if ($this->now()) {
            return Carbon::now();
        }

        $number = intval(preg_replace('/[^0-9]+/', '', $this->period), 10);
        $period = substr($this->period, -1); // d, w, m, y
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
    public function checkPeriod(): bool
    {
        $period = $this->getPeriod();
        if (!$period) {
            return false;
        }

        $lastSent = $this->last_sent_at ?: $period;

        return $period->gte($lastSent);
    }

    /**
     * @param $user
     * @param $secondUser
     * @return Model
     */
    public function createJob($user, $secondUser = null, $data = []): Model
    {
        $job = $this->jobs()->create([
            'user_id'       => $user->id,
            'other_user_id' => $secondUser ? $secondUser->id : null,
            'send_at'       => $this->getPeriod('add')
        ]);

        foreach ($data as $key => $value)
        {
            $job->variables()->create([
                'key'   =>  $key,
                'value' =>  $value
            ]);
        }

        return $job;
    }

    /**
     * Send email
     *
     * @param AutomatedEmailJob $job
     */
    public function sendTo(AutomatedEmailJob $job): void
    {
        Mail::to($job->user->email)->send(new AutomatedEmails($job));

        $this->logs()->create([
            'email' => $job->user->email,
            'type'  => 'success'
        ]);
    }

    /**
     * Replaces variables in email text
     *
     * @param User $user
     * @param array $data
     * @return string
     */
    public function replaceVariables(User $user, $data = []) : string
    {
        $userReplaceable = method_exists($user, 'getReplaceable') ? $user->getReplaceable() : [];
        $replace         = array_merge($data, $userReplaceable);
        $line            = $this->text;

        foreach ($replace as $key => $value) {
            $line = str_replace(
                [':'.$key, ':'.Str::upper($key), ':'.Str::ucfirst($key)],
                [$value, Str::upper($value), Str::ucfirst($value)],
                $line
            );
        }

        return $line;
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
