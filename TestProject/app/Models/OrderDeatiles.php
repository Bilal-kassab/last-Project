<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderDeatiles extends Model
{
    use HasFactory;
    
    protected $fillable =['quntity','order_id','product_id','price'];

    protected $hidden = [
        'pivot'
    ];

   

}
