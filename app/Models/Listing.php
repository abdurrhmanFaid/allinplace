<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Listing extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($listing) {
            $listing->slug = uniqid(true);
        });
    }

    /**
     * @return string
     */
    public function getRouteKeyName()
    {
        return "slug";
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeIsLive($query)
    {
        return $query->where('live', true);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeIsNotLive($query)
    {
        return $query->where('live', false);
    }

    /**
     * @return mixed
     */
    public function live()
    {
        return $this->live;
    }

    /**
     * @return mixed
     */
    public function cost()
    {
        return $this->category->price;
    }

    /**
     * @return mixed
     */
    public function formattedCost()
    {
        return $this->category->cost();
    }

    /**
     * @return bool
     */
    public function free()
    {
        return $this->cost() === 0;
    }

    /**
     * @param $query
     * @param $category
     * @return mixed
     */
    public function scopeFromCategory($query, Category $category)
    {
        return $query->where('category_id', $category->id);
    }

    /**
     * @param $query
     * @param $category
     * @return mixed
     */
    public function scopeInArea($query, Area $area)
    {
        return $query->whereIn('area_id', array_merge(
            [$area->id],
            $area->descendants()->pluck('id')->toArray()
        ));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favouritable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function likers()
    {
        return $this->morphToMany(User::class, 'favouritable', 'favourites');
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function hasFavouritedBy(User $user)
    {
        return $this->likers->contains($user);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function viewedUsers()
    {
        return $this->belongsToMany(User::class, 'user_listing_views')
            ->withTimestamps()
            ->withPivot(['count']);
    }

    /**
     * @return mixed
     */
    public function views()
    {
        $views = $this->viewedUsers->pluck('pivot.count')->toArray();

        return array_sum($views);
    }
}
