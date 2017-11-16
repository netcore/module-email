<?php

namespace Modules\Email\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class EmailLog extends Model
{
    /**
     * @var string
     */
    protected $table = 'netcore_email__email_logs';

    /**
     * @var array
     */
    protected $fillable = [
        'email',
        'type',
        'message'
    ];

    /* ---------------- Relations -------------------- */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function loggale(): MorphTo
    {
        return $this->morphTo();
    }
}
