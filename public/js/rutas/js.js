var rgs = new Bloodhound({  
    datumTokenizer: function(rgs) {
      return Bloodhound.tokenizers.whitespace(rgs.value);
    },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        url: window.location.origin + '/contactos-data/?term=%QUERY&dataType=json',
        wildcard: '%QUERY',
        filter: function(response) {

            return response;
        }
    }
});

$(document).ready(function() {

    $('#btn-nuevo').click(function() {

        formReset($('#form-registro'));

        $('#modal-registro').modal('toggle');
    });

    $('#modal-registro').on('shown.bs.modal', function (e) {
      
    });

    $('#modal-registro').on('hidden.bs.modal', function (e) {
        
        $('#tbl-ruta_casillas').removeAttr('lista_delete');

        getData();
    });

    $('#id_distrito_federal').change(function() {

        // console.log($(this).val());
        distritosLocales({ id_distrito_federal: $(this).val() });
    });

    $('#d-representante_general .typeahead').typeahead({ minLength: 3}, {
        name: 'rgs-lista',
        limit: 10,
        display: 'contacto',
        source: rgs
    })
    .on('typeahead:selected', function($e, data) {

        $('#id_rg').val(data.id);
        // $('#id_estado').val(data.id_estado);
        // $('#id_municipio').val(data.id_municipio);
        // $('#id_asentamiento').val(data.id);

        $(this).attr('seleccionado', 1);
    })
    .on('typeahead:change', function($e, data) {

        if(!$(this).attr('seleccionado')) {

            $('#id_rg').val('');
        }
    })
    .on('keyup', this, function (event) {

        if (event.keyCode == 8) {

            $(this).removeAttr('seleccionado');
            $('#id_rg').val('');
        }
    });

    $('#tbl-data').DataTable({
        responsive: true,
        colReorder: true,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todo"]],
        columns: [
            { data: "descripcion" },
            // { data: "no_rcs" },
            { data: "distrito_federal" },
            { data: "distrito_local" },
            { data: "representante_general" },
            /*{ data: null,        
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {

                    $(nTd).html('');
                }
            },*/
            { data: null,        
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {

                    var html = '';

                    html += '<button class="btn btn-success btn-sm btn-editar">';
                    html += '   <i class="fas fa-edit"></i>';
                    html += '</button>';

                    html += '<button class="btn btn-warning btn-sm btn-imprimir ml-1">';
                    html += '   <i class="fas fa-print"></i>';
                    html += '</button>';

                    $(nTd).html(html);
                }
            },
        ],
        "columnDefs": [
            // { "width": "20%", "targets": 0 },
            { 
                className: 
                "dt-nowrap", "targets": [ 3 ] 
            }
        ],
        createdRow: function(row, data, dataIndex) {
            
        }
    });

    $('#tbl-ruta_casillas').DataTable({
        responsive: true,
        colReorder: true,
        // paging: false,
        // lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todo"]],
        columns: [
            { data: "casilla" },
            { data: null,        
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {

                    var html = '';

                    // html += '<button class="btn btn-danger btn-sm pin" accion="delete">';
                    // html += '   <i class="fas fa-trash-alt"></i>';
                    // html += '</button>';

                    html += '<a class="btn btn-danger btn-sm pin" href="javascript:void(0)" role="button" accion="delete">'
                    html += '   <i class="fas fa-trash-alt"></i></a>';
                    html += '</a>';

                    $(nTd).html(html);
                }
            },
        ],
        columnDefs: [
            
        ],
        createdRow: function(row, data, dataIndex) {

        }
    });

    $('#tbl-casillas').DataTable({
        responsive: true,
        colReorder: true,
        // paging: false,
        // lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todo"]],
        // pageLength : 5,
        columns: [
            { data: "casilla" },
            { data: null,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {

                    var html = '';

                    html += '<button class="btn btn-primary btn-sm pin" accion="add">';
                    html += '   <i class="fas fa-plus-circle"></i>';
                    html += '</button>';

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
    })
    .on('click', '.pin', function() {

        var accion = $(this).attr('accion');
        var dt = $(this).parents().eq(3).DataTable();
        var tr = $(this).parents().eq(1);
        var row = dt.row(tr).data();
        var clase = $(this).parents().eq(3).attr('clase');

        if(accion == 'add') {

            var tbl = $('#tbl-ruta_casillas');
            var del = [];

            tr.remove();

            var dt_ = $('#tbl-ruta_casillas').DataTable();

            row.id_casilla = row.id;
            row.id_ruta = $('#id').val();

            delete row.id;

            row.id = null;

            var lista_delete = $('#tbl-ruta_casillas').attr('lista_delete') || '';

            var tmp = lista_delete.split(';');

            for(var i in tmp) {

                if(tmp[i] != row.id_casilla) del.push(tmp[i]);
            }

            lista_delete = '';

            for(var i in del) {

                lista_delete += del[i];
            }

            tbl.attr('lista_delete', lista_delete);
            
            dt_.row.add(row).draw();
            // dt_.draw();
        }
        else if(accion == 'delete') {

            var dt = $(this).parents().eq(3).DataTable();
            var tbl = $(this).parents().eq(3);
            var tr = $(this).parents().eq(1);
            var lista_delete = tbl.attr('lista_delete') || '';
            var id = row.id || '';

            if(lista_delete) lista_delete += ';';

            // if(id) 
            lista_delete += row.id_casilla;

            var item_delete = 'id,' + id + '|id_casilla,' + row.id_casilla + '|status,0';
            // var items = 'id,' + id + '|id_casilla,' + row.id_casilla + '|status,0';

            var items = seleccionadosParse({get_keys: true});

            // console.log(items)
            if(id) {

            }
            else {

            }

            /*
            modal_confirm({
                message: '¿Desea quitar la casilla?<br><h3>' + row.casilla + '</h3>',
                route: 'ruta-casilla',
                metodo: 'borrar',
                param: {
                    item_delete: item_delete,
                    items: items,
                    id: $('#id').val()
                },
                form: 'form-registro'
            });
            */

            tbl.attr('lista_delete', lista_delete);
            dt.row(tr).remove().draw();

            casillas({id_modulo: 'rutas'});
        }
    })
    .on('click', '.btn-imprimir', function() {

        var dt = $(this).parents().eq(3).DataTable();
        var tr = $(this).parents().eq(1);
        var row = dt.row(tr).data();

        console.log(row);

        window.open(window.location.origin + '/ruta-impresion/' + row.id);

    });

    $('#btn-buscar').click(function() {

        getData();
    });

    $('#btn-guardar').click(function() {

        var msj = '';

        if(!$('#id_distrito_federal').val()) msj += 'Seleccione el Distrito Federal<br>';
        if(!$('#id_distrito_local').val()) msj += 'Seleccione el Distrito Local<br>';
        if(!$('#descripcion').val()) msj += 'Escriba el nombre de la ruta<br>';

        if(msj) dialog_alert({id: 'dialog-alert', body: msj});
        else {
            
            modal_confirm({
                message: '¿Desea grabar ' + ($('#id').val() ? ' los cambios' : ' el registro') + '?',
                route: 'ruta',
                param: {
                    items: seleccionadosParse({get_keys: true}),
                    items_delete: $('#tbl-ruta_casillas').attr('lista_delete') || '',
                    id: $('#id').val()
                },
                form: 'form-registro'
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
        url: window.location.origin + '/rutas',
        cache: false,
        data: param,
        success :  function(data) {

            if(param.id) {

                Object.keys(data[0]).forEach(function(k) {

                    $('#' + k).val(data[0][k]);
                });

                var items = '';

                for(var i in data[0].ruta_casillas) {

                    if(items) items += ';';

                    items += data[0].ruta_casillas[i].id_casilla;
                }

                casillas({
                    seleccionados: items,
                    mod_op: 'items_seleccionados',
                    id_modulo: 'rutas'
                });

                distritosLocales({
                    id_distrito_federal: data[0].id_distrito_federal,
                    id_distrito_local: data[0].id_distrito_local,
                });

                dataTableSetData([{id: 'tbl-ruta_casillas', data: data[0].ruta_casillas}]);

                var items = '';

                for(var i in data[0].representantes) {

                    if(items) items += ';';

                    items += data[0].representantes[i].id_contacto;
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

function casillas(param) {

    spinner();

    param = param || {};

    param.dataType = 'json';

    if(!param.seleccionados) param.seleccionados = seleccionadosParse({key: 'id_casilla'});

    param = paramMaker({json: param, form: $('#form')});

    $.ajax({
        type: 'POST',
        method: 'post',
        dataType: 'json',
        url: window.location.origin + '/casillas',
        cache: false,
        data: param,
        success :  function(data) {

            dataTableSetData([{id: 'tbl-casillas', data: data}]);

            spinner({close: true});
        },
        error: function(jqXHR, textStatus, erroThrown) {
            
            spinner({close: true});
        }
    });
}

function seleccionados() {

    var dt = $('#tbl-ruta_casillas').DataTable();

    var data = [];

    if(dt.data().count()) {

        dt.rows().every( function ( rowIdx, tableLoop, rowLoop ) {

            var row = this.data();

            data.push(row)
        });
    }

    return data;
}

function seleccionadosParse(param) {

    var tmp = seleccionados();

    var data = '';

    for(var i in tmp) {

        if(data) data += ';';

        if(param.get_keys) {

            data += 'id,' + (tmp[i].id || '') + '|id_ruta,' + tmp[i].id_ruta + '|id_casilla,' + tmp[i].id_casilla;
        }
        else data += (tmp[i][param.key] || '0');
    }

    return data;
}

function distritosLocales(param) {

    spinner();

    $('#id_distrito_local').html('<option value=""></option>');

    param = param || {};

    param.dataType = 'json';

    param = paramMaker({json: param, form: $('#form')});

    $.ajax({
        type: 'post',
        method: 'post',
        dataType: 'json',
        url: window.location.origin + '/distritos-ligues',
        cache: false,
        data: param,
        success :  function(data) {

            // dataTableSetData([{id: 'tbl-contactos', data: data}]);
            for(var i in data) {

                var option = $('<option>', {
                    value: data[i].id_distrito_local,
                    text: data[i].distrito_local,
                });

                if(data[i].id_distrito_local == param.id_distrito_local) option.attr('selected', 'selected');

                $('#id_distrito_local').append(option);
            }

            spinner({close: true});
        },
        error: function(jqXHR, textStatus, erroThrown) {
            
            spinner({close: true});
        }
    });
}