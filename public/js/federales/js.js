$(document).ready(function() {

    $('#btn-nuevo').click(function() {

        formReset($('#form-registro'));

        $('#dialog').modal('toggle');
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
            { data: null,        
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {

                    $(nTd).html('');
                }
            },
            { data: null,        
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {

                    var html = '';

                    html += '<button class="btn btn-success btn-sm btn-editar">';
                    html += '   <i class="fas fa-edit"></i>';
                    html += '  </button>';

                    $(nTd).html(html);
                }
            },
        ],
        columnDefs: [
            
        ],
        createdRow: function(row, data, dataIndex) {

            
        }
    });

    $('body')
    .on('click', '.btn-editar', function() {

        var dt = $(this).parents().eq(3).DataTable();
        var tr = $(this).parents().eq(1);
        var row = dt.row(tr).data();

        getData({id: row.id});
    });

    $('#btn-buscar').click(function() {

        getData();
    });

    $('#btn-grabar').click(function() {

        var msj = '';

        if(!$('#descripcion').val()) msj += 'Escriba la descripción<br>';
        if(!$('#no_distrito').val()) msj += 'Escriba el número del distrito<br>';


        if(msj) dialog_alert({id: 'dialog-alert', body: msj});
        else {
            
            modal_confirm({
                message: '¿Desea grabar ' + ($('#id').val() ? ' los cambios' : ' el registro') + '?',
                route: 'distrito-federal',
                form: 'form-registro'
            });
            
            /*if(confirm('\u00BFDesea grabar ' + txt + '?')) {

                var metodo = $('#id').val() ? 'update' : 'store';

                var param = paramMaker({form: $('#form-registro')});

                $.ajax({
                    url: window.location.origin + '/distrito-federal/' + metodo,
                    dataType: 'json',
                    method: 'post',
                    data: param,
                    success: function(data) {

                        $('#dialog').modal('toggle');

                        getData();
                    },
                    error: function(jqXHR, textStatus, erroThrown) {

                        console.log(jqXHR.responseJSON)

                        var title = jqXHR.responseJSON.message;
                        var error = '';

                        for(var r in jqXHR.responseJSON.errors) {

                            if(error) error += '<br>';

                            error += jqXHR.responseJSON.errors[r];
                        }

                        dialog_alert({id: 'dialog-alert',title: title,body: error});
                    }
                });
            }*/
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
        url: window.location.origin + '/distritos-federales',
        cache: false,
        data: param,
        success :  function(data) {

            if(param.id) {

                Object.keys(data[0]).forEach(function(k) {

                    $('#' + k).val(data[0][k]);
                });

                $('#dialog').modal('toggle');
            }
            else dataTableSetData([{id: 'tbl-data', data: data}]);

            spinner();
        }
    });
}