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

let weekday = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][new Date().getDay()];

if(weekday == 'Sat' || weekday == 'Sun'){
$('#closeday').prop('hidden',true);
$('#warningclose').prop('hidden', false);
} else {
$('#closeday').prop('hidden',false);
$('#warningclose').prop('hidden', true);
}

$.ajax({
    method: 'GET',
    url: location.protocol + "//" + location.host  + '/super_admin/times',
    success:function(data){
        console.log(data);
        $.each(data, function(i,val){
            if(val != "over"){
                $('#select_time').append(`<option value="`+ val +`">`+ val +`</option>`)
            }
        });
    }
})


$('.add-modal').on('click', function(e){
    var id = $(this).attr('id');
    var number = $('#twod_'+ id).val();
    var block = $('#block_'+ id).val();
    var amt = $('#hotamt_'+ id).val();
    if($(this).attr('data-type') == 'hot'){
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
        url: location.protocol + "//" + location.host  + '/super_admin/hotblock1d/' + id,
        data: insert,
        success:function(data){
            // console.log(data);
            toastr.success('Data Add Created Successfully');
        }
    });
})
