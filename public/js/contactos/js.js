var tbl_data;

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

var referentes = new Bloodhound({  
    datumTokenizer: function(referentes) {
      return Bloodhound.tokenizers.whitespace(referentes.value);
    },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        url: window.location.origin + '/referentes/?term=%QUERY&dataType=json',
        wildcard: '%QUERY',
        filter: function(response) {

            return response;
        }
    }
});

var secciones = new Bloodhound({  
    datumTokenizer: function(secciones) {
      return Bloodhound.tokenizers.whitespace(secciones.value);
    },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        url: window.location.origin + '/secciones-data/?term=%QUERY&dataType=json',
        wildcard: '%QUERY',
        filter: function(response) {

            return response;
        }
    }
});

$(document).ready(function() {

    $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
        // var target = $(e.target).attr("href"); // activated tab
        // alert (target);
        $($.fn.dataTable.tables( true ) ).css('width', '100%');
        $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
    } );

    $('#modal-registro').on('hidden.bs.modal', function (e) {
        
        $('#d-asentamiento .typeahead, #d-referente .typeahead').val('');
        $('#id_pais, #id_estado, #id_municipio, #id_asentamiento, #id_referente').val('');

        // getData();
    });

    $('#btn-nuevo').click(function() {

        formReset($('#form-registro'));

        clearTables();

        $('#modal-registro').modal('toggle');

        $('#id_distrito_federal option').prop('checked', 0);
    });

    $('#btn-importar').click(function() {

        dataTableClear([{id: 'tbl-importar'}]);

        formReset($('#form-importar'));

        $('#modal-importar').modal('show');
    });

    $('#btn-verificar, #btn-grabar-importar').click(function() {

        if($('#archivo').val()) {

            var data = new FormData($('#form-importar')[0]);

            var continuar = true;

            data.append('id_usuario', $('#id_usuario').val());

            var importar;

            if($(this).attr('id') == 'btn-grabar-importar') {

                continuar = confirm('\u00BFDesea importar los regisros?');

                importar = 1;
                
                data.append('importar', importar);
            }

            // console.log(data);

            if(continuar) {

                spinner();

                $.ajax({
                    url: window.location.origin + '/contacto/importar',
                    method: 'post',
                    data: data,
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {

                        dataTableSetData([{id: 'tbl-importar',data: data}]);

                        if(importar) getData();

                        spinner({close: true});
                    },
                    complete: function() {
                        
                        spinner({close: true});
                    },
                    error: function(jqXHR, textStatus, erroThrown) {

                        spinner({close: true});
                    }
                });
            }
        }
        else dialog_alert({id: 'dialog-alert',body: 'Seleccione el archivo'});
    });

    $('#no_municipio_').change(function() {

        if($(this).val()) {

            $('#id_asentamiento_ *').hide();

            $('#id_asentamiento_ [id_municipio="' + $(this).val() +'"]').show();
        }
        else $('#id_asentamiento_ *').show();
    });

    tbl_data = $('#tbl-data').DataTable({
        // scrollX:        true,

        scrollY:        "300px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        fixedColumns:   {
            leftColumns: 1,
            rightColumns: 1
        },
        colReorder: true,
        /*"processing": true,
        "serverSide": true,
        "ajax": {
            url: "/contactos-ssp",
            "data": function ( d ) {

                console.log(d)

                var json = paramMaker({json: d, form: $('#form')});

                console.log(d)

                // d.id_distrito_federal = $('#id_distrito_federal_').val();
                // d.id_distrito_local = $('#id_distrito_local_').val();
            }
        },
        "type": "POST",
        searchDelay: 500,*/
        columns: [
            { data: "contacto" },
            { data: "casilla" },
            { data: "no_seccion" },
            { data: "no_telefono" },
            { data: "clave_elector" },
            { data: "no_distrito_federal" },
            { data: "no_distrito_local" },
            { data: "asentamiento" },
            { data: "direccion" },
            { data: "referente_corto" },
            { data: "coordinador_corto" },
            { data: "action" },
        ],
        columnDefs: [
            {
                type: 'chinese-string', 
                targets: 0 
            },
        ],
        lengthMenu: [ [10, 25, 50, -1], ['10 Filas', '25 Filas', '50 Filas', 'Mostrar todo'] ],
        /* dom: 'Bfrtipl',
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [ 0, ':visible' ]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions: {
                    // columns: [ 0, 1, 2, 4, 5, 6, 7, 8, 9 ]
                    columns: [':visible' ]
                }
            },
            'colvis'
        ], */
        createdRow: function(row, data, dataIndex) {
            
        }
    });

    impresionFormatos();

    $('#tbl-importar').DataTable({
        responsive: true,
        ordering: false,
        // searching: false,
        // paging: false,
        // info: false,
        columns: [
            { data: 'id' },
            { data: 'no_seccion' },
            { data: 'nombre' },
            { data: 'apellido1' },
            { data: 'apellido2' },
            { data: 'telefono' },
            { data: 'referente' },
        ],
        columnDefs: [
            {
                type: 'chinese-string', 
                targets: 1 
            },
            {
                // targets: -1,
                // className: 'text-right'
            }
        ],
        createdRow: function(row, data, dataIndex) {
           
        }
    });
    
    /*
    $('#tbl-data').DataTable({
        // scrollX:        true,

        scrollY:        "300px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        fixedColumns:   {
            leftColumns: 1,
            rightColumns: 1
        },
        colReorder: true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "/contactos-ssp",
            "data": function ( d ) {

                console.log(d)

                var json = paramMaker({json: d, form: $('#form')});

                console.log(d)

                // d.id_distrito_federal = $('#id_distrito_federal_').val();
                // d.id_distrito_local = $('#id_distrito_local_').val();
            }
        },
        "type": "POST",
        searchDelay: 500,
        columns: [
            { data: "contacto" },
            { data: "casilla" },
            { data: "no_seccion" },
            { data: "no_telefono" },
            // { data: "email" },
            { data: "no_distrito_federal" },
            { data: "no_distrito_local" },
            { data: "asentamiento" },
            { data: "direccion" },
            { data: "referente_corto" },
            { data: "coordinador_corto" },
            { data: "action" },
        ],
        columnDefs: [
            
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [ 0, ':visible' ]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions: {
                    // columns: [ 0, 1, 2, 4, 5, 6, 7, 8, 9 ]
                    columns: [':visible' ]
                }
            },
            'colvis'
        ],
        createdRow: function(row, data, dataIndex) {

        }
    });
    */

    $('#tbl-telefonos').DataTable({
        searching: false,
        paging: false,
        info: false,
        responsive: true,
        columns: [
            { data: "no_telefono" },
            { data: null,        
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {

                    var html = '';

                    var key = oData.id || '';

                    // html += '<button class="btn btn-danger btn-sm">';
                    // html += '   <i class="fas fa-trash-alt"></i>';
                    // html += '</button>';

                    html += '<a class="btn btn-danger btn-sm btn-delete" href="javascript:void(0)" role="button" key="' + key + '" clase="telefonos">'
                    html += '   <i class="fas fa-trash-alt"></i></a>';
                    html += '</a>';

                    $(nTd).html(html);
                }
            },
        ],
        columnDefs: [
            {
                width: "90%",
                targets: 0
            },
            {
                width: "10%",
                orderable: false,
                targets: -1,
            }
        ],
        createdRow: function(row, data, dataIndex) {

        }
    });

    $('#tbl-emails').DataTable({
        searching: false,
        paging: false,
        info: false,
        responsive: true,
        columns: [
            { data: "email" },
            { data: null,        
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {

                    var html = '';

                    var key = oData.id || '';

                    html += '<button class="btn btn-danger btn-sm btn-delete" key="' + key + '" clase="telefonos">'
                    html += '   <i class="fas fa-trash-alt"></i>';
                    html += '</button>';

                    $(nTd).html(html);
                }
            },
        ],
        columnDefs: [
            {
                width: "90%",
                targets: 0
            },
            {
                width: "10%",
                orderable: false,
                targets: -1,
            }
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

        /*$('#tbl-data > tbody > tr').each(function(i, o) {

            var ron = dt.rows($(o)).data();

            if(ron[0].id == row.id) row.DT_RowIndexx = parseInt(i) + 1;
        });*/

        // console.log(row);

        // $('#tbl-data_wrapper .DTFC_LeftBodyWrapper .datatable > tbody > :nth-child(' + row.DT_RowIndex + ') > :nth-child(1)').text('xxxxx');


        /*var index = $(this).parents().eq(1).index();

        var temp = tbl_data.row(index).data();

        // console.log(temp);
        
        temp.nombre = 'Tom';
        temp.contacto = temp.nombre;

        if(temp.apellido1) temp.contacto += ' ' + temp.apellido1;
        if(temp.apellido2) temp.contacto += ' ' + temp.apellido2;
        
        $('#tbl-data').dataTable().fnUpdate(temp,index,undefined,false);*/

        // tbl_data.fnUpdate(temp, index, undefined, false);

        getData_({ id: $(this).attr('iddb') });
    })
    .on('click', '.btn-delete', function() {

        var tbl = $(this).parents().eq(3);
        var dt = $(this).parents().eq(3).DataTable();
        var tr = $(this).parents().eq(1);
        var json = dt.row( tr ).data();

        var lista_delete = tbl.attr('lista_delete') || '';

        if(lista_delete) lista_delete += ';';

        lista_delete += json.id;

        tbl.attr('lista_delete', lista_delete);

        dt.row(tr).remove().draw();
    });

    $('#btn-buscar').click(function() {

        getData_();
    });

    $('.btn-agregar').click(function() {

        var clase = $(this).attr('clase');

        var field;
        var value;

        if(clase == 'telefonos') field = 'no_telefono';
        else if(clase == 'emails') field = 'email';
        
        value = $('#' + field).val();

        if(value) {

            var dt = $('#tbl-' + clase).DataTable();

            var json = {
                id: ''
            };

            eval('json.' + field + '=value');

            dt.row.add(json).draw();

            $('#' + field).val('').focus();
        }
    });

    $('#btn-guardar').click(function() {

        var msj = '';

        if(!$('#nombre').val()) msj += 'Escriba el nombre<br>';
        if(!$('#apellido1').val()) msj += 'Escriba el primer apellido<br>';
        if(!$('#id_seccion').val()) msj += 'Seleccione la sección';
        // if(!$('#id_casilla').val()) msj += 'Seleccione la casilla';

        if(msj) dialog_alert({id: 'dialog-alert', body: msj});
        else {

            var param = {};

            var telefonos = '';
            var emails = '';

            var dt_t = $('#tbl-telefonos').DataTable();
            var dt_e = $('#tbl-emails').DataTable();

            dt_t.rows().every( function ( rowIdx, tableLoop, rowLoop ) {

                var row = this.data();

                var values = '';

                Object.keys(row).forEach(function(k) {

                    if(values) values += '|';

                    values += k + ',' + row[k];
                });

                if(telefonos) telefonos += ';';

                telefonos += values;
            });

            dt_e.rows().every( function ( rowIdx, tableLoop, rowLoop ) {

                var row = this.data();

                var values = '';

                Object.keys(row).forEach(function(k) {

                    if(values) values += '|';

                    values += k + ',' + row[k];
                });

                if(emails) emails += ';';

                emails += values;
            });

            eval('param.telefonos=telefonos');
            eval('param.emails=emails');

            param.id = $('#id').val();

            /*
            modal_confirm({
                message: '¿Desea grabar ' + ($('#id').val() ? 'los cambios' : 'el registro') + '?',
                route: 'contacto',
                form: 'form-registro',
                param: param
            });
            */

            modal_confirm({
                message: '¿Desea grabar ' + ($('#id').val() ? ' los cambios' : ' el registro') + '?',
                route: 'contacto',
                route_data: 'contactos',
                id_table: 'tbl-data',
                param: param,
                form: 'form-registro',
                id: $('#id').val(),
                row_id: $('#row_id').val(),
                DT_RowIndex: $('#DT_RowIndex').val(),
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

        $('#id_pais').val(data.id_pais);
        $('#id_estado').val(data.id_estado);
        $('#id_municipio').val(data.id_municipio);
        $('#id_asentamiento').val(data.id);

        $(this).attr('seleccionado', 1);
    })
    .on('typeahead:change', function($e, data) {

        if(!$(this).attr('seleccionado')) {

            $('#id_pais, #id_estado, #id_municipio, #id_asentamiento, #asentamiento_').val('');
        }
    })
    .on('keyup', this, function (event) {

        if (event.keyCode == 8) {

            $(this).removeAttr('seleccionado');
            $('#id_pais, #id_estado, #id_municipio, #id_asentamiento').val('');
        }
    });

    $('#d-referente .typeahead').typeahead({ minLength: 3}, {
        name: 'referentes-lista',
        limit: 10,
        display: 'contacto_corto',
        source: referentes
    })
    .on('typeahead:selected', function($e, data) {

        $('#id_referente').val(data.id);
        // $('#id_estado').val(data.id_estado);
        // $('#id_municipio').val(data.id_municipio);
        // $('#id_asentamiento').val(data.id);

        $(this).attr('seleccionado', 1);
    })
    .on('typeahead:change', function($e, data) {

        if(!$(this).attr('seleccionado')) {

            $('#id_referente').val('');
        }
    })
    .on('keyup', this, function (event) {

        if (event.keyCode == 8) {

            $(this).removeAttr('seleccionado');
            $('#id_referente').val('');
        }
    });

    $('#d-no_seccion .typeahead').typeahead({ minLength: 2}, {
        name: 'secciones-lista',
        limit: 10,
        display: 'no_seccion',
        source: secciones
    })
    .on('typeahead:selected', function($e, data) {

        $('#id_seccion').val(data.id);
        
        $(this).attr('seleccionado', 1);

        casillas({id_seccion: data.id});
    })
    .on('typeahead:change', function($e, data) {

        if(!$(this).attr('seleccionado')) {

            $('#id_seccion').val('');
        }
    })
    .on('blur', function($e, data) {

        if(!$(this).attr('seleccionado')) {

            $(this).val('');
            $('#id_seccion, #casilla').val('');
            $('#id_casilla').html('<option></option>')
        }
    })
    .on('keyup', this, function (event) {

        if (event.keyCode == 8) {

            $(this).removeAttr('seleccionado');
            $('#id_seccion').val('');
        }
    });
});



function getData_(param) {

    spinner();

    param = param || {};

    param.dataType = 'json';

    param = paramMaker({json: param, form: $('#form')});

    $.ajax({
        type: 'POST',
        method: 'post',
        dataType: 'json',
        url: window.location.origin + '/contactos',
        cache: false,
        data: param,
        success :  function(data) {

            if(param.id) {

                Object.keys(data[0]).forEach(function(k) {

                    $('#' + k).val(data[0][k]);
                });

                casillas({
                    id_seccion: data[0].id_seccion,
                    id_casilla: data[0].id_casilla
                });

                if(data[0].telefonos) {

                    dataTableSetData([{
                        id: 'tbl-telefonos', data: data[0].telefonos,
                    }]);
                }

                if(data[0].emails) {

                    dataTableSetData([{
                        id: 'tbl-emails', data: data[0].emails
                    }]);
                }
                $('#d-no_seccion .typeahead').typeahead('val', data[0].no_seccion);
                // $('#d-no_seccion .typeahead').eq(0).val(data[0].no_seccion).trigger("input");
                $('#d-no_seccion .typeahead').attr('seleccionado', 1);

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


function getData(param) {

    if(param) {

        spinner();

        clearTables();

        param = param || {};

        param.dataType = 'json';

        param = paramMaker({json: param, form: $('#form')});

        $.ajax({
            type: 'POST',
            method: 'post',
            dataType: 'json',
            url: window.location.origin + '/contactos',
            cache: false,
            data: param,
            success :  function(data) {

                if(param.id) {

                    Object.keys(data[0]).forEach(function(k) {

                        $('#' + k).val(data[0][k]);
                    });

                    casillas({
                        id_seccion: data[0].id_seccion,
                        id_casilla: data[0].id_casilla
                    });

                    if(data[0].telefonos) {

                        dataTableSetData([{
                            id: 'tbl-telefonos', data: data[0].telefonos,
                        }]);
                    }

                    if(data[0].emails) {

                        dataTableSetData([{
                            id: 'tbl-emails', data: data[0].emails
                        }]);
                    }
                    $('#d-no_seccion .typeahead').typeahead('val', data[0].no_seccion);
                    // $('#d-no_seccion .typeahead').eq(0).val(data[0].no_seccion).trigger("input");
                    $('#d-no_seccion .typeahead').attr('seleccionado', 1);

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
    else {

        tbl_data.destroy();

        tbl_data = $('#tbl-data').DataTable({
            // scrollX:        true,

            scrollY:        "300px",
            scrollX:        true,
            scrollCollapse: true,
            paging:         false,
            fixedColumns:   {
                leftColumns: 1,
                rightColumns: 1
            },
            colReorder: true,
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "/contactos-ssp",
                "data": function ( d ) {

                    var json = paramMaker({json: d, form: $('#form')});

                    // d.id_distrito_federal = $('#id_distrito_federal_').val();
                    // d.id_distrito_local = $('#id_distrito_local_').val();
                }
            },
            "type": "POST",
            searchDelay: 500,
            columns: [
                { data: "contacto" },
                { data: "casilla" },
                { data: "no_seccion" },
                { data: "no_telefono" },
                { data: "clave_elector" },
                { data: "no_distrito_federal" },
                { data: "no_distrito_local" },
                { data: "asentamiento" },
                { data: "direccion" },
                { data: "referente_corto" },
                { data: "coordinador_corto" },
                { data: "action" },
            ],
            columnDefs: [
                {
                    type: 'chinese-string', 
                    targets: 0 ,
                    width: "20%" 
                },
            ],
            lengthMenu: [ [10, 25, 50, -1], ['10 Filas', '25 Filas', '50 Filas', 'Mostrar todo'] ], 
            // dom: 'Bfrtipl',
            /* buttons: [
                {
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [ 0, ':visible' ]
                    }
                },
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

                        var str = titulador();

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
            ],  */
            createdRow: function(row, data, dataIndex) {

            },
            "initComplete": function( settings, json ) {
                /* 
                tbl_data.buttons('.buttons-excel').nodes().addClass('hidden');
                
                console.log(999)

                tbl_data.buttons( '.export' ).remove();

                $('.dt-buttons').remove(); */

                /*new $.fn.dataTable.Buttons(tbl_data, {
                    buttons: [
                        'copy', 'excel', 'pdf'
                    ]
                });
                */
            },
            "drawCallback": function( settings ) {

                setTimeout(function() {

                    
                    var w = parseInt($('#tbl-data_wrapper .DTFC_LeftBodyLiner').css('width'));

                    w += 10;

                    console.log(w);

                    $('#tbl-data_wrapper .DTFC_LeftBodyLiner').css('width', w + 'px');
                }, 1000);
            }
        })
        .on('init', function () {
            
            

            // tbl_data.buttons('.dt-buttons').hide();
            /* if ( tbl_data.rows().count() > 5000 ) {
            } else {
                tbl_data.buttons('.csvButton').nodes().removeClass('hidden'); */
        });

        impresionFormatos();
    }



    /*
    
    */
}

function clearTables() {

    var dt_t = $('#tbl-telefonos').DataTable();
    var dt_e = $('#tbl-emails').DataTable();

    dt_t.clear().draw();
    dt_e.clear().draw();
}

function casillas(param) {

    console.log(param)

    param = param || {};

    if(param.id_seccion) {
        
        spinner();

        var param = paramMaker({json: param, form: $('#form')});

        $('#id_casilla').html('');

        var option = $('<option>', {
            value: '',
            text: '',
        });

        $('#id_casilla').append(option);

        $.ajax({
            url: window.location.origin + '/casillas',
            dataType: 'json',
            method: 'post',
            data: param,
            success: function(data) {

                for(var i in data) {

                    option = $('<option>', {
                        value: data[i].id,
                        text: data[i].casilla,
                    });

                    $('#id_casilla').append(option);
                }

                if(param.id_casilla) $('#id_casilla').val(param.id_casilla);

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
    else $('#id_casilla').html('');
}

function titulador() {

    var str = '';

    if($('#id_distrito_federal_').val()) {

        str = 'Distrito Federal: ' + $('#id_distrito_federal_ :selected').text().trim();
    }

    if($('#id_distrito_local_').val()) {

        if(str) str += '\n';

        str += 'Distrito Local: ' + $('#id_distrito_local_ :selected').text().trim();
    }

    if($('#id_coordinador_').val()) {

        if(str) str += '\n';

        str += 'Coordinador: ' + $('#id_coordinador_ :selected').text().trim();
    }

    if($('#id_referente_').val()) {

        if(str) str += '\n';

        str += 'Referente: ' + $('#id_referente_ :selected').text().trim();
    }

    return str;
}

function impresionFormatos() {

    if($('#form').attr('acc_impresion')) {

        var tmp = ($('#form').attr('acc_impresion')).split(';');
    
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

                botones.push(
                    {
                        extend: 'pdfHtml5',
                        filename: 'someName',
                        orientation: 'landscape', //portrait, landscape
                        pageSize: 'A4',
                        customize : function(doc) {
                            doc.pageMargins = [100, 10, 10,10 ]; 
                            doc.styles.tableHeader.fontSize = 8;
                            doc.defaultStyle.fontSize = 8;
                        },
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
    
                            var str = titulador();
    
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
                )
            }
        }

        botones.push('colvis');

        new $.fn.dataTable.Buttons( tbl_data, {
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
    
        tbl_data.buttons( 0, null ).container().prependTo(
            tbl_data.table().container()
        );
    }
}