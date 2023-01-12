<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;

/**
 * Class Link
 *
 * @mixin Builder
 * @package App
 */
class Link extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['ends_at', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'country_target' => 'object',
        'platform_target' => 'object',
        'language_target' => 'object',
        'rotation_target' => 'object'
    ];

    /**
     * Get the total clicks count under the Link.
     *
     * @return int
     */
    public function getTotalClicksAttribute()
    {
        return $this->hasMany('App\Models\Stat')->where('link_id', $this->id)->count();
    }

    /**
     * Get the Space of the Link.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function space()
    {
        return $this->hasOne('App\Models\Space', 'id', 'space_id');
    }

    /**
     * Get the Domain of the Link.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function domain()
    {
        return $this->hasOne('App\Models\Domain', 'id', 'domain_id');
    }

    /**
     * Get the Stats of the Link.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stats()
    {
        return $this->hasMany('App\Models\Stat')->where('link_id', $this->id);
    }

    /**
     * Get the user that owns the Link.
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    /**
     * Get the Pixels of the Link.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pixels() {
        return $this->belongsToMany('App\Models\Pixel');
    }

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeSearchTitle(Builder $query, $value)
    {
        return $query->where('title', 'like', '%' . $value . '%');
    }

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeSearchUrl(Builder $query, $value)
    {
        return $query->where('url', 'like', '%' . $value . '%');
    }

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeSearchAlias(Builder $query, $value)
    {
        return $query->where('alias', 'like', '%' . $value . '%');
    }

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeOfUser(Builder $query, $value)
    {
        return $query->where('user_id', '=', $value);
    }

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeOfSpace(Builder $query, $value)
    {
        return $query->where('space_id', '=', $value)
            ->when(!$value, function ($query) use ($value) {
                $query->orWhereNull('space_id');
            });
    }

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeOfDomain(Builder $query, $value)
    {
        return $query->where('domain_id', '=', $value);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeExpired(Builder $query)
    {
        return $query->where(function ($query) {
            $query->whereNotNull('ends_at')
                ->where('ends_at', '<', Carbon::now());
        })->orWhere(function ($query) {
            $query->where('expiration_clicks', '>', 0)
                ->whereColumn('clicks', '>=', 'expiration_clicks');
        });
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeDisabled(Builder $query)
    {
        return $query->where('disabled', '=', 1);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('disabled', '=', 0)
            ->where(function ($query) {
                $query->where('ends_at', '>', Carbon::now())
                    ->orWhere('ends_at', '=', null);
            })
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('expiration_clicks', '=', null)
                        ->orWhere('expiration_clicks', '=', 0);
                })
                ->orWhere(function ($query) {
                    $query->where('expiration_clicks', '>', 0)
                        ->whereColumn('clicks', '<', 'expiration_clicks');
                });
            });
    }

    /**
     * Encrypt the link's password.
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Crypt::encryptString($value);
    }

    /**
     * Decrypt the link's password.
     *
     * @param $value
     * @return string
     */
    public function getPasswordAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Encrypt the link's stats password.
     *
     * @param $value
     */
    public function setPrivacyPasswordAttribute($value)
    {
        $this->attributes['privacy_password'] = Crypt::encryptString($value);
    }

    /**
     * Decrypt the link's stats page password.
     *
     * @param $value
     * @return string
     */
    public function getPrivacyPasswordAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return null;
        }
    }
}
