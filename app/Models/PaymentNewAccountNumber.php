<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentNewAccountNumber extends Model
{
    use HasFactory;

    protected $hidden = ['id', 'payment_id', 'created_at', 'updated_at'];
    protected $table = 'new_payment_account_numbers';
    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_id');
    }
}
