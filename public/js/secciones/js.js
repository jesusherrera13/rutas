$(document).ready(function() {

    $('#btn-nuevo, #btn-reset').click(function() {

        distritosLocales();

        formReset($('#form-registro'));

        if($(this).attr('id') == 'btn-nuevo') $('#modal-registro').modal('toggle');
        else if($(this).attr('id') == 'btn-reset') {

            var dt = $('#tbl-casillas').DataTable();
            dt.clear().draw();
        }

        $('#id_distrito_federal option').prop('checked', 0);
    });

    $('#btn-agregar').click(function() {

        formReset($('#form-casilla'));

        var dt = $('#tbl-add').DataTable();

        dt.clear().draw();

        banned();

        $('#modal-casilla').modal('toggle');
    });

    $('#btn-add').click(function() {

        var id_tipo_casilla = $('#id_tipo_casilla').val();
        var casilla;
        var data = [];

        var dta = $('#tbl-add').DataTable();

        var no_casilla = 1;

        var dt = $('#tbl-casillas').DataTable();

        if(dt.data().count()) {

            dt.rows().every( function ( rowIdx, tableLoop, rowLoop ) {

                var row = this.data();
                
                if(id_tipo_casilla == row.id_tipo_casilla) {

                    no_casilla = parseInt(row.no_casilla) + 1;
                }
            });
        }

        if(dta.data().count()) {

            dta.rows().every( function ( rowIdx, tableLoop, rowLoop ) {

                var row = this.data();

                data.push(row);
                
                if(id_tipo_casilla == row.id_tipo_casilla) {

                    no_casilla = parseInt(row.no_casilla) + 1;
                }
            });
        }

        casilla = $('#no_seccion').val() + '-' +id_tipo_casilla;

        if(id_tipo_casilla == 'C')  casilla += no_casilla

        data.push(
            {
                casilla: casilla,
                id_tipo_casilla: id_tipo_casilla,
                no_casilla: no_casilla
            }
        );

        var json = [
            {
                casilla: casilla,
                id_tipo_casilla: id_tipo_casilla,
                no_casilla: no_casilla
            }
        ];

        dataTableSetData([{id: 'tbl-add', data: data}]);

        banned();
    });

    $('#id_distrito_federal').change(function() {

        distritosLocales({ id_distrito_federal: $(this).val() });
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
            { data: "no_seccion" },
            { data: "distrito_federal" },
            { data: "distrito_local" },
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
        buttons: [
            'pdf'
        ],
        createdRow: function(row, data, dataIndex) {

        }
    });

    $('#tbl-casillas').DataTable({
        responsive: true,
        paging: false,
        searching: false,
        info: false,
        ordering: false,
        columns: [
            { data: "casilla" },
            // { data: "tipo" },
            { data: null,        
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {

                    var input = '';

                    if(oData.id_tipo_casilla != 'B') {

                        var checked = oData.status ? 'checked="checked"' : '';

                        input = '<input type="checkbox" ' + checked + '>';
                    }

                    $(nTd).html(input);
                }
            },
        ],
        columnDefs: [
            {
                orderable: false,
                // className: 'select-checkbox',
                targets:   1
            }
        ],
        createdRow: function(row, data, dataIndex) {

        }
    });

    $('#tbl-add').DataTable({
        responsive: true,
        paging: false,
        searching: false,
        info: false,
        ordering: false,
        columns: [
            { data: "casilla" }
        ],
        columnDefs: [
            {
                // orderable: false,
                // targets: 0
            }
        ],
        createdRow: function(row, data, dataIndex) {

        }
    });

    $('body')
    .on('click', '.btn-editar', function() {

        distritosLocales();

        var dt = $(this).parents().eq(3).DataTable();
        var tr = $(this).parents().eq(1);
        var row = dt.row(tr).data();

        getData({id: row.id});
    })
    .on('click', '#tbl-casillas :checkbox', function() {

        var dt = $(this).parents().eq(3).DataTable();
        var tr = $(this).parents().eq(1);
        var row = dt.row(tr).data();

        // console.log(row)
    });

    $('#btn-buscar').click(function() {

        getData();
    });

    $('#btn-grabar').click(function() {

        var msj = '';

        if(!$('#no_seccion').val()) msj += 'Escriba la sección<br>';
        if(!$('#id_distrito_federal').val()) msj += 'Seleccione el Distrito Federal<br>';
        if(!$('#id_distrito_local').val()) msj += 'Seleccione el Distrito Local<br>';

        if(msj) dialog_alert({id: 'dialog-alert', body: msj});
        else {

            var param = { lista: '' };

            var dt = $('#tbl-casillas').DataTable();

            if(dt.data().count()) {

                $('#tbl-casillas > tbody > tr').each(function(i, o) {

                    var row = dt.rows($(o)).data();

                    var no_casilla = row[0].no_casilla || '';
                    var status;

                    if(row[0].id_tipo_casilla == 'B') status = row[0].status;
                    else status = ($(o).find(':checkbox')).prop('checked') ? 1 : 0;

                    if(param.lista) param.lista += ';';

                    param.lista += 'id,' + row[0].id +'|id_seccion,' + row[0].id_seccion + '|id_tipo_casilla,' + row[0].id_tipo_casilla;
                    param.lista += '|status,' + status + '|no_casilla,' + no_casilla;

                });

                /*dt.rows().every( function ( rowIdx, tableLoop, rowLoop ) {

                    var row = this.data();

                    if(param.lista) param.lista += ';';

                    param.lista += 'id,' + param.id +'|no_seccion,' + no_seccion;
                    param.lista += '|id_tipo_casilla,' + row.id_tipo_casilla + '|no_casilla,' + row.no_casilla;
                });*/
            }

            modal_confirm({
                message: '¿Desea grabar ' + ($('#id').val() ? 'los cambios' : 'el registro') + '?',
                route: 'seccion',
                param: param,
                form: 'form-registro'
            });
        }
    });

    $('#btn-grabar-casillas').click(function() {

        var id_seccion = $('#id').val();
        // var id_tipo_casilla = $('#id_tipo_casilla').val();

        var dt = $('#tbl-add').DataTable();

        var no_casilla = 1;

        var param = {
            id: $('#id').val(),
            lista: ''
        };

        if(dt.data().count()) {

            dt.rows().every( function ( rowIdx, tableLoop, rowLoop ) {

                var row = this.data();

                if(param.lista) param.lista += ';'

                param.lista += 'id_seccion,' + id_seccion + '|id_tipo_casilla,' + row.id_tipo_casilla;
                param.lista += '|no_casilla,' + row.no_casilla;
            });

            modal_confirm({
                message: '¿Desea grabar grabar las casillas?',
                route: 'casilla',
                metodo: 'guardar',
                param: param,
                form: 'form-registro',
                modals: [
                    { id: 'modal-casilla' }
                ]
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
        url: window.location.origin + '/secciones',
        cache: false,
        data: param,
        success :  function(data) {

            if(param.id) {

                Object.keys(data[0]).forEach(function(k) {

                    $('#' + k).val(data[0][k]);
                });

                distritosLocales({
                    id_distrito_federal: data[0].id_distrito_federal,
                    id_distrito_local: data[0].id_distrito_local,
                })

                if(data[0].casillas) dataTableSetData([{id: 'tbl-casillas', data: data[0].casillas}]);


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

function distritosLocales(param) {

    // console.log(param)

    param = param || {};

    if(param.id_distrito_federal) {
        
        spinner();

        var param = paramMaker({json: param, form: $('#form')});

        $('#id_distrito_local').html('');

        var option = $('<option>', {
            value: '',
            text: '',
        });

        $('#id_distrito_local').append(option);

        $.ajax({
            url: window.location.origin + '/distritos-ligues',
            dataType: 'json',
            method: 'post',
            data: param,
            success: function(data) {

                for(var i in data) {

                    option = $('<option>', {
                        value: data[i].id_distrito_local,
                        text: data[i].distrito_local,
                    });

                    $('#id_distrito_local').append(option);
                }

                if(param.id_distrito_local) $('#id_distrito_local').val(param.id_distrito_local);

                spinner();
            },
            error: function(jqXHR, textStatus, erroThrown) {

                var title = jqXHR.responseJSON.message;
                var error = '';

                for(var r in jqXHR.responseJSON.errors) {

                    if(error) error += '<br>';

                    error += jqXHR.responseJSON.errors[r];
                }

                spinner();

                dialog_alert({id: 'dialog-alert',title: title,body: error});
            }
        });
    }
    else $('#id_distrito_local').html('');
}

function banned() {

    var banned = [];

    var dtc = $('#tbl-casillas').DataTable();

    if(dtc.data().count()) {

        dtc.rows().every( function ( rowIdx, tableLoop, rowLoop ) {

            var row = this.data();

            if(row.id_tipo_casilla == 'B' || row.id_tipo_casilla == 'E') {

                if(banned.indexOf(row.id_tipo_casilla) < 0) banned.push(row.id_tipo_casilla);
            }
        });
    }

    var dta = $('#tbl-add').DataTable();

    if(dta.data().count()) {

        dta.rows().every( function ( rowIdx, tableLoop, rowLoop ) {

            var row = this.data();

            if(row.id_tipo_casilla == 'B' || row.id_tipo_casilla == 'E') {

                if(banned.indexOf(row.id_tipo_casilla) < 0) banned.push(row.id_tipo_casilla);
            }
        });
    }

    $('#id_tipo_casilla *').show();
    // $('#id_tipo_casilla').val()

    // console.log(banned);

    if(banned.length) {

        for(var i in banned) {

            if(banned[i] == $('#id_tipo_casilla').val()) $('#id_tipo_casilla').val('');

            $('#id_tipo_casilla [value="' + banned[i] + '"]').hide();
        }
    }
}