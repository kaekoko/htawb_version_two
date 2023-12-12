$('.js-example-basic-single').select2();

$('.datepicker').datepicker({
    startDate: '-3d'
});

function showTime() {
    var date = new Date(),
        utc = new Date(Date.UTC(
            date.getFullYear(),
            date.getMonth(),
            date.getDate(),
            date.getHours(),
            date.getMinutes(),
            date.getSeconds()
        ));
    document.getElementById('time').innerHTML = utc.toUTCString();
}

setInterval(showTime, 1000);

$('.add-modal').on('click', function (e) {
    var id = $(this).attr('id');
    var number = $('#twod_' + id).val();
    $.ajax({
        method: 'POST',
        url: location.protocol + "//" + location.host + '/super_admin/custom_c2d/' + id,
        data: {
            number: number
        },
        success: function (data) {
            toastr.success(data.data.record_time + ' In ' + data.data.twod_number + ' Number Created')
        }
    });
})
