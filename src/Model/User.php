<?php

namespace TheBachtiarz\Auth\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function __construct()
    {
        $this->fillable = $this->getFillableData();
    }

    // ? Public Methods

    // ? Private Methods
    /**
     * get fillable data by condition
     *
     * @return array
     */
    private function getFillableData(): array
    {
        $result = [];

        if (tbauthconfig('user_auth_identity_method') === 'email')
            $result = ['email', 'email_verified_at'];
        if (tbauthconfig('user_auth_identity_method') === 'username')
            $result = ['username'];

        $result[] = 'password';

        return $result;
    }

    // ? Setter Modules
}
