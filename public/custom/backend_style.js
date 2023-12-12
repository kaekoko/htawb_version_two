$(document).ready(function () {

    //Ajax start
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize Select2 Elements
    $('.select2').select2()

    // Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })

    // SummerNote
    $('#summernote').summernote({
        height: 300,
    });

    $('#summernoteNoImage').summernote({
        height: 200,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'video']],
            ['view', ['fullscreen', 'help']],
        ]
    });

    // Delete Alert
    $('.deleteBtn').click(function () {
        var data_id = $(this).attr("data-id");
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $("#postBtn" + data_id).submit();
            }
        })
    });

    // Confirm Alert
    $('.confirmBtn').click(function () {
        var data_id = $(this).attr("data-id");
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, confim it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $("#postBtn" + data_id).submit();
            }
        })
    });

    // Status Switch Page Reload
    // $('.toggle-class').change(function() {
    //     var status = $(this).prop('checked') == true ? 1 : 0;
    //     var item_id = $(this).data('id');
    //     if(status == 0){
    //             $('.offBtn'+item_id).attr( "name", "status" );
    //     }else{
    //             $('.onBtn'+item_id).attr( "name", "status" );
    //     }
    //     $("#switchForm"+ item_id).submit();
    // });

    // DataTable
    $('#data_table_super_admin').DataTable({
        "info": false,
    });
    $('#data_table_senior_agent').DataTable({
        "info": false
    });
    $('#data_table_master_agent').DataTable({
        "info": false
    });
    $('#data_table_agent').DataTable({
        "info": false
    });

    // Datatable for id correct increasement
    var data_table = $('#data_table').DataTable({
        "info": false,
        // "searching": false,
        "columnDefs": [{
            "searchable": false,
            "orderable": false,
            "targets": 0
        }],
    });

    data_table.on('order.dt search.dt', function () {
        data_table.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

    var t = $('#data_table_custom').DataTable({
        "info": false,
        "columnDefs": [{
            "searchable": false,
            "orderable": false,
            "targets": 0
        }],
    });

    t.on('order.dt search.dt', function () {
        t.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

    var t = $('#data_table_auto').DataTable({
        "info": false,
        "columnDefs": [{
            "searchable": false,
            "orderable": false,
            "targets": 0
        }],
    });

    t.on('order.dt search.dt', function () {
        t.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

    //custom 1, custom 2 and auto 1
    var t = $('#data_table_custom_1').DataTable({
        "info": false,
        "columnDefs": [{
            "searchable": false,
            "orderable": false,
            "targets": 0
        }],
    });

    t.on('order.dt search.dt', function () {
        t.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

    var t = $('#data_table_custom_2').DataTable({
        "info": false,
        "columnDefs": [{
            "searchable": false,
            "orderable": false,
            "targets": 0
        }],
    });

    t.on('order.dt search.dt', function () {
        t.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

    var t = $('#data_table_auto_1').DataTable({
        "info": false,
        "columnDefs": [{
            "searchable": false,
            "orderable": false,
            "targets": 0
        }],
    });

    t.on('order.dt search.dt', function () {
        t.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

    // Datatable for pdf,print and excel
    $("#datatable_1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["excel", "pdf", "print"],
        "columnDefs": [{
            "searchable": false,
            "orderable": false,
            "targets": 0
        }],
        "order": [[1, 'asc']]
    }).buttons().container().appendTo('#datatable_1_wrapper .col-md-6:eq(0)');

    // Phone Number Field Check
    $("#check_phone").change(function () {
        $('#check_phone').attr("name", "phone");
    });

    //Timepicker
    $('#timepicker').datetimepicker({
        format: 'LT'
    })

    //Date picker
    $('#reservationdate').datetimepicker({
        format: 'L'
    });

    //Date range picker
    $('#reservation').daterangepicker()

    //Role select
    $('.role_select').change(function () {
        Swal.fire(
            'Are U Sure?',
            'That role will not be edit after you create notification!',
        )
    });

    //Update time
    var number = parseInt($('#update_time').text(), 10) || 0;

    var interval = setInterval(function () {
        $('#update_time').text(number++);
        if (number > 100000) {
            clearInterval(interval);
        }
    }, 1000);

})

//Copy text click
function copyTextClick() {
    var copyText = $('.copy_text').text();
    navigator.clipboard.writeText(copyText);
}

function noti_count(type) {
    $.ajax({
        method: 'GET',
        url: location.protocol + "//" + location.host + '/super_admin/notification',
        success: function (res) {
            let data = res?.data;
            $(`#${type}`).html(data?.[type]);
        }
    });
}

+function(a){"use strict";function b(b){return this.each(function(){var d=a(this),e=d.data("bs.toggle"),f="object"==typeof b&&b;e||d.data("bs.toggle",e=new c(this,f)),"string"==typeof b&&e[b]&&e[b]()})}var c=function(b,c){this.$element=a(b),this.options=a.extend({},this.defaults(),c),this.render()};c.VERSION="2.2.0",c.DEFAULTS={on:"On",off:"Off",onstyle:"primary",offstyle:"default",size:"normal",style:"",width:null,height:null},c.prototype.defaults=function(){return{on:this.$element.attr("data-on")||c.DEFAULTS.on,off:this.$element.attr("data-off")||c.DEFAULTS.off,onstyle:this.$element.attr("data-onstyle")||c.DEFAULTS.onstyle,offstyle:this.$element.attr("data-offstyle")||c.DEFAULTS.offstyle,size:this.$element.attr("data-size")||c.DEFAULTS.size,style:this.$element.attr("data-style")||c.DEFAULTS.style,width:this.$element.attr("data-width")||c.DEFAULTS.width,height:this.$element.attr("data-height")||c.DEFAULTS.height}},c.prototype.render=function(){this._onstyle="btn-"+this.options.onstyle,this._offstyle="btn-"+this.options.offstyle;var b="large"===this.options.size?"btn-lg":"small"===this.options.size?"btn-sm":"mini"===this.options.size?"btn-xs":"",c=a('<label class="btn">').html(this.options.on).addClass(this._onstyle+" "+b),d=a('<label class="btn">').html(this.options.off).addClass(this._offstyle+" "+b+" active"),e=a('<span class="toggle-handle btn btn-default">').addClass(b),f=a('<div class="toggle-group">').append(c,d,e),g=a('<div class="toggle btn" data-toggle="toggle">').addClass(this.$element.prop("checked")?this._onstyle:this._offstyle+" off").addClass(b).addClass(this.options.style);this.$element.wrap(g),a.extend(this,{$toggle:this.$element.parent(),$toggleOn:c,$toggleOff:d,$toggleGroup:f}),this.$toggle.append(f);var h=this.options.width||Math.max(c.outerWidth(),d.outerWidth())+e.outerWidth()/2,i=this.options.height||Math.max(c.outerHeight(),d.outerHeight());c.addClass("toggle-on"),d.addClass("toggle-off"),this.$toggle.css({width:h,height:i}),this.options.height&&(c.css("line-height",c.height()+"px"),d.css("line-height",d.height()+"px")),this.update(!0),this.trigger(!0)},c.prototype.toggle=function(){this.$element.prop("checked")?this.off():this.on()},c.prototype.on=function(a){return this.$element.prop("disabled")?!1:(this.$toggle.removeClass(this._offstyle+" off").addClass(this._onstyle),this.$element.prop("checked",!0),void(a||this.trigger()))},c.prototype.off=function(a){return this.$element.prop("disabled")?!1:(this.$toggle.removeClass(this._onstyle).addClass(this._offstyle+" off"),this.$element.prop("checked",!1),void(a||this.trigger()))},c.prototype.enable=function(){this.$toggle.removeAttr("disabled"),this.$element.prop("disabled",!1)},c.prototype.disable=function(){this.$toggle.attr("disabled","disabled"),this.$element.prop("disabled",!0)},c.prototype.update=function(a){this.$element.prop("disabled")?this.disable():this.enable(),this.$element.prop("checked")?this.on(a):this.off(a)},c.prototype.trigger=function(b){this.$element.off("change.bs.toggle"),b||this.$element.change(),this.$element.on("change.bs.toggle",a.proxy(function(){this.update()},this))},c.prototype.destroy=function(){this.$element.off("change.bs.toggle"),this.$toggleGroup.remove(),this.$element.removeData("bs.toggle"),this.$element.unwrap()};var d=a.fn.bootstrapToggle;a.fn.bootstrapToggle=b,a.fn.bootstrapToggle.Constructor=c,a.fn.toggle.noConflict=function(){return a.fn.bootstrapToggle=d,this},a(function(){a("input[type=checkbox][data-toggle^=toggle]").bootstrapToggle()}),a(document).on("click.bs.toggle","div[data-toggle^=toggle]",function(b){var c=a(this).find("input[type=checkbox]");c.bootstrapToggle("toggle"),b.preventDefault()})}(jQuery);
//# sourceMappingURL=bootstrap-toggle.min.js.map
