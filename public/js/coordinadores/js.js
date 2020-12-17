var table;

var asentamientos = new Bloodhound({  
    datumTokenizer: function(asentamientos) {
      return Bloodhound.tokenizers.whitespace(asentamientos.value);
    },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        url: window.location.origin + '/asentamientos/?term=%QUERY&dataType=json',
        wildcard: '%QUERY',
        filter: function(response) {

            return response;
        }
    }
});

$(document).ready(function() {

    /* $('#btn-nuevo').click(function() {

        formReset($('#form-registro'));

        $('#modal-registro').modal('show');

        coordinadores();
    }); */

    $('#modal-registro').on('shown.bs.modal', function (e) {
      
    });

    $('#modal-registro').on('hidden.bs.modal', function (e) {
        
        getData();

        $('#modal-registro  .modal-title').html('Casilla');
    });

    $('#btn-nuevo').click(function() {

        // $('#modal-filtro').modal('show');
        getData({open: true});
    });

    $('#modal-filtro').on('shown.bs.modal', function (e) {

        // contactos();
    });

    table = $('#tbl-data').DataTable({
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
            { data: null,        
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {

                    var html = '';

                    /* html += '<button class="btn btn-success btn-sm btn-editar">';
                    html += '   <i class="fas fa-edit btn-editar"></i>';
                    html += '</button>'; */
                    html += '   <i class="fas fa-trash-alt pin" accion="delete"></i>';

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

    $('#tbl-coordinadores').DataTable({
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

                    // html += '<button class="btn btn-danger btn-sm pin" accion="delete">'
                    html += '   <i class="fas fa-trash-alt pin" accion="delete"></i>';
                    // html += '</button>';

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

                    // html += '<button class="btn btn-primary btn-sm pin" accion="add">';
                    html += '   <i class="fas fa-plus-circle pin" accion="add""></i>';
                    // html += '</button>';

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

            /* tr.remove();

            var dt_ = $('#tbl-coordinadores').DataTable();

            row.id_contacto = row.id;

            delete row.id;

            row.id = null;

            dt_.row.add(row);
            dt_.draw(); */

            console.log(row)

            var tbl = $(this).parents().eq(3);

            var items = 'id,|id_contacto,' + row.id + '|status,1';

            modal_confirm({
                message: '¿Desea agregar al coordinador?<br><h3>' + row.contacto + '</h3>',
                route: 'coordinador',
                metodo: 'guardar',
                callback: ['getData()','$("#modal-filtro").modal("hide")'],
                param: {
                    accion: accion,
                    items: items
                },
                form: 'form'
            });
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
                callback: ['getData()'],
                param: {
                    accion: accion,
                    items: items
                },
                form: 'form'
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
                route: 'coordinador',
                metodo: 'guardar',
                callback: 'coordinadores',
                param: {
                    accion: 'add',
                    items: seleccionadosParse({get_keys: true})
                },
                form: 'form-registro'
            });
        }
    });

    $('#d-asentamiento .typeahead').typeahead({ minLength: 3}, {
        name: 'asentamientos-lista',
        limit: 10,
        display: 'asentamiento_',
        source: asentamientos
    })
    .on('typeahead:selected', function($e, data) {

        console.log(data);

        $('#id_asentamiento').val(data.id);
        // $('#id_pais').val(data.id_pais);
        // $('#id_estado').val(data.id_estado);
        // $('#id_municipio').val(data.id_municipio);
        // $('#id_asentamiento').val(data.id);

        $(this).attr('seleccionado', 1);
    })
    .on('typeahead:change', function($e, data) {

        if(!$(this).attr('seleccionado')) {

            // $('#id_pais, #id_estado, #id_municipio, #id_asentamiento').val('');
            $('#id_asentamiento').val('');
        }
    })
    .on('keyup', this, function (event) {

        if (event.keyCode == 8) {

            $(this).removeAttr('seleccionado');
            // $('#id_pais, #id_estado, #id_municipio, #id_asentamiento').val('');
            $('#id_asentamiento').val('');
        }
    });

    impresionFormatos();
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
        url: window.location.origin + '/coordinadores',
        cache: false,
        data: param,
        success :  function(data) {

            dataTableSetData([{id: 'tbl-data', data: data}]);

            if(param.open) contactos({data: data});

            spinner({close: true});
        },
        error: function(jqXHR, textStatus, erroThrown) {
            
            spinner({close: true});
        }
    });
}

function coordinadores(param) {

    spinner();

    param = param || {};

    param.dataType = 'json';

    if(!param.seleccionados) param.seleccionados = seleccionadosParse({key: 'id'});

    param = paramMaker({json: param, form: $('#form')});

    $.ajax({
        type: 'POST',
        method: 'post',
        dataType: 'json',
        url: window.location.origin + '/coordinadores',
        cache: false,
        data: param,
        success :  function(data) {

            /* 
            dataTableSetData([
                {id: 'tbl-coordinadores', data: data.coordinadores},
                {id: 'tbl-contactos', data: data.contactos}
            ]); 
            */

            dataTableSetData([
                {id: 'tbl-coordinadores', data: data}
            ]);

            spinner({close: true});
        },
        error: function(jqXHR, textStatus, erroThrown) {
            
            spinner({close: true});
        }
    });
}

function contactos(param) {

    spinner();

    param = param || {};

    param.dataType = 'json';

    param.seleccionados = '';

    for(var i in param.data) {

        if(param.seleccionados) param.seleccionados += ';';
        
        param.seleccionados += param.data[i].id_contacto;
    }

    delete param.data;

    param = paramMaker({json: param, form: $('#form')});

    $.ajax({
        type: 'POST',
        method: 'post',
        dataType: 'json',
        url: window.location.origin + '/contactos',
        cache: false,
        data: param,
        success :  function(data) {

            /* 
            dataTableSetData([
                {id: 'tbl-coordinadores', data: data.coordinadores},
                {id: 'tbl-contactos', data: data.contactos}
            ]); 
            */

            dataTableSetData([
                {id: 'tbl-contactos', data: data}
            ]);

            spinner({close: true});
        },
        error: function(jqXHR, textStatus, erroThrown) {
            
            spinner({close: true});
        }
    });

    $('#modal-filtro').modal('show');
}

function seleccionados() {

    var dt = $('#tbl-coordinadores').DataTable();

    var data = [];

    if(dt.data().count()) {

        dt.rows().every( function ( rowIdx, tableLoop, rowLoop ) {

            var row = this.data();

            console.log(row)

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

function impresionFormatos() {

    if($('#form').attr('acc_impresion')) {

        var tmp = ($('#form').attr('acc_impresion')).split(';');
    
        console.log(tmp);
        
        var botones = [];

        for(var i in tmp) {

            if(tmp[i] == 1) {

                botones.push({
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                })
            }
            else if(tmp[i] == 2) {

                botones.push({
                    extend: 'pdfHtml5',
                    filename: 'someName',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    exportOptions: {
                        // columns: [ 0, 1, 2, 4, 5, 6, 7, 8, 9 ]
                        page: 'all',
                        columns: [':visible' ],
                        // stripNewlines: true,
                        // stripHtml: true,
                    },
                    download: 'open',
                    title: function () {
    
                        // var str = '<h1>Reporte Contactos</h1>';
                        var str = 'Reporte Contactos';
    
                        return str;
                    },
                    messageTop: function () {
    
                        var table = $('#tbl-data').DataTable();
                        var info = table.page.info();
    
                        // var str = titulador();
                        var str = '';
    
                        if(str) str += '\n\n';
    
                        str += 'Registros totales: ' + info.recordsDisplay;
    
                        return str;
                    },
                    messageBottom: function() {
    
                        var table = $('#tbl-data').DataTable();
                        var info = table.page.info();
    
                        return '\nRegistros totales: ' + info.recordsDisplay
                    }
                })
            }

            botones.push('colvis');
            
            new $.fn.dataTable.Buttons( table, {
                buttons: botones
                /* buttons: [
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'someName',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        exportOptions: {
                            // columns: [ 0, 1, 2, 4, 5, 6, 7, 8, 9 ]
                            page: 'all',
                            columns: [':visible' ],
                            // stripNewlines: true,
                            // stripHtml: true,
                        },
                        download: 'open',
                        title: function () {
        
                            // var str = '<h1>Reporte Contactos</h1>';
                            var str = 'Reporte Contactos';
        
                            return str;
                        },
                        messageTop: function () {
        
                            var table = $('#tbl-data').DataTable();
                            var info = table.page.info();
        
                            // var str = titulador();
                            var str = '';
        
                            if(str) str += '\n\n';
        
                            str += 'Registros totales: ' + info.recordsDisplay;
        
                            return str;
                        },
                        messageBottom: function() {
        
                            var table = $('#tbl-data').DataTable();
                            var info = table.page.info();
        
                            return '\nRegistros totales: ' + info.recordsDisplay
                        }
                    },
                    'colvis'
                ], */ 
            } );
        
            table.buttons( 0, null ).container().prependTo(
                table.table().container()
            );
        }

    }
}