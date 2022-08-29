<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EndUser extends Model
{
    use HasFactory;
    /**
     * @var string
     */
    protected $table = 'roles';
    /**
     * @var string[]
     */
    protected $fillable = ['role',
        'role_identifier',
        'role_level',
        'email_verified_at',
        'status'];

    /**
     * Get the user relationship.
     *
     * @return HasMany user data
     */
    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }

}
