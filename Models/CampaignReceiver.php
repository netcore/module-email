<?php

namespace Modules\Email\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignReceiver extends Model
{
    /**
     * @var string
     */
    protected $table = 'netcore_email__campaign_receivers';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'email',
        'is_sent',
        'sent_at'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'sent_at'
    ];

    /* ---------------- Relations -------------------- */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('netcore.module-admin.user.model'));
    }

    /* ---------------- Query scopes -------------------- */

    /**
     * @param $query
     * @return mixed
     */
    public function scopeNotSent($query)
    {
        return $query->where('is_sent', false);
    }
}
