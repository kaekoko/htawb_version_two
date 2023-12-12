//month and 3d date , year

let date3d = $('.date3d').attr("value");

var month = $('.his_month').attr("value");

let year = $('.his_year').attr("value");




$(function () {

    
        let timerInterval;
        Swal.fire({
        title: "Thanks for waiting",
        html: "Waiting to the 3D Dashboard",
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
            //Date range picker
    $('input[name="dashboard_3d_date"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,
        locale: {
            format: 'YYYY/MM/DD'
        },
        maxYear: parseInt(moment().format('YYYY'), 11)
    }, function (start, end, label) {
    });

    //by date daily filter
    $('.date-3d').on('apply.daterangepicker', function (ev, picker) {
        var date = $('.date-3d').val();
        history.pushState(null, '', '?date=' + date)
        window.location.reload()
    });

    //Ajax start
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        
    });

    //datatable
    $('#bet_3d_his_datatable').DataTable({
        "info": false,
        "ordering": false,
    });

    $('#bet_slip_dash_datatable').DataTable({
        "info": false,
        "ordering": false,
    });

    //refresh
    $('.reload').click(function () {
        window.location = '/super_admin/dashboard_3d';
    });

    // Passwrod Alert
    $('.clearance').click(function () {
        $('.modal-password').modal('show');
        $('.pass_month').val(month);
        $('.pass_date_3d').val(date3d);
        $('.pass_year').val(year);
        $('.all_bet_amount_cl').val($('.all_bet_amount_c').text());
        $('.total_reward_cl').val($('.total_reward_c').text());
        $('.profit_cl').val($('.profit_c').text());
        $('.user_refer_total_cl').val($('.user_refer_total_c').text());
        $('.lucky_number_cl').val($('.lucky_number_c').text());
    });

    // Refund Alert
    $('.refund').click(function () {
        $('.modal-refund').modal('show');
        $('.pass_month').val(month);
        $('.pass_date_3d').val(date3d);
        $('.pass_year').val(year);
    });

    //grant_numbers
    $.ajax({
        method: 'POST',
        url: location.protocol + "//" + location.host + '/super_admin/grant_numbers_3d',
        data: {
            month: month,
            date3d: date3d,
            year: year,
        },
        
        success: function (data) {
            if (data.status == 'open') {
                var htmlContent = ''; // Create an empty string to store HTML content
        
                $.each(data.data, function (i, val) {
                    var $block_color = (val.amount == 0) ? 'zero_amount' : '';
        
                    htmlContent += `
                        <div class="p-2">
                            <button type="button" class="btn btn-block btn-lg custom_dashboard ${$block_color} number_slot_${val.bet_number}"  bet_number="${val.bet_number}">
                                <p class="open_number">${val.bet_number}</p>
                                <span>${parseFloat(val.amount)}</span>
                            </button>
                        </div>
                    `;
                });
        
                $('.all_bet').append(htmlContent); // Append the entire HTML content at once
                hot_block();
            }
        }
    });

    //time_statics
    $.ajax({
        method: 'POST',
        url: location.protocol + "//" + location.host + '/super_admin/time_statics_3d',
        data: {
            month: month,
            date3d: date3d,
            year: year,
        },
        success: function (data) {
            //profit and lose
            var profitAndLose;
            if (data.profit > 0) {
                profitAndLose = `
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 class="profit_c">`+ data.profit + `</h3>
                        <p>Profit/Loss</p>
                        <div class="icon">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                    </div>
                </div>
            `;
            } else {
                profitAndLose = `
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 class="profit_c">`+ data.profit + `</h3>
                        <p>Profit/Loss</p>
                        <div class="icon">
                            <i class="fas fa-minus-circle"></i>
                        </div>
                    </div>
                </div>
            `;
            }

            $('#over_all_amount').append(`
            <div class="row">
                <div class="over_all_amount">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3 class="all_bet_amount_c">`+ data.all_bet_amounts + `</h3>
                            <p>All Bet Amounts</p>
                        </div>
                        <div class="icon">
                            <i class="nav-icon fas fa-file-invoice"></i>
                        </div>
                    </div>
                </div>
                <div class="over_all_amount">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3 class="total_reward_c">`+ data.total_reward + `</h3>
                            <p>Total Reward</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                    </div>
                </div>
                <div class="over_all_amount">
                    `+ profitAndLose + `
                </div>
            </div>
            <div class="row">
                <div class="over_all_amount">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3 class="user_refer_total_c">`+ data.user_refer_total + `</h3>
                            <p>Total User Refer Amount</p>
                        </div>
                        <div class="icon">
                            <i class="fa-sm fas fa-percent"></i>
                        </div>
                    </div>
                </div>
                <div class="over_all_amount">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>`+ data.total_bet_number + `</h3>
                            <p>Total Bet Number</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-list-ol"></i>
                        </div>
                    </div>
                </div>
                <div class="over_all_amount">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3 class="lucky_number_c">`+ data.lucky_number + `</h3>
                            <p>Lucky Number</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
        `);
        }
    });

    //single_detail
    $(document).on('click', '.custom_dashboard', function () {
        $('.betDetailData').empty();
        var number = $(this).attr("bet_number");
        $.ajax({
            method: 'POST',
            url: location.protocol + "//" + location.host + '/super_admin/single_detail_3d',
            data: {
                month: month,
                date3d: date3d,
                number: number,
                year: year,
            },
            success: function (data) {
                if (data.length > 0) {
                    $('.modal-number').modal('show');
                    $('#modal-head').text(number);
                    $.each(data, function (i, val) {
                        $('.betDetailData').append(`
                        <tr>
                            <td><a href="">`+ val.name + `</a></td>
                            <td>`+ parseFloat(val.amount) + `MMK</td>
                        </tr>
                    `);
                    });
                }
            }
        });
    });

    //Clearance
    $.ajax({
        method: 'POST',
        url: location.protocol + "//" + location.host + '/super_admin/clearance_status_3d',
        data: {
            month: month,
            date3d: date3d,
            year: year,
        },
        success: function (data) {
            $('.clearance').text(date3d + ' Clearance');
            if (data == 'yes') {
                $('.clearance').attr('disabled', 'disabled');
            }
        }
    });
            clearInterval(timerInterval);
        }
        }).then((result) => {
        /* Read more about handling dismissals below */
        if (result.dismiss === Swal.DismissReason.timer) {
            console.log("I was closed by the timer");
        }
        });
    
})

//hot and block number
function hot_block() {
    $.ajax({
        method: 'POST',
        url: location.protocol + "//" + location.host + '/super_admin/hot_block_3d',
        data: {
            month: month
        },
        success: function (data) {

            $.each(data.data['block'], function (i, vales) {
                $.each(vales.block_number, function (i, val) {
                    $('.number_slot_' + val).addClass('block_number').removeClass('zero_amount');
                });
            });

            $.each(data.data['hot'], function (i, vales) {
                var hots = vales;
                $.each(vales.hot_number, function (i, val) {
                    var bet_amount = $('.number_slot_' + val + ' span').html();
                    if (parseInt(bet_amount) >= parseInt(hots.amount)) {
                        $('.number_slot_' + val).addClass('hot_number').removeClass('zero_amount');
                    }
                });
            });
        }
    });
}