<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $email
 * @property Carbon $email_verified_at
 * @property string $password
 * @property int $is_admin
 * @property int $active
 * @property string $twofa_secret
 * @property string $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Image $main_image
 * @property Collection $images
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'is_admin', 'api_token', 'is_verified'
    ];

    public function images(): MorphMany
    {
        return $this->morphMany('App\Models\Image','object');
    }

    public function main_image(): MorphOne
    {
        return $this->morphOne('App\Models\Image','object')->where('is_main',1);
    }

    public function verify($sCode)
    {
        if ($this->verify_code === $sCode && (time() - strtotime($this->verify_code_created_at)) < 60 * 60 * 24) {
            $this->verify_code = null;
            $this->verify_code_created_at = null;
            $this->is_verified = '1';
            $this->email_verified_at = date('Y-m-d H:i:s');
            $this->save();

            return true;
        }

        return false;
    }

    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Track::class, 'favorites');
    }

    public function downloadsTracks()
    {
        return $this->belongsToMany(Track::class, 'downloads');
    }

    public function social_accounts(): HasMany
    {
        return $this->hasMany(SocialAccount::class);
    }
}
