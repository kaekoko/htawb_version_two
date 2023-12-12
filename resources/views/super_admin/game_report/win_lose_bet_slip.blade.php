@extends('super_admin.backend_layout.app')

@section('title', $user->name . "'s Game Histories")

@section('win-lose-active', 'active')

@section('content')
    <a href="{{ url('game/win_lose?start_date=' . request()->query('start_date') . '&end_date=' . request()->query('end_date')) }}"
        title="Back"><button class="btn btn-default btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i>
            Back</button></a>
    <br />
    <br />
    <div class="card card-dark card-outline">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="data_table_super_admin" class="table table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>TurnOver</th>
                                    <th>Commision</th>
                                    <th>W/L</th>
                                    <th>P/L</th>
                                    <th style="display: none"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bettings as $key => $data)
                                    <tr style="cursor: pointer;">
                                        <td>
                                            {{ $key + 1 }}
                                        </td>
                                        <td>{{ $data['data_by_date']['date'] }}</td>
                                        <td>{{ $data['data_by_date']['turnover'] }}</td>
                                        <td>{{ $data['data_by_date']['commission'] }}</td>
                                        <td>
                                            @if ($data['data_by_date']['winloss'] < 0)
                                                <span
                                                    class="badge badge-danger">{{ $data['data_by_date']['winloss'] }}</span>
                                            @else
                                                <span
                                                    class="badge badge-success">{{ $data['data_by_date']['winloss'] }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($data['data_by_date']['profitloss'] < 0)
                                                <span
                                                    class="badge badge-danger">{{ $data['data_by_date']['profitloss'] }}</span>
                                            @else
                                                <span
                                                    class="badge badge-success">{{ $data['data_by_date']['profitloss'] }}</span>
                                            @endif
                                        </td>
                                        <td style="display: none;">{{ $data['provider_data'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        // Datatable Collpase
        function format(data) {
            // How to show in collpase area?
            return (
                `
                <table class="table table-xs bg-secondary">
                    <thead>
                        <tr>
                            <th>Provider</th>
                            <th>Valid Turnover</th>
                            <th>Commision</th>
                            <th>W/L</th>
                            <th>P/L</th>
                        </tr>
                    </thead>
                    <tbody>
                       ${data.length > 0 ? (
                        data.map(_data => (
                        `               <tr>
                                                                    <td>
                                                                        <a href="/game/win_lose_user/game_history_detail/?date=${_data.date}&provider=${_data.p_code}&username=${_data.username}">${_data.p_code}</a>
                                                                    </td>  
                                                                    <td>${_data.total_turnover}</td>    
                                                                    <td>${_data.total_commission}</td>    
                                                                    <td>
                                                                        ${
                                                                            _data.total_winloss < 0 ?
                                                                                `
                                                                        <span class="badge badge-danger"> ${_data.total_winloss}</span>
                                                                    `
                                                                            : `<span class="badge badge-success"> ${_data.total_winloss}</span>
                                                                `}
                                                                    </td>    
                                                                    <td>
                                                                        ${
                                                                            _data.total_profitloss < 0 ?
                                                                                `
                                                                        <span class="badge badge-danger"> ${_data.total_profitloss}</span>
                                                                    `
                                                                            : `<span class="badge badge-success"> ${_data.total_profitloss}</span>
                                                                `}
                                                                    </td>                                                         </tr>  
                                            `
                       ))
                       ): (
                        `<tr><td colspan="6">No Data!</td></tr>`
                       )}
                    </tbody>
                </table>
                `
            );
        }

        $(document).ready(function() {
            let dt = $('#data_table_super_admin').DataTable();
            // Array to track the ids of the details displayed rows
            var detailRows = [];
            $('#data_table_super_admin tbody').on('click', 'tr td', function() {
                var tr = $(this).closest('tr');
                var row = dt.row(tr);
                var idx = detailRows.indexOf(tr.attr('id'));

                if (row.child.isShown()) {
                    tr.removeClass('details');
                    row.child.hide();

                    // Remove from the 'open' array
                    detailRows.splice(idx, 1);
                } else {
                    tr.addClass('details');
                    row.child(format(JSON.parse(row.data()[6])).replaceAll(",", "")).show();
                    // Add to the 'open' array
                    if (idx === -1) {
                        detailRows.push(tr.attr('id'));
                    }
                }
            });

            // On each draw, loop over the `detailRows` array and show any child rows
            dt.on('draw', function() {
                detailRows.forEach(function(id, i) {
                    $('#' + id + ' td').trigger('click');
                });
            });
        });
    </script>
@endsection
