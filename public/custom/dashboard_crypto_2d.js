//Date range picker
$(function () {
    $('input[name="dashboard_date"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,
        locale: {
            format: 'YYYY/MM/DD'
        },
        maxYear: parseInt(moment().format('YYYY'), 11)
    });
});

//by date daily filter
$('.date').on('apply.daterangepicker', function () {
    var date = $('.date').val();
    history.pushState(null, '', '?date=' + date)
    window.location.reload()
});

$('.reload').click(function () {
    window.location = '/super_admin/dashboard_c2d';
});

//data and section
var date = $('.date').val();
var section = $('.section').attr("value");

//onclick chage time section
function changeSection(time) {
    section = time;
    $('.clearance').text(section + ' Clearance');
}

//onclick chage time section
function changeDate() {
    date = $('.date').val();
}

//Ajax Setup
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Clearance Password Alert
$('.clearance').click(function () {
    $('.pass_date').val(date);
    $('.pass_section').val(section);
    $('.modal-password').modal('show');
});

// Refund Password Alert
$('.refund').click(function () {
    $('.pass_date').val(date);
    $('.pass_section').val(section);
    $('.modal-refund').modal('show');
});

// hot block data
function hot_block_data() {
    $.ajax({
        method: 'GET',
        url: location.protocol + "//" + location.host + '/api/hot_block_c2d',
        data: {
            date: date
        },
        success: function (data) {

            var blocks = data.data['block'][section];
            if (data.data['block'] != '') {
                $.each(blocks.block_number, function (i, val) {
                    $('.number_slot_' + val).addClass('block_number').removeClass('zero_amount');
                });
            }

            var hots = data.data['hot'][section];
            if (data.data['hot'] != '') {
                $.each(hots.hot_number, function (i, val) {
                    var bet_amount = $('.number_slot_' + val + ' span').html();
                    if (parseInt(bet_amount) >= parseInt(hots.amount)) {
                        $('.number_slot_' + val).addClass('hot_number').removeClass('zero_amount');
                    }
                });
            }
        }
    });
}

//single_detail
$(document).on('click', '.custom_dashboard', function () {
    $('.betDetailData').empty();
    var number = $(this).attr("bet_number");
    $.ajax({
        method: 'POST',
        url: location.protocol + "//" + location.host + '/super_admin/single_detail_c2d',
        data: {
            date: date,
            time: section,
            number: number
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
    url: location.protocol + "//" + location.host + '/super_admin/clearance_c2d',
    data: {
        date: date,
        section: section
    },
    success: function (data) {
        $('.clearance').text(section + ' Clearance');
        if (data.time_check === 'open' && data.lucky_number_read === 1) {
            $('.clearance').attr('disabled', 'disabled');
        }
        if (data.time_check === 'close' && data.lucky_number_read === 1) {
            $('.clearance').attr('disabled', 'disabled');
        }
        if (data.time_check === 'open' && data.lucky_number_read === '') {
            $('.clearance').attr('disabled', 'disabled');
        }
        if (data.approve === '' && data.lucky_number_read === 0) {
            $('.clearance').attr('disabled', 'disabled');
        }
    }
});

//Grant Numbers
$.ajax({
    method: 'POST',
    url: location.protocol + "//" + location.host + '/super_admin/grant_numbers_c2d',
    data: {
        date: date,
        time: section
    },
    success: function (data) {
        if (data.status == 'open') {
            $.each(data.data, function (i, val) {
                var $block_color;
                if (val.amount == 0) {
                    $block_color = `zero_amount`;
                }
                if (val.over_amount_message == 1) {
                    $block_color = `over_amount_message`;
                }
                $('.all_bet').append(`

                    <div class="p-2">
                        <button type="button" class="btn btn-block btn-lg custom_dashboard `+ $block_color + ` number_slot_` + val.bet_number + `" bet_number="` + val.bet_number + `">
                            <p class="open_number">`+ val.bet_number + `</p>
                            <span>`+ parseFloat(val.amount) + `</span>
                        </button>
                    </div>
                `)
            });
        }

        if (data.status != 'open' && data.status != 'close') {
            $.each(data, function (i, val) {
                var $block_color;
                if (val.amount == 0) {
                    $block_color = `zero_amount`;
                }
                $('.all_bet').append(`
                    <div class="p-2">
                        <button type="button" class="btn btn-block btn-lg custom_dashboard `+ $block_color + `">
                            <p class="open_number">`+ val.bet_number + `</p>
                            <span>`+ parseFloat(val.amount) + `</span>
                        </button>
                    </div>
                `)
            });
        }

        if (data.status == 'close') {
            $('.clerance_div').empty();
            $('.all_bet').append(`
                <div class="p-2">
                    <button type="button" class="btn btn-block btn-lg custom_dashboard">
                        <p class="open_number">Today Close Day</p>
                    </button>
                </div>
            `);
        }

        hot_block_data();
    }
});

//Over All Statistics
$.ajax({
    method: 'POST',
    url: location.protocol + "//" + location.host + '/super_admin/time_statics_c2d',
    data: {
        date: date,
        time: section
    },
    success: function (data) {
        //profit and lose
        var profitAndLose;
        if (data.profit > 0) {
            profitAndLose = `
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>`+ data.profit + `</h3>
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
                        <h3>`+ data.profit + `</h3>
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
                            <h3>`+ data.all_bet_amounts + `</h3>
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
                            <h3>`+ data.total_reward + `</h3>
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
                            <h3>`+ data.user_refer_total + `</h3>
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
                            <h3>`+ data.lucky_number + `</h3>
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

//daily_detail
$(document).on('click', '.custom_daily', function () {
    $('.betDetailData').empty();
    var number = $(this).attr("bet_number");
    $.ajax({
        method: 'POST',
        url: location.protocol + "//" + location.host + '/super_admin/daily_detail_c2d',
        data: {
            date: date,
            number: number
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

//Section Change Api Req
$('.section').click(function () {
    $('.all_bet').empty();
    $('.over_all_amount').empty();
    $('#bet_slip').empty();
    $('.clearance').empty();
    $('.clearance').removeAttr("disabled");

    var section = $(this).attr("value");

    //grant_numbers
    $.ajax({
        method: 'POST',
        url: location.protocol + "//" + location.host + '/super_admin/grant_numbers_c2d',
        data: {
            date: date,
            time: section
        },
        success: function (data) {
            if (data.status == 'open') {
                $.each(data.data, function (i, val) {
                    var $block_color;
                    if (val.amount == 0) {
                        $block_color = `zero_amount`;
                    }
                    if (val.over_amount_message == 1) {
                        $block_color = `over_amount_message`;
                    }
                    $('.all_bet').append(`

                        <div class="p-2">
                            <button type="button" class="btn btn-block btn-lg custom_dashboard `+ $block_color + ` number_slot_` + val.bet_number + `" bet_number="` + val.bet_number + `">
                                <p class="open_number">`+ val.bet_number + `</p>
                                <span>`+ parseFloat(val.amount) + `</span>
                            </button>
                        </div>
                    `)
                });
            }

            if (data.status != 'open' && data.status != 'close') {
                $.each(data, function (i, val) {
                    var $block_color;
                    if (val.amount == 0) {
                        $block_color = `zero_amount`;
                    }
                    $('.all_bet').append(`
                        <div class="p-2">
                            <button type="button" class="btn btn-block btn-lg custom_dashboard `+ $block_color + `">
                                <p class="open_number">`+ val.bet_number + `</p>
                                <span>`+ parseFloat(val.amount) + `</span>
                            </button>
                        </div>
                    `)
                });
            }

            if (data.status == 'close') {
                $('.clerance_div').empty();
                $('.all_bet').append(`
                    <div class="p-2">
                        <button type="button" class="btn btn-block btn-lg custom_dashboard">
                            <p class="open_number">Today Close Day</p>
                        </button>
                    </div>
                `);
            }
            hot_block_data();
        }
    });

    //time_statics
    $.ajax({
        method: 'POST',
        url: location.protocol + "//" + location.host + '/super_admin/time_statics_c2d',
        data: {
            date: date,
            time: section
        },
        success: function (data) {
            //profit and lose
            var profitAndLose;
            if (data.profit > 0) {
                profitAndLose = `
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>`+ data.profit + `</h3>
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
                            <h3>`+ data.profit + `</h3>
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
                                <h3>`+ data.all_bet_amounts + `</h3>
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
                                <h3>`+ data.total_reward + `</h3>
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
                                <h3>`+ data.user_refer_total + `</h3>
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
                                <h3>`+ data.lucky_number + `</h3>
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

    //Clearance
    $.ajax({
        method: 'POST',
        url: location.protocol + "//" + location.host + '/super_admin/clearance_c2d',
        data: {
            date: date,
            section: section
        },
        success: function (data) {
            $('.clearance').text(section + ' Clearance');
            if (data.time_check === 'open' && data.lucky_number_read === 1) {
                $('.clearance').attr('disabled', 'disabled');
            }
            if (data.time_check === 'close' && data.lucky_number_read === 1) {
                $('.clearance').attr('disabled', 'disabled');
            }
            if (data.time_check === 'open' && data.lucky_number_read === '') {
                $('.clearance').attr('disabled', 'disabled');
            }
        }
    });

    // //Bet slips
    $.ajax({
        method: 'POST',
        url: location.protocol + "//" + location.host + '/super_admin/bet_slips_c2d',
        data: {
            date: date,
            section: section
        },
        success: function (data) {
            $.each(data, function (i, val) {
                $('#bet_slip').append(`
                    <tr>
                        <td>`+ val.user_name + `</td>
                        <td>`+ val.section + `</td>
                        <td>`+ val.total_amount + ` MMK</td>
                        <td>`+ val.total_bet + `</td>
                        <td>`+ val.date + `</td>
                        <td><a href="bet_slip_c2d/`+ val.action + `" title="View"><button class="btn btn-info btn-sm"><i class="fa fa-eye"></i></button></a></td>
                    </tr>
                `);
            });
        }
    });
});

//daily click
$('.daily').click(function () {

    $('.all_bet').empty();
    $('#over_all_amount').empty();
    $('#bet_slip').empty();

    //daily_grant_numbers
    $.ajax({
        method: 'POST',
        url: location.protocol + "//" + location.host + '/super_admin/daily_grant_numbers_c2d',
        data: {
            date: date,
        },
        success: function (data) {
            if (data.status == 'open') {
                var result_amount;
                $.each(data.data, function (i, val) {
                    var $block_color;

                    if (val.amount == 0) {
                        $block_color = `zero_amount`;
                        result_amount = val.amount;
                    }
                    else if (val.current_amount) {
                        $block_color = `zero_amount`;
                        result_amount = val.current_amount;
                    } else {
                        result_amount = val.amount;
                    }

                    $('.all_bet').append(`

                        <div class="p-2">
                            <button type="button" class="btn btn-block btn-lg custom_daily `+ $block_color + `" bet_number="` + val.bet_number + `">
                                <p class="open_number">`+ val.bet_number + `</p>
                                <span>`+ parseFloat(result_amount) + `</span>
                            </button>
                        </div>
                    `)
                });
            }

            if (data.status != 'open' && data.status != 'close') {
                $.each(data, function (i, val) {
                    var $block_color;
                    if (val.amount == 0) {
                        $block_color = `zero_amount`;
                    }
                    $('.all_bet').append(`
                        <div class="p-2">
                            <button type="button" class="btn btn-block btn-lg custom_daily `+ $block_color + `">
                                <p class="open_number">`+ val.bet_number + `</p>
                                <span>`+ parseFloat(val.amount) + `</span>
                            </button>
                        </div>
                    `)
                });
            }

            if (data.status == 'close') {
                $('.clerance_div').empty();
                $('.all_bet').append(`
                    <div class="p-2">
                        <button type="button" class="btn btn-block btn-lg custom_daily">
                            <p class="open_number">Today Close Day</p>
                        </button>
                    </div>
                `);
            }
        }
    });

    //daily_statics
    $.ajax({
        method: 'POST',
        url: location.protocol + "//" + location.host + '/super_admin/daily_statics_c2d',
        data: {
            date: date
        },
        success: function (data) {
            //profit and lose
            var profitAndLose;
            if (data.profit > 0) {
                profitAndLose = `
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>`+ data.profit + `</h3>
                                <p>Profit</p>
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
                                <h3>`+ data.profit + `</h3>
                                <p>Loss</p>
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
                                    <h3>`+ data.all_bet_amounts + `</h3>
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
                                    <h3>`+ data.total_reward + `</h3>
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
                                    <h3>`+ data.user_refer_total + `</h3>
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
                        <div class="over_all_amount" style="min-width: 320px">
                            <div class="small-box bg-info">
                            </div>
                        </div>
                    </div>
                `);
        }
    });

    //Bet slips daily
    $.ajax({
        method: 'POST',
        url: location.protocol + "//" + location.host + '/super_admin/daily_bet_slips_c2d',
        data: {
            date: date,
        },
        success: function (data) {
            $.each(data, function (i, val) {
                $('#bet_slip').append(`
                    <tr>
                        <td>`+ val.user_name + `</td>
                        <td>`+ val.section + `</td>
                        <td>`+ val.total_amount + ` MMK</td>
                        <td>`+ val.total_bet + `</td>
                        <td>`+ val.date + `</td>
                        <td><a href="bet_slip_c2d/`+ val.action + `" title="View"><button class="btn btn-info btn-sm"><i class="fa fa-eye"></i></button></a></td>
                    </tr>
                `);
            });
        }
    });
});

//Bet slips
$.ajax({
    method: 'POST',
    url: location.protocol + "//" + location.host + '/super_admin/bet_slips_c2d',
    data: {
        date: date,
        section: section
    },
    success: function (data) {
        $.each(data, function (i, val) {
            $('#bet_slip').append(`
                <tr>
                    <td>`+ val.user_name + `</td>
                    <td>`+ val.section + `</td>
                    <td>`+ val.total_amount + ` MMK</td>
                    <td>`+ val.total_bet + `</td>
                    <td>`+ val.date + `</td>
                    <td><a href="bet_slip_c2d/`+ val.action + `" title="View"><button class="btn btn-info btn-sm"><i class="fa fa-eye"></i></button></a></td>
                </tr>
            `);
        });
    }
});