<?php

namespace App\Models;

use sslcommerz;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class sslcommerz_account extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id', 'store_passwd', 'init_url', 'currency', 'success_url', 'fail_url', 'cancel_url', 'ipn_url'
    ];




  
}

