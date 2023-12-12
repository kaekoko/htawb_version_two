@extends('super_admin.backend_layout.app')

@section('title', "Betslip Transaction")

@section('transaction-active', 'active')

@section('content')
<div class="card card-dark card-outline">
    <div class="card-body">
        <div class="row mt-3">
            <div class="col-12">
                <div class="table-responsive">
                    <table id="data_table_super_admin" class="table table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>MEMEBER CODE</th>
                                <th>BALANCE</th>
                                <th>BEFORE BALANCE</th>
                                <th>PROVIDER CODE</th>
                                <th>PROVIDER NAME</th>
                                <th>METHOD NAME</th>
                                <th>PROVIDER</th>
                                <th>PROVIDER LINE</th>
                                <th>WAGER ID</th>
                                <th>CURRENCY</th>
                                <th>GAME TYPE</th>
                                <th>GAME</th>
                                <th>GAME ROUND</th>
                                <th>VALID BET AMOUNT</th>
                                <th>BET AMOUNT</th>
                                <th>TRANSACTION AMOUNT</th>
                                <th>TRANSACTION</th>
                                <th>PAYOUT AMOUNT</th>
                                <th>PAYOUT DETAIL</th>
                                <th>BET DETAIL</th>
                                <th>COMMISION AMOUNT</th>
                                <th>JACKPOT AMOUNT</th>
                                <th>SETTLEMENT DATE</th>
                                <th>JP BET</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $betslip)
                            <tr>
                                <td>{{ $betslip->id }}</td>
                                <td>{{ $betslip->member_code }}</td>
                                <td>{{ $betslip->balance }}</td>
                                <td>{{ $betslip->before_balance }}</td>
                                <td>{{ $betslip->provider_code }}</td>
                                <td>{{ $betslip->provider_name }}</td>
                                <td>{{ $betslip->method_name }}</td>
                                <td>{{ $betslip->provider }}</td>
                                <td>{{ $betslip->provider_line }}</td>
                                <td>{{ $betslip->wager_id }}</td>
                                <td>{{ $betslip->currency }}</td>
                                <td>{{ $betslip->game_type }}</td>
                                <td>{{ $betslip->game }}</td>
                                <td>{{ $betslip->game_round }}</td>
                                <td>{{ $betslip->valid_bet_amount }}</td>
                                <td>{{ $betslip->bet_amount }}</td>
                                <td>{{ $betslip->transaction_amount }}</td>
                                <td>{{ $betslip->transaction }}</td>
                                <td>{{ $betslip->payout_amount }}</td>
                                <td>{{ $betslip->payout_detail }}</td>
                                <td>{{ $betslip->bet_detail }}</td>
                                <td>{{ $betslip->commision_amount }}</td>
                                <td>{{ $betslip->jackpot_amount }}</td>
                                <td>{{ $betslip->settlement_date }}</td>
                                <td>{{ $betslip->jp_bet }}</td>
                                <td>{{ $betslip->status }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    const mouseWheel = document.querySelector('#data_table_super_admin');

    mouseWheel.addEventListener('wheel', function(e) {
        const race = 50; // How many pixels to scroll

        if (e.deltaY > 0) // Scroll right
            mouseWheel.scrollLeft += race;
        else // Scroll left
            mouseWheel.scrollLeft -= race;
        e.preventDefault();
    });
</script>
@endsection