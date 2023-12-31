<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;
    
     protected $fillable=['name','price','trade_name','date','company','amount','category_id','user_id'];

     protected $hidden = [
       'pivot'
   ];

     public function orders():BelongsToMany
     {
            return $this->belongsToMany(Order::class,'order_deatiles');
     }

     public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
