<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Team extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'personal_team',
    ];

    /**
     * Define la relación con el modelo User
     *
     * @return BelongsTo<User, Team>
     */
    public function owner(): BelongsTo
    {
        /** @var BelongsTo<User, Team> */
        return $this->belongsTo(User::class, 'user_id');
    }
}
