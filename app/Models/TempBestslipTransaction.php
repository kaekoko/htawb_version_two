<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempBestslipTransaction extends Model
{
    use HasFactory;

    protected $table = 'temp_bestslip_transactions';

    protected $dates = [
        'settlement_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'member',
        'operator',
        'product',
        'provider',
        'provider_line',
        'wager_id',
        'currency',
        'game_type',
        'game',
        'game_round',
        'valid_bet_amount',
        'bet_amount',
        'transaction_amount',
        'transaction',
        'payout_amount',
        'payout_detail',
        'bet_detail',
        'commision_amount',
        'jackpot_amount',
        'settlement_date',
        'jp_bet',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
