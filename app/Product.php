<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'image', 'status'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['status_number', 'image_url', 'created_at_human', 'updated_at_human'];

    public function getCreatedAtHumanAttribute()
    {
    	return Carbon::parse($this->attributes['created_at'])->diffForhumans();
    }

    public function getUpdatedAtHumanAttribute()
    {
        return Carbon::parse($this->attributes['updated_at'])->diffForhumans();
    }

    public function getImageUrlAttribute()
    {
        if(empty($this->attributes['image'])) return asset('storage/products/default.jpeg');

        return asset('storage/products/'.$this->attributes['image']);
    }

    public function getStatusAttribute($value)
    {
    	switch ($value) {
	    case 1:
	        return "Pendente";
	        break;
	    case 2:
	        return "Em análise";
	        break;
	    case 3:
	        return "Aprovado";
	        break;
	    case 4:
	        return "Reprovado";
	        break;
	    default:
	    	return "Error";
		}
    }

    public function getStatusNumberAttribute()
    {
        return $this->attributes['status'];
    }
}
