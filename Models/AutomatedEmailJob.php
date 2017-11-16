<?php

namespace Modules\Email\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Email\Models\AutomatedEmail;

class AutomatedEmailJob extends Model
{
    /**
     * @var string
     */
    protected $table = 'netcore_email__automated_email_jobs';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'other_user_id',
        'send_at'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'send_at'
    ];

    /* ---------------- Relations -------------------- */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function automatedEmail(): BelongsTo
    {
        return $this->belongsTo(AutomatedEmail::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('netcore.module-admin.user.model'));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function otherUser(): BelongsTo
    {
        return $this->belongsTo(config('netcore.module-admin.user.model'), 'other_user_id');
    }

    /* ---------------- Other methods -------------------- */

    /**
     * Check if it is time to send
     *
     * @return bool
     */
    public function timeToSend(): bool
    {
        $automatedEmail = $this->automatedEmail;
        if (!$automatedEmail) {
            return false;
        }

        return $automatedEmail->now() ? true : $this->send_at->lte(Carbon::now());
    }
}
