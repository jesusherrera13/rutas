$(document).ready(function() {

    $('#btn-nuevo').click(function() {

        formReset($('#form-registro'));

        $('#modal-registro').modal('toggle');

        referentes();
    });

    $('#modal-registro').on('shown.bs.modal', function (e) {
      
    });

    $('#modal-registro').on('hidden.bs.modal', function (e) {
        
        getData();
        $('#modal-registro  .modal-title').html('Casilla');
    });

    $('#tbl-data').DataTable({
        responsive: true,
        colReorder: true,
        // lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todo"]],
        language: {
            // search: "_INPUT_",
            search: "Filtrar:",
            searchPlaceholder: "Buscar...",
            // sLengthMenu: "_MENU_"
        },
        columns: [
            { data: "contacto" },
            { data: "contacto_corto" },
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
            {
                orderable: false,
                targets: -1,
            }
        ],
        createdRow: function(row, data, dataIndex) {
            
        }
    });

    $('#tbl-referentes').DataTable({
        responsive: true,
        colReorder: true,
        "dom": 'frtip',
        // paging: false,
        pageLength : 6,
        language: {
            // search: "_INPUT_",
            search: "Buscar:",
            searchPlaceholder: "Buscar...",
            // sLengthMenu: "_MENU_"
        },
        columns: [
            { data: "contacto" },
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
            {
                orderable: false,
                targets: -1,
            }
        ],
        createdRow: function(row, data, dataIndex) {

        }
    });

    $('#tbl-contactos').DataTable({
        responsive: true,
        colReorder: true,
        "dom": 'frtip',
        // lengthMenu: [[7], [7]],
        // paging: false,
        // lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todo"]],
        pageLength : 6,
        language: {
            // search: "_INPUT_",
            search: "Buscar:",
            searchPlaceholder: "Buscar...",
            // sLengthMenu: "_MENU_"
        },
        columns: [
            { data: "contacto" },
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
            {
                orderable: false,
                targets: -1,
            }
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

            tr.remove();

            var dt_ = $('#tbl-referentes').DataTable();

            row.id_contacto = row.id;

            delete row.id;

            row.id = null;

            dt_.row.add(row);
            dt_.draw();
        }
        else if(accion == 'delete') {

            var tbl = $(this).parents().eq(3);
            var lista_delete = tbl.attr('lista_delete') || '';

            if(lista_delete) lista_delete += ';';

            var id = row.id || '';

            lista_delete += id;

            var items = 'id,' + id + '|id_contacto,' + row.id_contacto + '|status,0';

            modal_confirm({
                message: '¿Desea quitar al coordinador?<br><h3>' + row.contacto + '</h3>',
                route: 'coordinador',
                metodo: 'guardar',
                callback: 'referentes',
                param: {
                    accion: accion,
                    items: items
                },
                form: 'form-registro'
            });
        }
    });

    $('#btn-buscar').click(function() {

        getData();
    });

    $('#btn-guardar').click(function() {

        var msj = '';

        if(msj) dialog_alert({id: 'dialog-alert', body: msj});
        else {
            
            modal_confirm({
                message: '¿Desea grabar ' + ($('#id').val() ? ' los cambios' : ' el registro') + '?',
                route: 'referente',
                metodo: 'guardar',
                callback: 'referentes',
                param: {
                    accion: 'add',
                    items: seleccionadosParse({get_keys: true})
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
        url: window.location.origin + '/referentes',
        cache: false,
        data: param,
        success :  function(data) {

            if(param.id) {

                Object.keys(data[0]).forEach(function(k) {

                    $('#' + k).val(data[0][k]);
                });

                $('#modal-registro  .modal-title').html('Casilla: ' + data[0]['casilla']);

                dataTableSetData([{id: 'tbl-referentes', data: data[0].representantes}]);

                var items = '';

                for(var i in data[0].representantes) {

                    if(items) items += ';';

                    items += data[0].representantes[i].id_contacto;
                }

                referentes({
                    seleccionados: items,
                    mod_op: 'representantes_seleccionados',
                    id_modulo: 'casillas'
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

function referentes(param) {

    spinner();

    param = param || {};

    param.dataType = 'json';

    if(!param.seleccionados) param.seleccionados = seleccionadosParse({key: 'id'});

    param = paramMaker({json: param, form: $('#form')});

    $.ajax({
        type: 'POST',
        method: 'post',
        dataType: 'json',
        url: window.location.origin + '/referentes-contactos',
        cache: false,
        data: param,
        success :  function(data) {

            dataTableSetData([
                {id: 'tbl-referentes', data: data.referentes},
                {id: 'tbl-contactos', data: data.contactos}
            ]);

            spinner({close: true});
        },
        error: function(jqXHR, textStatus, erroThrown) {
            
            spinner({close: true});
        }
    });
}

function seleccionados() {

    var dt = $('#tbl-referentes').DataTable();

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

            data += 'id,' + (tmp[i].id || '') + '|id_contacto,' + tmp[i].id_contacto;
        }
        else data += (tmp[i][param.key] || '0');
    }

    return data;
}