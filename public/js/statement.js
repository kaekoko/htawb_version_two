$(function(){
    $('#statment-text').prop('hidden', true);
    $('#current-tab').attr('data-name', 'senior_agent_id');
    $('#current-tab').attr('data-table', '#sa_agent');

    calldaily('daily', 'senior_agent_id', $('.date').val(), '#sa_agent');

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
        if($(this).attr('data-name') != 'user'){
            $('#nav-section').attr('data-name', $(this).attr('data-name'));
            $('#nav-section').attr('data-id', $(this).attr('data-id'));
            $('#modal-xl').modal('show');
            $('#nine').empty();
            $('#nav-section').find('.active').click();
            callsection('section', $(this).attr('data-name'), send_date, '#nine', '9:30 AM', $(this).attr('data-id'));
        } else {
            $('#nav-section-user').attr('data-name', $(this).attr('data-name'));
            $('#nav-section-user').attr('data-id', $(this).attr('data-id'));
            $('#modal-user').modal('show');
            $('#nine-user').empty();
            $('#nav-section-user').find('.active').click();
            callsection('section', $(this).attr('data-name'), send_date, '#nine-user', '9:30 AM', $(this).attr('data-id'));
        }
    })


    $('.section-list').on('click', function(){
        var tag = $(this).attr('data-table');
        var section = $(this).attr('data-record');
        var column = $('#nav-section').attr('data-name');
        var column_user = $('#nav-section-user').attr('data-name');
        var date = $('.date').val();
        var time = $(this).attr('data-time');
        var id = $('#nav-section').attr('data-id');
        var id_user = $('#nav-section-user').attr('data-id');
        $(tag).empty();
        if($(this).attr('data-title') == 'user'){
            callsection(section, column_user, date, tag, time, id_user);
        } else {
            callsection(section, column, date, tag, time, id);
        }
    })
    

    function calldaily(daily,column, date, abody){
        var table_append = abody;
        $.ajax({
            method: 'POST',
            url: location.protocol + "//" + location.host  + '/api/superadmin_payment_statement/' + daily,
            data: {
                column: column,
                date: date
            },
            success:function(data){
                if(data.message !== 'success'){
                    $('#statment-text').prop('hidden', false);
                } else {
                    $('#statment-text').prop('hidden', true);
                    console.log(data.result);
                    console.log(table_append);
                    $.each(data.result, function(i,v){
                        if(column !== 'user'){
                            $(abody).append(`
                            <tr>
                                <td>`+ v['name'] +`</td>
                                <td>`+ v['phone'] +`</td>
                                <td>`+ v['to_get'] +`</td>
                                <td>`+ v['to_pay'] +`</td>
                                <td>`+ v['percent'] +`</td>
                                <td>`+ v['commission'] +`</td>
                                <td>`+ v['result'] +`</td>
                                <td><button type="button" class="btn btn-default section-modal" data-name="`+ column +`" data-id="`+ v['id'] +`">
                                View
                                </button></td>
                            </tr>
                        `);
                        } else {
                            $(abody).append(`
                            <tr>
                                <td>`+ v['name'] +`</td>
                                <td>`+ v['phone'] +`</td>
                                <td>`+ v['reward'] +`</td>
                                <td><button type="button" class="btn btn-default section-modal" data-name="`+ column +`" data-id="`+ v['id'] +`">
                                View
                                </button></td>
                            </tr>
                        `);
                        }
                    })
                }
            }
        })
    }

    function callsection(section,column,date,abody,time, id){
        var table_append = abody;
        $.ajax({
            method: 'POST',
            url: location.protocol + "//" + location.host  + '/api/superadmin_payment_statement/' + section,
            data: {
                column: column,
                date: date,
                time: time,
                id: id
            },
            success:function(data){
                if(data.message !== 'success'){
                    $('#statment-text').prop('hidden', false);
                } else {
                    $('#statment-text').prop('hidden', true);
                    console.log(data.result);
                    console.log(table_append);
                    $.each(data.result, function(i,v){
                        if(column !== 'user'){
                            if(v['to_get'] > 0 || v['to_pay'] > 0){
                                $(abody).append(`
                                <tr>
                                    <td>`+ v['to_get'] +`</td>
                                    <td>`+ v['to_pay'] +`</td>
                                    <td>`+ v['percent'] +`</td>
                                    <td>`+ v['commission'] +`</td>
                                    <td>`+ v['result'] +`</td>
                                </tr>
                                `);
                            }
                        } else {
                            $(abody).append(`
                            <tr>
                                <td>`+ v['name'] +`</td>
                                <td>`+ v['phone'] +`</td>
                                <td>`+ v['reward'] +`</td>
                            </tr>
                        `);
                        }
                    })
                }
            }
        })
    }
    
});