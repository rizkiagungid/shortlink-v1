<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Domain
 *
 * @mixin Builder
 * @package App
 */
class Domain extends Model
{
    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeSearchName(Builder $query, $value)
    {
        return $query->where('name', 'like', '%' . $value . '%');
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
     * @return Builder
     */
    public function scopeGlobal(Builder $query)
    {
        return $query->where('user_id', '=', 0);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopePrivate(Builder $query)
    {
        return $query->where('user_id', '=', 1);
    }

    /**
     * Get the total links count under the Domain.
     *
     * @return int
     */
    public function getTotalLinksAttribute()
    {
        return $this->hasMany('App\Models\Link')->where('domain_id', $this->id)->count();
    }

    /**
     * Get the Links under the Domain.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function links()
    {
        return $this->hasMany('App\Models\Link')->where('domain_id', $this->id);
    }

    /**
     * Get the user that owns the Domain.
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    /**
     * Get the domain name with the URL protocol.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return config('settings.short_protocol') . '://' .$this->name;
    }
}
