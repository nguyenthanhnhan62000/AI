<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificates_land extends Model
{
    use HasFactory;

    public function certificates_land_category(){
        return $this->hasOne(Certificates_land_category::class,'id','certificates_land_categorie_id');
    }
}
