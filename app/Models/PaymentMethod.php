<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PaymentNewAccountNumber;

class PaymentMethod extends Model
{
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payment_methods';

    public function cash_ins()
    {
        return $this->hasMany(CashIn::class);
    }

    public function cash_outs()
    {
        return $this->hasMany(CashOut::class);
    }

    public function credit_histories()
    {
        return $this->hasMany(CreditHistory::class, 'payment_method_id');
    }

    public function credit_out_histories()
    {
        return $this->hasMany(CreditOutHistory::class, 'payment_method_id');
    }

    public function payment_account_numbers()
    {
        return $this->hasMany(PaymentAccountNumber::class, 'payment_id');
    }

    public function new_payment_account_numbers()
    {
        return $this->hasMany(PaymentNewAccountNumber::class, 'payment_id');
    }

}
