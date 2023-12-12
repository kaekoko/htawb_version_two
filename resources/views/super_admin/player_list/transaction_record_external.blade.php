@extends('super_admin.backend_layout.external')

@section('title', "Transaction Report: $date")

@section('player-list-active', 'active')

@section('content')
    <button onclick="history.back()">Go Back</button>
    <br />
    <br />
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-sm transaction-record-data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User ID</th>
                                    <th>Game Type</th>
                                    <th>Bet Time</th>
                                    <th>Turnover</th>
                                    <th>Payout</th>
                                    <th>Bet</th>
                                    <th>Commission</th>
                                    <th>W/L</th>
                                    <th>P/L</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(function() {
            let date = "{{ $date }}";
            let provider = "{{ $provider }}";
            let username = "{{ $username }}";
            let url = `/transaction_reports_external?date=${date}&provider=${provider}&username=${username}`;
            let dtOverrideGlobals = {
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: url,
                order: [
                    [1, 'desc']
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'index'
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'game_type',
                        name: 'game_type'
                    },
                    {
                        data: 'bet_time',
                        name: 'bet_time'
                    },
                    {
                        data: 'turnover',
                        name: 'turnover'
                    },
                    {
                        data: 'payout',
                        name: 'payout'
                    },
                    {
                        data: 'bet',
                        name: 'bet'
                    },
                    {
                        data: 'commission',
                        name: 'commission'
                    },
                    {
                        data: 'win_loss',
                        name: 'win_loss'
                    },
                    {
                        data: 'profit_loss',
                        name: 'profit_loss'
                    }
                ],
                pageLength: 50,
            };
            $('.transaction-record-data-table').DataTable(dtOverrideGlobals);
        });
    </script>
@endsection
