<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table="orders";
    
    protected $fillable=['price_all','user_id','status','payment_status'];


    protected $hidden = [
      
    ];


    public function user():BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

        
    public function products():BelongsToMany
     {
            return $this->belongsToMany(Product::class,'order_deatiles');
     }

    

}
