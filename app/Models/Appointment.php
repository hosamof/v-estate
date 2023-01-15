<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'address',
        'date',
        'customer_name',
        'customer_phone',
        'est_start',
        'est_end'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['agent'];

    /**
     * user(agent) relation
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
