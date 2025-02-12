<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer_profile extends Model
{
    use HasFactory;

    protected $fillable = [
        "cus_naem",
        "cus_add",
        "cus_city",
        "cus_state",
        "cus_postcode",
        "cus_country",
        "cus_phone",
        "cus_fax",
        "ship_name",
        "ship_add",
        "ship_city",
        "ship_state",
        "ship_postcode",
        "ship_country",
        "ship_phone",
        'user_id',
        
    ] ;

    public function user(): BelongsTo
        {
            return $this->BelongsTo(User::class);
        }
}
