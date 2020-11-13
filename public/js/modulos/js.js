$(document).ready(function() {

    $('#btn-nuevo').click(function() {

        formReset($('#form-registro'));

        $('#modal-registro').modal('show');
    });

    $('#modal-registro').on('hidden.bs.modal', function (e) {
        
        getData();
        
        $('#modal-registro  .modal-title').html('Módulos');
    });
    
    $('#tbl-data').DataTable({
        responsive: true,
        colReorder: true,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todo"]],
        /*language: {
            search: 'Buscar:',
            lengthMenu: '_MENU_ registros',
            zeroRecords: 'Sin registros para mostrar',
            info: '(_START_-_END_) de _TOTAL_',
            infoEmpty: 'No records available',
            paginate: {
                first:      '&#124;&lt;',
                last:       '&gt;&#124;',
                next:       '&gt;',
                previous:   '&lt;'
            },
        },*/
        columns: [
            { data: "descripcion" },
            { data: "url" },
            { data: "icon" },
            { data: "action" },
            /* { data: null,        
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {

                    var html = '';

                    // html += '<button class="btn btn-success btn-sm btn-editar">';
                    html += '<i class="fas fa-edit btn-editar btn-pin" iddb="' + oData.id + '"></i>';
                    // html += '</button>';

                    $(nTd).html(html);
                }
            }, */
        ],
        columnDefs: [
            
        ],
        createdRow: function(row, data, dataIndex) {

            
        }
    });

    $('body')
    .on('click', '.btn-editar', function() {

        var iddb = $(this).attr('iddb');
        var dt = $('#tbl-data').DataTable();
        var row;

        dt.rows().every( function ( rowIdx, tableLoop, rowLoop ) {

            var d = this.data();
            
            if(d.id == iddb) {

                row = d;

                console.log(row)

                $('#row_id').val(rowIdx);
                $('#DT_RowIndex').val(row.DT_RowIndex);
            }
        });

        getData({ id: $(this).attr('iddb') });
    });

    $('#btn-buscar').click(function() {

        getData();
    });

    $('#btn-grabar').click(function() {

        var msj = '';

        if(!$('#descripcion').val()) msj += 'Escriba la descripción<br>';
        if(!$('#url').val()) msj += 'Escriba la URL<br>';

        if(msj) dialog_alert({id: 'dialog-alert', body: msj});
        else {
            
            /* 
            modal_confirm({
                message: '¿Desea grabar ' + ($('#id').val() ? ' los cambios' : ' el registro') + '?',
                route: 'modulo',
                form: 'form-registro'
            }); 
            */

            modal_confirm({
                message: '¿Desea grabar ' + ($('#id').val() ? ' los cambios' : ' el registro') + '?',
                route: 'modulo',
                route_data: 'modulos',
                id_table: 'tbl-data',
                // param: param,
                form: 'form-registro',
                id: $('#id').val(),
                row_id: $('#row_id').val(),
                DT_RowIndex: $('#DT_RowIndex').val(),
            });
        }
    });
});


function getData(param) {

    spinner();

    param = param || {};
    param.dataType = 'json';
    param = paramMaker({json: param, form: $('#form')});

    $.ajax({
        type: 'POST',
        method: 'post',
        dataType: 'json',
        url: window.location.origin + '/modulos',
        cache: false,
        data: param,
        success :  function(data) {

            if(param.id) {

                Object.keys(data[0]).forEach(function(k) {

                    $('#' + k).val(data[0][k]);
                });

                $('#modal-registro').modal('show');
            }
            else dataTableSetData([{id: 'tbl-data', data: data}]);

            spinner({close: true});
        },
        error: function(jqXHR, textStatus, erroThrown) {
            
            spinner({close: true});
        }
    });
}