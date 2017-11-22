<?php

namespace Modules\Email\Models;

use Illuminate\Database\Eloquent\Model;

class AutomatedEmailJobVariable extends Model
{
    /**
     * Sets the table name
     *
     * @var string
     */
    protected $table = 'netcore_email__automated_email_job_variables';

    /**
     * Disables created_at and updated_at fields
     *
     * @var bool
     */
    public $timestamps = false;

}
