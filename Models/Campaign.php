<?php

namespace Modules\Email\Models;

use App\Models\User;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Traits\SyncTranslations;
use Modules\Email\Translations\CampaignTranslation;

class Campaign extends Model
{
    use Translatable, SyncTranslations;

    /**
     *  Statuses
     */
    const STATUSES = [
        'not_sent' => 'Not sent',
        'sent'     => 'Sent',
        'sending'  => 'Sending in progress',
        'stopped'  => 'Stopped',
        'error'    => 'Error'
    ];

    /**
     * @var string
     */
    protected $table = 'netcore_email__campaigns';

    /**
     * @var array
     */
    protected $fillable = [
        'status',
        'last_user_id'
    ];

    /**
     * @var string
     */
    public $translationModel = CampaignTranslation::class;

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'netcore_email__campaign_user')
                ->using(CampaignPivot::class)
                ->withPivot([
                    'is_sent',
                    'sent_at'
                ])->withTimestamps();
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return self::STATUSES[$this->status];
    }

    /* ------- Statuses -----------*/

    /**
     * @return bool
     */
    public function notStarted()
    {
        return $this->status == 'not_sent';
    }

    /**
     * @return bool
     */
    public function isDone()
    {
        return $this->status == 'sent';
    }

    /**
     * @return bool
     */
    public function inProgress()
    {
        return $this->status == 'sending';
    }

    /**
     * @return bool
     */
    public function isStopped()
    {
        return in_array($this->status, ['stopped', 'error']);
    }

    /**
     *  Start email campaign
     */
    public function start()
    {
        file_put_contents($this->lockFile(), time());

        $this->update([
            'status' => 'sending'
        ]);

        // Launch command
        \Artisan::queue('email-campaign:send', [
            'campaign' => $this->id
        ]);
    }

    /**
     *  Stop email campaign
     *
     * @param null   $userId
     * @param string $status
     */
    public function stop($userId = null, $status = 'stopped')
    {
        if ($this->lockFileExists()) {
            unlink($this->lockFile());
        }

        $this->status = $this->isDone() ? 'sent' : $status;
        $this->last_user_id = $userId ?: $this->last_user_id;
        $this->save();
    }

    /**
     * @return bool
     */
    public function lockFileExists()
    {
        return file_exists($this->lockFile());
    }

    /**
     * @return string
     */
    private function lockFile()
    {
        return storage_path('lock_files/.email-campaign-in-progress-' . $this->id . '.lock');
    }
}
