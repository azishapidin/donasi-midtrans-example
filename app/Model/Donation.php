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
        'donation_type',
        'amount',
        'note',
    ];
}
