<?php

namespace Modules\Email\Models;

use Carbon\Carbon;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Mail;
use Modules\Email\Traits\ReplaceVariables;
use Modules\Translate\Traits\SyncTranslations;
use Modules\Email\Emails\AutomatedEmails;
use Modules\Email\Models\AutomatedEmailJob;
use Modules\Email\Translations\AutomatedEmailTranslation;

class AutomatedEmail extends Model
{
    use Translatable, SyncTranslations, ReplaceVariables;

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
        'filters',
        'is_active',
        'last_sent_at',
        'last_user_id'
    ];

    /**
     * @var array
     */
    protected $dates = ['last_sent_at'];

    /**
     * @var array
     */
    protected $casts = [
        'filters' => 'array'
    ];

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

    /**
     * @param $query
     * @return mixed
     */
    public function scopeInterval($query)
    {
        return $query->where('type', 'interval');
    }

    /* ---------------- Other methods -------------------- */

    /**
     * @return bool
     */
    public function isStatic(): bool
    {
        return $this->type === 'static';
    }

    /**
     * @return bool
     */
    public function isPeriod(): bool
    {
        return $this->type === 'period';
    }

    /**
     * @return bool
     */
    public function isInterval(): bool
    {
        return $this->type === 'interval';
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
        $userModel = config('netcore.module-admin.user.model');

        $users = $userModel::select('*');

        if ($this->isInterval()) {
            $users->whereRaw('DATE_ADD(created_at, ' . $this->getInterval() . ') BETWEEN DATE_SUB(NOW(), INTERVAL 1 MINUTE) AND NOW()');
        }

        if ($this->filters) {
            foreach ($this->filters as $clauses) {
                foreach ($clauses as $clause => $query) {
                    $users->{$clause}(is_array($query) ? implode('', $query) : $query);
                }
            }
        }

        if (!$this->last_user_id) {
            return $users;
        }

        return $users->where('id', '>', $this->last_user_id);
    }

    /**
     * @param string $action
     * @return mixed
     */
    public function getPeriod($action = 'sub')
    {
        if ($this->now()) {
            return Carbon::now();
        }

        $number = intval(preg_replace('/[^0-9]+/', '', $this->period), 10);
        $period = substr($this->period, -1); // d, w, m, y
        $method = $this->getMethod($period, $action);
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
     * @return string
     */
    public function getInterval(): string
    {
        $number = intval(preg_replace('/[^0-9]+/', '', $this->period), 10);
        $period = substr($this->period, -1); // h, m, d etc.
        $array = [
            'd' => 'DAY',
            'w' => 'WEEK',
            'm' => 'MONTH',
            'y' => 'YEAR'
        ];

        return 'INTERVAL ' . $number . ' ' . array_get($array, $period, 'DAY');
    }

    /**
     * @param       $user
     * @param array $variables
     * @return Model
     */
    public function createJob($user, $variables = []): Model
    {
        $job = $this->jobs()->create([
            'user_id' => $user->id,
            'send_at' => $this->getPeriod('add')
        ]);

        foreach ($variables as $key => $value) {
            $job->variables()->create([
                'key'   => $key,
                'value' => $value
            ]);
        }

        return $job;
    }

    /**
     * Send email
     *
     * @param User $user
     * @param null $job
     */
    public function sendTo(User $user, $job = null): void
    {
        Mail::to($user->email)->send(new AutomatedEmails($this, $user, $job));

        $this->logs()->create([
            'email' => $user->email,
            'type'  => 'success'
        ]);
    }

    /**
     * @param $period
     * @param $action
     * @return mixed
     */
    private function getMethod($period, $action)
    {
        $array = [
            'd' => $action === 'sub' ? 'subDays' : 'addDays',
            'w' => $action === 'sub' ? 'subWeeks' : 'addWeeks',
            'm' => $action === 'sub' ? 'subMonthsNoOverflow' : 'addMonthsNoOverflow',
            'y' => $action === 'sub' ? 'subYears' : 'addYears',
        ];

        return array_get($array, $period, null);
    }
}
