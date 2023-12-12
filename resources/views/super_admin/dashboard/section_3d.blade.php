@extends('super_admin.backend_layout.app')

@section('title', 'Super Admin 3D Dashboard')

@section('dashboard-3d-active', 'active')

@section('content')
    <h3 class="text-center">Please the 3D Date Section</h3>

    <div class='contaner'>
        <div class=" " style="height:300px;">
            <div class='d-flex row justify-content-center align-item-center mt-5 text-white' >

                   @foreach($sections as $n)

                   <a href="{{route('dashboard.section3d',$n->date)}}" class='col-3 m-3'>
                   <button onclick="second()" class='btn btn-warning border border-warning border-4  w-100' style='height:80px;'>
                <span class="fs-3" style="font-size: 20px;">{{$n->date}} day for section</span>
                </button>
                   </a>

                   @endforeach

            </div>
        </div>
    </div>

@endsection
@section('scripts')
<script>
    function second(){
        let timerInterval;
Swal.fire({
  title: "Thanks for waiting",
  html: "Waiting to the 2D Dashboard",
  timer: 20000,
  timerProgressBar: true,
  didOpen: () => {
    Swal.showLoading();
    const timer = Swal.getPopup().querySelector("b");
    timerInterval = setInterval(() => {
      timer.textContent = `${Swal.getTimerLeft()}`;
    }, 100);
  },
  willClose: () => {
    clearInterval(timerInterval);
  }
}).then((result) => {
  /* Read more about handling dismissals below */
  if (result.dismiss === Swal.DismissReason.timer) {
    console.log("I was closed by the timer");
  }
});
    }
</script>
@endsection