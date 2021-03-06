<?php

namespace Modules\Email\Models;

use Dimsav\Translatable\Translatable;
use File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Mail;
use Modules\Email\Emails\CampaignEmail;
use Modules\Email\Traits\ReplaceVariables;
use Modules\Email\Translations\CampaignTranslation;
use Modules\Translate\Traits\SyncTranslations;

class Campaign extends Model
{
    use Translatable, SyncTranslations, ReplaceVariables;

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
        'last_email'
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

    /* ---------------- Relations -------------------- */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receivers(): HasMany
    {
        return $this->hasMany(CampaignReceiver::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function logs(): MorphMany
    {
        return $this->morphMany(EmailLog::class, 'loggable');
    }

    /* ---------------- Other methods -------------------- */

    /**
     * Get readable status
     *
     * @return string
     */
    public function getStatus(): string
    {
        return array_get(self::STATUSES, $this->status, 'Unknown');
    }

    /**
     * Campaign has not yet sent
     *
     * @return bool
     */
    public function notStarted(): bool
    {
        return $this->status == 'not_sent';
    }

    /**
     * Campaign has been sent
     *
     * @return bool
     */
    public function isDone(): bool
    {
        return $this->status == 'sent';
    }

    /**
     * Campaign in progress
     *
     * @return bool
     */
    public function inProgress(): bool
    {
        return $this->status == 'sending';
    }

    /**
     * Campaign stopped
     *
     * @return bool
     */
    public function isStopped(): bool
    {
        return in_array($this->status, ['stopped', 'error']);
    }

    /**
     * @return mixed
     */
    public function getReceivers()
    {
        if (!$this->last_email) {
            return $this->receivers();
        }

        return $this->receivers()->where('id', '>', $this->last_email);
    }

    /**
     * Send email
     *
     * @param CampaignReceiver $receiver
     * @return void
     */
    public function sendTo(CampaignReceiver $receiver): void
    {
        Mail::to($receiver->email)->send(new CampaignEmail($this, $receiver->user));

        $this->logs()->create([
            'email' => $receiver->email,
            'type'  => 'success'
        ]);
    }

    /**
     *  Start email campaign
     *
     * @return void
     */
    public function start(): void
    {
        // Create directory for lock files if it does not exist
        $path = storage_path('lock_files');
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 775);
            // Add .gitignore
            File::put($path . '/.gitignore', '*' . PHP_EOL . '!.gitignore');
        }

        file_put_contents($this->lockFile(), time());

        // Launch command
        \Artisan::queue('email-campaign:send', [
            'campaign' => $this->id
        ]);
    }

    /**
     * Stop email campaign
     *
     * @param string $status
     * @param null   $lastReceiver
     * @return void
     */
    public function stop($status = 'stopped', $lastReceiver = null): void
    {
        if ($this->lockFileExists()) {
            unlink($this->lockFile());
        }

        $this->status = $this->isDone() ? 'sent' : $status;
        $this->last_email = ($status !== 'sent') ? ($lastReceiver ?: $this->last_email) : null;
        $this->save();
    }

    /**
     * Check if lock file exists
     *
     * @return bool
     */
    public function lockFileExists(): bool
    {
        return file_exists($this->lockFile());
    }

    /**
     * Lock file path
     *
     * @return string
     */
    private function lockFile(): string
    {
        return storage_path('lock_files/.email-campaign-in-progress-' . $this->id . '.lock');
    }
}
