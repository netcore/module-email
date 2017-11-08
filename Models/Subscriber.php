<?php

namespace Modules\Email\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    /**
     * @var string
     */
    protected $table = 'netcore_email__subscribers';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'email'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
