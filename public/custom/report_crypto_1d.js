$(function() {

    var urlParams = new URLSearchParams(window.location.search);
    //calendar
    if(urlParams.has('start_date')){
        var start = moment(urlParams.get('start_date'));
        var end = moment(urlParams.get('end_date'));
    }else{
        var start = moment().subtract(29, 'days');
        var end = moment();
    }

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

    //calendar

    //refresh
    $('.reload').click(function(){
        window.location = '/super_admin/report_c1d';
    });

    if(urlParams.has('start_date')){
        start = urlParams.get('start_date');
        end = urlParams.get('end_date');
    }else{
        start = start.format('YYYY-MM-DD');
        end = end.format('YYYY-MM-DD');
    }

    //by date daily filter
    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
        $('.daily_static').empty();
        var startDate = $('#reportrange').data('daterangepicker').startDate;
        var endDate = $('#reportrange').data('daterangepicker').endDate;
        var start = startDate.format('YYYY-MM-DD');
        var end = endDate.format('YYYY-MM-DD');
        history.pushState(null, '', '?start_date='+start+'&end_date='+end)
        window.location.reload()
    });

});


