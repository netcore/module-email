<?php

namespace Modules\Email\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Email\Models\AutomatedEmail;
use Modules\Email\Models\AutomatedEmailJobVariable;

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variables(): HasMany
    {
        return $this->hasMany(AutomatedEmailJobVariable::class);
    }

    /* ---------------- Accessors -------------------- */

    /**
     * Returns variable list
     *
     * @return array
     */
    public function getVariableListAttribute(): array
    {
        return $this->variables
            ->pluck('value', 'key')
            ->toArray();
    }
}
