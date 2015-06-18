$(function () {

    /** BEGIN DATATABLE EXAMPLE **/
    if ($('#datatable-pharmacy').length > 0){
        selectedIds = [];
        var table = $('#datatable-pharmacy').DataTable({
            "dom": '<"top"fl>t<"bottom"ip>r',
            "lengthMenu": [[10, 25, 50, 100], ["10 pozycji", "25 pozycji", "50 pozycji", "100 pozycji"]],
            "language": {
                "url": "/datatables/pl_PL.json"
            },
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "ajaxSource": "/cms-ir/pharmacy",
            "serverMethod": "POST",
            "paginate":true,
            "sortable": true,
            "searchable": true,
            "order": [[ 1, "desc" ]],
            "columnDefs": [
                {
                    "targets": [ 0 ],
                    "render": function (data, type, row) {   // o, v contains the object and value for the column
                        if ( type === 'display' ) {
                            return '<div class="checkbox"><label><input type="checkbox" class="check-row i-grey" /></label></div>';
                        }
                        return data;
                    },
                    "className": "dt-body-center",
                    "sortable": false
                },
                {
                    "targets": [ 2 ],
                    "render": function (data, type, row) {   // o, v contains the object and value for the column
                        if ( type === 'display' ) {
                            return  '<a href="pharmacy/edit/'+data+'" class="btn btn-primary" data-toggle="tooltip" title="Edycja"><i class="fa fa-pencil"></i></a> ' +
                                '<a href="pharmacy/delete/'+data+'" id="'+data+'" class="btn btn-danger" data-toggle="tooltip" title="Usuwanie"><i class="fa fa-trash-o"></i></a>';
                        }
                        return data;
                    },
                    "className": "dt-body-action",
                    "sortable": false
                },
                {
                    "sortable": false,
                    "targets": [ -1 ]
                }
            ],
            "drawCallback": function(  data ) {
                selectedIds = [];

                $('input.i-grey').iCheck({
                    checkboxClass: 'icheckbox_minimal-grey',
                    radioClass: 'iradio_minimal-grey',
                    increaseArea: '20%'
                });

                setTimeout(function () {
                    // select row
                    $('.check-row').on('ifChecked', function(event){
                        var tr = $(this).parent().parent().parent().parent().parent();
                        var data = table.row(tr).data();
                        var id = data[0];
                        selectedIds.push(id);
                        tr.addClass('selected-row');
                        //console.log(selectedIds);
                    });

                    // deselect row
                    $('.check-row').on('ifUnchecked', function(event) {
                        var tr = $(this).parent().parent().parent().parent().parent();
                        var data = table.row(tr).data();
                        var id = data[0];
                        var index = selectedIds.indexOf(id);
                        selectedIds.splice(index, 1);
                        tr.removeClass('selected-row');
                        //console.log(selectedIds);
                    });

                    //check all
                    $('.check-all').on('ifChecked', function (event) {
                        selectedIds = [];
                        $('#datatable-pharmacy tbody tr td .check-row').iCheck('check');
                        //console.log(selectedIds);
                    });

                    //uncheck all
                    $('.check-all').on('ifUnchecked', function (event) {
                        selectedIds = [];
                        $('#datatable-pharmacy tbody tr td .check-row').iCheck('uncheck');
                        //console.log(selectedIds);
                    });
                },0);

                //massive action select
                if($('#datatable-pharmacy_length select[name="massive-action"]').length === 0)
                {
                    $('#datatable-pharmacy_length').append($('.massive-action').html());
                }

                //add new button
                if($('.the-box .bottom .btn-facebook').length === 0)
                {
                    $('.the-box .bottom').append($('.btn-facebook').parent().html());
                    $('.row .col-sm-12 .btn-facebook').remove();
                }

                //massive actions
                $('select[name="massive-action"]').off('change').on('change', function () {
                    var action = $(this).val();
                    if(action.length > 0)
                    {
                        var modal = action + 'MassiveModal';
                        $('#'+modal).on('show.bs.modal', function () {
                            if(selectedIds.length > 0)
                            {
                                $('#'+modal+' .content').show();
                                $('#'+modal+' .modal-footer input[type="submit"][value="Tak"]').show();
                                $('#'+modal+' .message').hide();

                                if(action === 'delete') // massive delete
                                {
                                    $('#'+modal+' form input').off('click').on('click', function (ev) {
                                        ev.preventDefault();
                                        var del = $(this).val();
                                        del == 'Tak' ? $('.spinner').show() : $('.spinner').hide();

                                        $.ajax({
                                            type: "POST",
                                            url: "/cms-ir/pharmacy/delete/1",
                                            dataType : 'json',
                                            data: {
                                                modal: true,
                                                id: selectedIds,
                                                del: del
                                            },
                                            success: function(json)
                                            {
                                                $('.spinner').hide();
                                                $('.check-all').iCheck('uncheck');
                                                $('#'+modal).modal('hide');
                                                $('select[name="massive-action"]').val('');
                                                table.ajax.reload();
                                            }
                                        });

                                    });
                                } else // massive change status
                                {
                                    $('#'+modal+' form input').off('click').on('click', function (ev) {
                                        ev.preventDefault();
                                        var del = $(this).val();
                                        del == 'Zapisz' ? $('.spinner').show() : $('.spinner').hide();
                                        var statusId = $('#'+modal+' select[name="status"]').val();
                                        $.ajax({
                                            type: "POST",
                                            url: "/cms-ir/pharmacy/change-status/1",
                                            dataType : 'json',
                                            data: {
                                                modal: true,
                                                id: selectedIds,
                                                statusId: statusId,
                                                del: del
                                            },
                                            success: function(json)
                                            {
                                                $('.spinner').hide();
                                                $('.check-all').iCheck('uncheck');
                                                $('#'+modal).modal('hide');
                                                $('select[name="massive-action"]').val('');
                                                $('#'+modal+' select[name="status"]').val(1);
                                                table.ajax.reload();
                                            }
                                        });

                                    });
                                }

                            } else
                            {
                                $('#'+modal+' .content').hide();
                                $('#'+modal+' .modal-footer input[type="submit"][value="Tak"]').hide();
                                $('#'+modal+' .modal-footer input[type="submit"][value="Zapisz"]').hide();
                                $('#'+modal+' .message').show();
                            }
                        }).modal('show');
                    }
                });

                $('#deleteMassiveModal, #statusMassiveModal').off().on('hidden.bs.modal', function () {
                    $('select[name="massive-action"]').val('');
                }).modal('hide');
            }
        });

        // delete modal
        $('#datatable-pharmacy tbody').on('click', '.btn-danger', function (e) {
            e.preventDefault();
            var entityId = $(this).attr('id');
            $('#deleteModal').off().on('show.bs.modal', function () {

                $('#deleteModal form input').off('click').on('click', function (ev) {
                    ev.preventDefault();
                    var del = $(this).val();
                    del == 'Tak' ? $('.spinner').show() : $('.spinner').hide();

                    $.ajax({
                        type: "POST",
                        url: "/cms-ir/pharmacy/delete/" +entityId,
                        dataType : 'json',
                        data: {
                            modal: true,
                            id: entityId,
                            del: del
                        },
                        success: function(json)
                        {
                            $('.spinner').hide();
                            $('#deleteModal').modal('hide');
                            table.ajax.reload();
                        }
                    });

                });

            }).modal('show');
        });

    }
});