<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venues extends Model
{
    use HasFactory;
    /**
     * @var string
     */
    protected $table = 'venues';
    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'password',
        'description',
        'category',
        'place_name',
        'website',
        'phone_number',
        'address',
        'image_path',
        'latitude',
        'longitude',
        'opening_time',
        'closing_time',
        'coupon_code',
        'status'];

}
