<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BetslipTransaction extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'betslip_transactions';

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

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function wager()
    {
        return $this->belongsTo(Wager::class, 'wager_id');
    }

    public function getSettlementDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    // public function setSettlementDateAttribute($value)
    // {
    //     $this->attributes['settlement_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    // }

    public function slip_game()
    {
        return $this->belongsTo(ShamelessGame::class,'p_code','game');
    }

  
}
