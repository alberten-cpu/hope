<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deals extends Model
{
    use HasFactory;
    /**
     * @var string
     */
    protected $table = 'deals';
    /**
     * @var string[]
     */
    protected $fillable = [
        'venue_id',
        'title',
        'description',
        'image_path',
        'latitude',
        'longitude',
        'opening_time',
        'closing_time',
        'status'];
}
