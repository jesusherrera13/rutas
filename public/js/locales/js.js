$(document).ready(function() {

    $('#modal-registro').on('shown.bs.modal', function (e) {
      
    });

    $('#modal-registro').on('hidden.bs.modal', function (e) {
        
        getData();
        $('#modal-registro  .modal-title').html('Casilla');
    });

    $('#btn-nuevo').click(function() {

        formReset($('#form-registro'));

        $('#modal-registro').modal('toggle');

        $('#id_distrito_federal option').prop('checked', 0);
    });

    $('#distrito_federal').dblclick(function() {

        $('#distrito_federal option').prop('selected', 0);
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
            { data: "distrito_federal" },
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
        if(!$('#distrito_federal').val().length) msj += 'Seleccione el Distrito Federal<br>';

        // console.log($('#distrito_federal').val())

        if(msj) dialog_alert({id: 'dialog-alert', body: msj});
        else {

            var param = {
                distrito_federal: ''
            };

            $('#distrito_federal option:selected').each(function(i, o) {

                if(param.distrito_federal) param.distrito_federal += ';';

                param.distrito_federal += 'id_distrito_local,' + $('#id').val() + '|no_distrito_local,' + $('#no_distrito').val();
                param.distrito_federal += '|id_distrito_federal,' + $(o).val() +'|no_distrito_federal,' + $(o).attr('no_distrito');
            });

            /*
            for(var i in $('#distrito_federal option:selected').val()) {

                if(param.distrito_federal) param.distrito_federal += ';';

                param.distrito_federal += $('#distrito_federal').val()[i];
            }
            */

            modal_confirm({
                message: '¿Desea grabar ' + ($('#id').val() ? ' los cambios' : ' el registro') + '?',
                route: 'distrito-local',
                form: 'form-registro',
                param: param
            });

            /*var txt = $('#id').val() ? ' los cambios' : ' el registro';
            
            if(confirm('\u00BFDesea grabar ' + txt + '?')) {

                var metodo = $('#id').val() ? 'update' : 'store';

                var param = paramMaker({form: $('#form-registro')});

                $.ajax({
                    url: window.location.origin + '/distrito-local/' + metodo,
                    dataType: 'json',
                    method: 'post',
                    data: param,
                    success: function(data) {

                        $('#modal-registro').modal('toggle');

                        getData();
                    },
                    error: function(jqXHR, textStatus, erroThrown) {

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
        url: window.location.origin + '/distritos-locales',
        cache: false,
        data: param,
        success :  function(data) {

            if(param.id) {

                Object.keys(data[0]).forEach(function(k) {

                    $('#' + k).val(data[0][k]);
                });

                $('#distrito_federal option').prop('selected', 0);

                if(data[0].distritos_federales) {

                    // console.log(data[0].distritos_federales);
                    for(var i in data[0].distritos_federales) {

                        $('#distrito_federal [value="' + data[0].distritos_federales[i] + '"]').prop('selected', 1);
                    }
                }

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