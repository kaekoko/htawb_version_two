<div class="d-flex">
    <div class="btn-group mr-1">
        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-tasks"></i>
        </button>
        <div class="dropdown-menu">
            <div class="d-flex justify-content-center align-items-center py-0">
                <div class="px-2">
                    <a href="{{ url('/super_admin/user/' . $row->id) }}" title="View"><button class="btn btn-info btn-sm"><i class="fa fa-eye"></i></button></a>
                </div>
                <div class="px-2">
                    <a href="{{ url('super_admin/user/' . $row->id . '/edit') }}" title="Edit"><button class="btn btn-primary btn-sm"><i class="fa fa-solid fa-pen"></i></button></a>
                </div>
                <div class="px-2">
                    <a href="{{ url('super_admin/user/all_report/' . $row->id ) }}" title="Report"><button class="btn btn-danger btn-sm"><i class="fa-brands fa-stack-exchange"></i></button></a>
                </div>
                {{-- <form id="postBtn{{ $row->id }}" method="post" action="{{ route('user.destroy', $row->id) }}" style="display: inline;">
                @csrf
                @method('DELETE')
                </form>
                <div class="px-2">
                    <button class="btn btn-danger btn-sm deleteBtn" data-id={{ $row->id }}><i class="fa fa-trash"></i></button>
                </div> --}}
            </div>

            <div class="dropdown-divider"></div>

            @if (Auth::guard('super_admin')->user()->role_id == 1)
            <a class="dropdown-item py-0" href="{{ url('/super_admin/cash_in/' . $row->id) }}">Cash
                In</a>
            @endif

            @if (Auth::guard('super_admin')->user()->role_id == 1)
            <a class="dropdown-item py-0" href="{{ url('/super_admin/cash_out/' . $row->id) }}">Cash
                Out</a>
            @endif


            <div class="dropdown-divider"></div>

            <a class="dropdown-item py-0" href="{{ url('/super_admin/only/betslip/histroy/' . $row->user_code) }}">Game History new</a>


            <a class="dropdown-item py-0" href="{{ url('/super_admin/cash_in_history/' . $row->id) }}">Cash
                In History</a>
            <a class="dropdown-item py-0" href="{{ url('/super_admin/cash_out_history/' . $row->id) }}">Cash
                Out History</a>

        </div>
    </div>

    <div class="btn-group">
        <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa-solid fa-clipboard-list"></i>
        </button>
        <div class="dropdown-menu">
            <a class="dropdown-item py-1" href="{{ url('/super_admin/user/1d_betslips_only/' . $row->id) }}">1D Bet History
            </a>
            <a class="dropdown-item py-1" href="{{ url('/super_admin/user/2d_betslips_only/' . $row->id) }}">2D Bet History
            </a>
            <a class="dropdown-item py-1" href="{{ url('/super_admin/user/crypto1d_betslips_only/' . $row->id) }}">Crypto 1D Bet History
            </a>
            <a class="dropdown-item py-1" href="{{ url('/super_admin/user/crypto2d_betslips_only/' . $row->id) }}">Crypto 2D Bet History
            </a>
            <a class="dropdown-item py-1" href="{{ url('/super_admin/user/3d_betslips_only/' . $row->id) }}">3D Bet History
            </a>
        </div>
    </div>

</div>
