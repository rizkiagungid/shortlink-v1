<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Space
 *
 * @mixin Builder
 * @package App
 */
class Space extends Model
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
     * Get the total links count under the Space.
     *
     * @return int
     */
    public function getTotalLinksAttribute()
    {
        return $this->hasMany('App\Models\Link')->where('space_id', $this->id)->count();
    }

    /**
     * Get the Links under the Space.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function links()
    {
        return $this->hasMany('App\Models\Link')->where('space_id', $this->id);
    }

    /**
     * Get the user that owns the Space.
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }
}
