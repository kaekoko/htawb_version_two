$(function(){
    var array = [];
    $.ajax({
        url: 'get_proncat',
        type: 'GET',
        success:function(data){
            var active = '';
            var pane = '';
            array.push(data.data['provider']);
            console.log(data.data['provider']);
            $.each(data.data['category'], function(i,v){
                if(i == 0){
                    active = 'active';
                    pane = 'show active';
                } else {
                    active = '';
                    pane = '';
                }

                $('#cat-tab').append(`
                    <li class="nav-item" role="presentation">
                        <button class="nav-link `+ active +` cat-bton" data-id="`+ v['id'] +`" id="tab-`+ v['id'] +`" data-bs-toggle="tab" data-bs-target="#nav-category-`+ v['id'] +`" type="button" role="tab" aria-controls="`+ v['id'] +`" aria-selected="true">`+ v['name'] +`</button>
                    </li>
                `)

                $('#cat-body').append(`
                <div class="tab-pane fade `+ pane +` ml-5" data-id="`+ v['id'] +`" id="nav-category-`+ v['id'] +`" role="tabpanel" aria-labelledby="tab-`+ v['id'] +`">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="nav flex-column nav-pills me-3 cat-column-`+ v['id'] +`" id="v-pills-tab"  role="tablist" aria-orientation="vertical">
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="tab-content text-center cat-pane-`+ v['id'] +`" id="v-pills-tabContent" style="text-align:center;">
                            </div>
                        </div>
                    </div>
                </div>
                `)
            })
            $('.cat-bton[data-id=1]')[0].click();
        }
    })

    $(document).on('click','.cat-bton', function(e){
        $('#checking -tab').attr('data-cat', $(this).attr('id'));
        var catid = $(this).attr('data-id');
        var btn_active = '';
        var pane_active = '';
        $('.cat-column-'+catid).empty();
        $('.cat-pane-'+catid).empty();
        $.each(array[0], function(i,v){
            if(i == 0){
                btn_active = 'active';
            } else {
                btn_active = '';
            }
            $('.cat-column-'+catid).append(
                `<button class="nav-link `+ btn_active +` pro-game-btn" id="v-pills-`+ v['id'] +`-tab" data-id="`+ v['id'] +`" data-bs-toggle="pill" data-bs-target="#v-pills-cat-`+ catid +`pro-`+ v['id'] +`" type="button" role="tab" aria-controls="v-pills-cat-`+ catid +`pro-`+ v['id'] +`" aria-selected="true"><img src="../` + v['img'] + `" width="70" height="60"></button>`
            )

            if(i == 0){
                pane_active = 'show active';
            } else {
                pane_active = '';
            }
            $('.cat-pane-'+catid).append(
                `<div class="tab-pane fade `+ pane_active +` game-con" id="v-pills-cat-`+ catid +`pro-`+ v['id'] +`" role="tabpanel" aria-labelledby="v-pills-`+ v['id'] +`-tab">
                    <div class="gamesbycat-`+ catid +`pro-`+ v['id'] +`">


                    </div>
                </div>`
            )
        })
    })

    $(document).on('click', '.pro-game-btn', function(e){
        $('#checking -tab').attr('data-pro', $(this).attr('data-id'));
        var pro_id = $(this).attr('data-id');
        var cat_id = $(this).closest('.tab-pane').attr('data-id');
        game_data(cat_id, pro_id);
    })

    function game_data(cat_id, pro_id){
        $.ajax({
            url: 'game-click?provider_id=' + pro_id + '&category_id=' + cat_id,
            type: 'GET',
            success:function(data){
                $('#game-table-pro-'+ pro_id + '-cat-' + cat_id).dataTable().fnDestroy();
                $('.gamesbycat-'+ cat_id +'pro-'+ pro_id).html(data);

                $('.game-table-pro-'+ pro_id + '-cat-' + cat_id).DataTable({
                    processing: true,
                    serverSide: true,
                    pageLength: 4,
                    ajax: 'game-lists?provider_id=' + pro_id + '&category_id=' + cat_id,
                    columns: [
                        {data: 'name', name: 'name'},
                        {data: 'img', name: 'img'},
                        {data: 'is_hot', name: 'is_hot'},
                        {data: 'is_new', name: 'is_new'},
                        {data: 'active', name: 'active'},
                        {data: 'created_at', name: 'created_at'},
                    ],
                    columnDefs: [
                        {
                            "targets" : [ 1 ],
                                render : function (data, type, row) {
                var gameimg = row.img;
                if(gameimg.indexOf('http') != -1){
                   return '<img src="'+ row.img +'" width="100" height="100">'
                } else if (gameimg.includes('cdn-static.queenmakergames.co')) {
                    return '<img src="'+ row.img +'" width="100" height="100">'
                }else{
                    return '<img src="../storage/games/'+ row.img  +'" width="100" height="100">'
                }
                            },
                        },{
                            "targets" : [ 2 ],
                                render : function (data, type, row) {
                                if(row.is_hot == 1){
                                    var hot = 'checked';
                                }else{
                                    var hot = '';
                                }
                                console.log(hot);
                                return '<div class="switch-game mt-2" id="'+ row.name + row.g_code +'"><input type="checkbox" class="togg-game" data-request="hot" id="h_'+ row.name + row.g_code +'" data-id="'+ row.id + '"'+ hot + '><label for="h_'+ row.name + row.g_code + '"><i></i></label></div>'
                            },
                        },
                        {
                            "targets" : [ 3 ],
                                render : function (data, type, row) {
                                if(row.is_new === 1){
                                    var neww = 'checked';
                                }else{
                                    var neww = '';
                                }
                                return '<div class="switch-game mt-2" id="'+ row.name + row.g_code +'"><input type="checkbox" class="togg-game" data-request="new" id="n_'+ row.name + row.g_code +'" data-id="'+ row.id + '"'+ neww + '><label for="n_'+ row.name + row.g_code + '"><i></i></label></div>'
                            },
                        },
                        {
                            "targets" : [ 4 ],
                                render : function (data, type, row) {
                                if(row.active === 1){
                                    var active = 'checked';
                                }else{
                                    var active = '';
                                }
                                return '<div class="switch-game mt-2" id="'+ row.name + row.g_code +'"><input type="checkbox" class="togg-game" data-request="active" id="a_'+ row.name + row.g_code +'" data-id="'+ row.id + '"'+ active + '><label for="a_'+ row.name + row.g_code + '"><i></i></label></div>'
                            },
                        },{
                            "targets": [ 5 ],
                                render: function(data, type, row) {
                                    return moment(row.created_at).format('DD-MM-YYYY HH:mm A');
                                },
                        },
                    ]
                });
                $('#cat-body').animate({ scrollTop: "0" },  500);
            }
        });
    }

    $(document).on('click','.togg-game', function(){
        var id = $(this).attr('data-id');
        var req = $(this).attr('data-request');
        var token = $('#token').val();
        var num = 0;
        if ($(this).is(':checked')) {
            var num = 1;
        } else {
            var num = 0;
        }

        $.ajax({
            url: 'game-control/'+ id,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': token
            },
            data: {
                request_name: req,
                status: num
            },
            success:function(data){
                console.log(data);
            }
        })
    })
})
