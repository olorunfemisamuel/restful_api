<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cause extends Model
{
  protected $fillable = ['title', 'description', 'image_url'];

    public function contribute() {
        return $this->hasMany(Contribution::class);
    }
}
