<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    //

    use HasFactory;
    protected $fillable = ['name', 'email'];

     protected $appends = ['total_users','total_genrated_urls','total_url_hits'];

    public function getTotalUsersAttribute() {
        return $this->users()->count();
    }

    public function users() {
        return $this->hasMany(User::class);
    }
    public function shortUrls() {
        return $this->hasMany(ShortUrl::class);
    }

    public function getTotalGenratedUrlsAttribute() {
        return $this->shortUrls()->count();
    }

    public function getTotalUrlHitsAttribute() {
        return $this->shortUrls()->sum('click_count');
    }

    
}
