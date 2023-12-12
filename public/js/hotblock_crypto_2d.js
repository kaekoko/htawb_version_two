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
    var block = $('#block_' + id).val();
    var amt = $('#hotamt_' + id).val();
    if ($(this).attr('data-type') == 'hot') {
        var insert = {
            hot_number: number,
            hot_amount: amt,
            type: 'hot',

        }
    } else {
        var insert = {
            block_number: block,
            type: 'block'
        }
    }
    $.ajax({
        method: 'POST',
        url: location.protocol + "//" + location.host + '/super_admin/hotblock_c2d/' + id,
        data: insert,
        success: function () {
            toastr.success('Data Add Created Successfully');
        }
    });
})
