@extends('super_admin.backend_layout.app')

@section('title', 'Player List')

@section('player-list-active', 'active')

@section('content')
    <a href="{{ url('game/claim_refer_game_history') }}" class="btn btn-success btn-sm mb-3">Claim Game Referral History</a>
    <div class="card">
        <div class="card-body">
            <form action="{{ url('game/player_detail') }}">
                <label for="userId">
                    Search By UserId:
                    <div class="d-flex">
                        <input type="text" required name="user_code" class="form form-sm form-control">
                        <button class="btn btn-sm btn-info ml-2">Search</button>
                    </div>
                </label>
            </form>
        </div>
    </div>
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm player-list-data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>User ID</th>
                                    <th>phone</th>
                                    <th>Balance</th> 
                                    <th>Game Balance</th>
                                    <th>Promo Turnover</th>
                                    <th>Game Referral</th>
                                    <th>Game Transfer Log</th>
                                    <th>Register Date</th>
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
            let dtOverrideGlobals = {
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ url('game/player_list') }}",
                order: [
                    [1, 'desc']
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'index'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'user_code',
                        name: 'user_code'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'balance',
                        name: 'balance'
                    },
                    {
                        data: 'game_balance',
                        name: 'game_balance'
                    },
                    {
                        data: "turnover",
                        name: "turnover"
                    },
                    {
                        data: "game_refer_amt",
                        name: "game_refer_amt"
                    },
                    {
                        data: "game_transfer_log",
                        name: "game_transfer_log"
                    },
                    {
                        data: "created_at",
                        name: "created_at"
                    }
                ],
                pageLength: 50,
            };
            $('.player-list-data-table').DataTable(dtOverrideGlobals);
        });
    </script>
@endsection
