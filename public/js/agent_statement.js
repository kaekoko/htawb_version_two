$(function(){
    var smaid = $('#sid').val();
    var wh_o = 'agent';

    $('#statment-text').prop('hidden', true);
    $('#current-tab').attr('data-name', 'user');
    $('#current-tab').attr('data-table', '#user');

    calldaily('daily', 'user', $('.date').val(), '#user');
   
    console.log($('.date').val());

    $('.link-statement').on('click', function(e){
        var daily = $(this).attr('data-record');
        var column = $(this).attr('data-value');
        var date = $('.date').val();
        var send_date = moment(date).format('YYYY-MM-DD');
        $('#current-tab').attr('data-name', $(this).attr('data-value'));
        $('#current-tab').attr('data-table', $(this).attr('data-table'));
        var append_body = $(this).attr('data-table');
        $(append_body).empty();
        calldaily(daily, column, send_date, append_body);
    })

    $(document).on('click','.applyBtn', function(){
        var table = $('#current-tab').attr('data-table');
        $(table).empty();
        var date = $('.date').val();
        var send_date = moment(date).format('YYYY-MM-DD');
        calldaily('daily', $('#current-tab').attr('data-name'), send_date, $('#current-tab').attr('data-table'));
    })

    $(document).on('click', '.section-modal', function(){
        var date = $('.date').val();
        var send_date = moment(date).format('YYYY-MM-DD');
        $('#nav-section-user').attr('data-name', $(this).attr('data-name'));
        $('#nav-section-user').attr('data-id', $(this).attr('data-id'));
        $('#modal-user').modal('show');
        $('#nine-user').empty();
        $('#nav-section-user').find('.active').click();
        callsection('section', $(this).attr('data-name'), send_date, '#nine-user', '9:30 AM', $(this).attr('data-id'));
    })


    $('.section-list').on('click', function(){
        var tag = $(this).attr('data-table');
        var section = $(this).attr('data-record');
        var column = $('#nav-section').attr('data-name');
        var column_user = $('#nav-section-user').attr('data-name');
        var date = $('.date').val();
        var time = $(this).attr('data-time');
        var id = $('#nav-section').attr('data-id');
        var cur = $('#current-tab').attr('data-cur', $(this).attr('id'));
        var aria = $('#current-tab').attr('data-aria', $(this).attr('aria-controls'));
        var id_user = $('#nav-section-user').attr('data-id');
        $(tag).empty();
        callsection(section, column_user, date, tag, time, id_user);
    })
    

    function calldaily(daily,column, date, abody){
        var table_append = abody;
        $.ajax({
            method: 'POST',
            url: location.protocol + "//" + location.host  + '/api/sma_agent_payment_statement/' + daily,
            data: {
                column: column,
                date: date,
                sma_id: smaid,
                who: wh_o,
            },
            success:function(data){
                if(data.message !== 'success'){
                    $('#statment-text').prop('hidden', false);
                } else {
                    $('#statment-text').prop('hidden', true);
                    $.each(data.result, function(i,v){
                            $(table_append).append(`
                            <tr>
                                <td>`+ v['name'] +`</td>
                                <td>`+ v['phone'] +`</td>
                                <td>`+ v['reward'] +`</td>
                                <td><button type="button" class="btn btn-default section-modal" data-name="`+ column +`" data-id="`+ v['id'] +`">
                                View
                                </button></td>
                            </tr>
                        `);
                    })
                }
            }
        })
    }

    function callsection(section,column,date,abody,time, id){
        var table_append = abody;
        $.ajax({
            method: 'POST',
            url: location.protocol + "//" + location.host  + '/api/sma_agent_payment_statement/' + section,
            data: {
                column: column,
                date: date,
                time: time,
                id: id,
                sma_id: smaid,
                who: wh_o
            },
            success:function(data){
                console.log(data);
                if(data.message !== 'success'){
                    $('#statment-text').prop('hidden', false);
                } else {
                    $('#statment-text').prop('hidden', true);
                    $.each(data.result, function(i,v){
                            $(abody).append(`
                            <tr>
                                <td>`+ v['name'] +`</td>
                                <td>`+ v['phone'] +`</td>
                                <td>`+ v['reward'] +`</td>
                            </tr>
                        `);
                    })
                }
            }
        })
    }
    
});