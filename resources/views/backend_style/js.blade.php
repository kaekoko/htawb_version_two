@include('backend_style.footer')

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
<!-- Filterizr-->
<script src="{{ asset('plugins/filterizr/jquery.filterizr.min.js') }}"></script>
<!-- Ekko Lightbox -->
<script src="{{ asset('plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
<!-- Laravel Javascript Validation -->
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
<!-- Sweet Alert -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- DataTables  & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<!-- Bootstrap Switch -->
<!-- <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script> -->
{{-- Custom js --}}
<script src="{{ asset('custom/backend_style.js') }}"></script>
<script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
{{-- firebase initialize file --}}
<script src="{{ asset('firebase.js') }}"></script>
<script>
    $(document).ready(function() {
        // ALert
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        @if (session('flash_message'))
            Toast.fire({
                icon: "success",
                title: "{{ session('flash_message') }}"
            })
        @endif

        @if (session('error_message'))
            Toast.fire({
                icon: "warning",
                title: "{{ session('error_message') }}"
            })
        @endif
    })

    var loadFile = function(event) {
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    };

    messaging.onMessage(function(payload) {
        const noteTitle = payload.notification.title;
        const noteOptions = {
            body: payload.notification.body,
            icon: payload.notification.icon,
        };
        new Notification(noteTitle, noteOptions);

        const title = payload.notification.title;
        const body = payload.notification.body;

        if (body == 'User Cash In') {
            noti_count("cash_in_count");
        }

        if (body == 'User Cash Out') {
            noti_count("cash_out_count");
        }

        if (body == 'New User Registered') {
            noti_count("user_count");
        }

        if (body == 'User Betting: Thai 2D') {
            noti_count("bet_slip_count");
        }

        if (body == 'User Betting: Crypto 2D') {
            noti_count("bet_slip_c2d_count");
        }

        if (body == 'User Betting: Thai 3D') {
            noti_count("bet_slip_3d_count");
        }
    });
    // Tooltips
    $(function() {
        $('.bs-tooltip').each(function() {
            $(this).tooltip({
                html: true,
                title: $('#' + $(this).data('bs-tooltip')).html()
            });
        });

    })
    //
    const numberFormatter = (number = 0) => {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>
@yield('scripts')
