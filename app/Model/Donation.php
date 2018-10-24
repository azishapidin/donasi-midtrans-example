<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    /**
     * Fillable attribute.
     *
     * @var array
     */
    protected $fillable = [
        'donor_name',
        'donor_email',
        'donation_type',
        'amount',
        'note',
    ];

    /**
     * Set status to Pending
     *
     * @return void
     */
    public function setPending()
    {
        # code...
    }

    /**
     * Set status to Success
     *
     * @return void
     */
    public function setSuccess()
    {
        # code...
    }

    /**
     * Set status to Failed
     *
     * @return void
     */
    public function setFailed()
    {
        # code...
    }
}
