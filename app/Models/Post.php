<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;;

class Post extends Model
{
    protected $fillable = [
        'title',
        'description',
        'body',
        'category_id',
        'image',
    ];

    public function category()
    {
    	return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
    	return $this->belongsToMany(Tag::class);
    }

    public function comments()
    {
    	return $this->hasMany(Comment::class);
    }

    public function getRelatedPostsAttribute()
    {
        $tagIds = $this->tags->pluck('id')->toArray();
        $posts = collect();

        if (count($tagIds)) {
            $posts = Post::where('posts.id','!=',$this->id)->whereHas('tags', function($query) use ($tagIds){
                $query->whereIn('tags.id', $tagIds);
            })->get();
        }

        return $posts;
    }

    public function setTitleAttribute($title)
    {
        $this->attributes['title'] = $title;
        $this->attributes['slug'] = str_slug($title);
    }

    public function getUrlAttribute()
    {
        return route('posts.show',['slug' => $this->slug, 'id' => $this->id]);
    }

    public function getImageUrlAttribute()
    {
        return \Storage::disk('public')->url("images/$this->image");
    }

    public function imageUrl($width, $height)
    {
        return route('image.transform.fit', ['width' => $width, 'height' => $height, 'filename' => $this->image]);
    }
}