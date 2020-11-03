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

    $('body')
    .on('click', '.btn-editar', function() {

        getData({ id: $(this).attr('iddb') });
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

        getData();
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

            modal_confirm({
                message: '¿Desea grabar ' + ($('#id').val() ? 'los cambios' : 'el registro') + '?',
                route: 'contacto',
                form: 'form-registro',
                param: param
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