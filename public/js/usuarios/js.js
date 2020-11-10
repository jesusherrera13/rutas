$(document).ready(function() {

    $('#btn-nuevo').click(function() {

        formReset($('#form-registro'));

        $('#modal-registro').modal('show');
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
            { data: "name" },
            { data: "email" },
            { data: null,        
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {

                    var html = '';

                    // html += '<button class="btn btn-success btn-sm btn-editar">';
                    html += '<i class="fas fa-edit btn-editar btn-pin"></i>';
                    // html += '</button>';

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

        if(!$('#name').val()) msj += 'Escriba el nombre<br>';

        if(!$('#password').val()) msj += 'Escriba el password<br>';

        if(!$('#password_').val()) msj += 'Confirme el password<br>';

        if($('#password').val() && $('#password_').val()) {
            
            if($('#password').val() != $('#password_').val()) msj += 'Las contraseñas no coinciden<br>';
        }

        if(msj) dialog_alert({id: 'dialog-alert', body: msj});
        else {

            var param = {distritos_federales: '',distritos_locales: ''};

            $('#tbl-distritos-federales :checkbox:checked').each(function(i, o) {

                if(param.distritos_federales) param.distritos_federales += ';';

                param.distritos_federales += 'id,' + ($(o).attr('iddb') || '') + '|id_distrito_federal,' + $(o).val();
            });

            $('#tbl-distritos-locales :checkbox:checked').each(function(i, o) {

                if(param.distritos_locales) param.distritos_locales += ';';

                param.distritos_locales += 'id,' + ($(o).attr('iddb') || '') + '|id_distrito_local,' + $(o).val();
            });

            // console.log(param);
            
            modal_confirm({
                message: '¿Desea grabar ' + ($('#id').val() ? ' los cambios' : ' el registro') + '?',
                route: 'usuario',
                form: 'form-registro',
                param: param
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
        url: window.location.origin + '/usuarios',
        cache: false,
        data: param,
        success :  function(data) {

            if(param.id) {

                Object.keys(data[0]).forEach(function(k) {

                    $('#' + k).val(data[0][k]);
                });

                if(data[0].distritos_federales) {
                    
                    for(var i in data[0].distritos_federales) {

                        $('#tbl-distritos-federales [value="' + data[0].distritos_federales[i].id_distrito_federal +'"]').prop('checked', 1);
                    }
                }

                if(data[0].distritos_locales) {
                    
                    for(var i in data[0].distritos_locales) {

                        $('#tbl-distritos-locales [value="' + data[0].distritos_locales[i].id_distrito_local +'"]').prop('checked', 1);
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