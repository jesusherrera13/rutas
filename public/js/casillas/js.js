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

var representantes_tipos = [];

$(document).ready(function() {

    $('#btn-nuevo').click(function() {

        $('#casilla-tab').click();

        var dt1 = $('#tbl-representantes').DataTable();

        dt1.clear().draw();

        formReset($('#form-registro'));

        $('#modal-registro').modal('show');
    });

    $('#modal-registro').on('shown.bs.modal', function (e) {
      
    });

    $('#modal-filtro').on('shown.bs.modal', function (e) {

        var dt = $('#tbl-representantes').DataTable();

        var items = '';

        if(dt.data().count()) {

            dt.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
    
                var row = this.data();
    
                // console.log(row.id_contacto);
                if(items) items += ';';

                items += row.id_contacto;
            });
        }
        
        /* 

        for(var i in data[0].representantes) {

            if(items) items += ';';

            items += data[0].representantes[i].id_contacto;
        }

        */

        contactos({
            seleccionados: items,
            mod_op: 'representantes_seleccionados',
            id_modulo: 'casillas'
        }); 
    });

    $('#modal-registro').on('hidden.bs.modal', function (e) {
        
        getData();
        $('#modal-registro  .modal-title').html('Casilla');
    });

    $('#btn-contactos').click(function() {

        $('#modal-filtro').modal('show');
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

            var dt_ = $('#tbl-representantes').DataTable();

            row.id_contacto = row.id;
            row.id_casilla = $('#id').val();

            delete row.id;

            row.id = null;

            dt_.row.add(row);
            dt_.draw();
        }
        else if(accion == 'delete') {

            var tbl = $(this).parents().eq(3);
            var lista_delete = tbl.attr('lista_delete') || '';

            if(lista_delete) lista_delete += ';';

            lista_delete += row.id;

            var items = 'id,' + row.id + '|id_casilla,' + row.id_casilla + '|id_contacto,' + row.id_contacto + '|status,0';

            modal_confirm({
                message: '¿Desea quitar al representante?<br><h3>' + row.contacto + '</h3>',
                route: 'representante',
                metodo: 'guardar',
                param: {
                    items: items,
                    id: $('#id').val()
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
                route: 'casilla',
                param: {
                    items: seleccionadosParse({get_keys: true}),
                    id: $('#id').val()
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

    var param = paramMaker({form: $('#form')});
    param = paramMaker({json: param, form: $('#form')});

    var f1 = $.ajax({
        method: 'post',
        dataType: 'json',
        url: window.location.origin + '/representantes-tipos',
        cache: false,
        data: param
    });

    $.when(f1).then(function(r1) {

        representantes_tipos = r1;
        
        init();
    });
});


function init() {

    console.log('init')

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
            { data: "casilla" },
            { data: "no_rcs" },
            { data: "no_distrito_federal" },
            { data: "no_distrito_local" },
            { data: "ruta" },
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

    $('#tbl-representantes').DataTable({
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

                    var select = $('<select>', {
                        campo: 'id_representante_tipo'
                    });

                    var option = $('<option>', {
                        value: '',
                    });

                    select.append(option);

                    for(var j in representantes_tipos) {

                        option = $('<option>', {
                            value: representantes_tipos[j].id,
                            text: representantes_tipos[j].descripcion
                        });

                        select.append(option);
                    }

                    if(oData.id_representante_tipo) {

                        select.val(eval('oData.id_representante_tipo'));
                    }

                    $(nTd).html(select);
                }
            },
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
                targets: [-1,-2],
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
                    html += '   <i class="fas fa-plus-circle pin" accion="add"></i>';
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
}

function getData(param) {

    spinner();

    param = param || {};

    param.dataType = 'json';

    param = paramMaker({json: param, form: $('#form')});

    $.ajax({
        type: 'POST',
        method: 'post',
        dataType: 'json',
        url: window.location.origin + '/casillas',
        cache: false,
        data: param,
        success :  function(data) {

            if(param.id) {

                $('#casilla-tab').click();

                Object.keys(data[0]).forEach(function(k) {

                    $('#' + k).val(data[0][k]);
                });

                $('#modal-registro  .modal-title').html('Casilla: ' + data[0]['casilla']);

                dataTableSetData([{id: 'tbl-representantes', data: data[0].representantes}]);

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

function contactos(param) {

    spinner();

    param = param || {};

    param.dataType = 'json';

    if(!param.seleccionados) param.seleccionados = seleccionadosParse({key: 'id'});

    param = paramMaker({json: param, form: $('#form')});

    $.ajax({
        type: 'POST',
        method: 'post',
        dataType: 'json',
        url: window.location.origin + '/contactos',
        cache: false,
        data: param,
        success :  function(data) {

            dataTableSetData([{id: 'tbl-contactos', data: data}]);

            spinner({close: true});
        },
        error: function(jqXHR, textStatus, erroThrown) {
            
            spinner({close: true});
        }
    });
}

function seleccionados() {

    var dt = $('#tbl-representantes').DataTable();

    var data = [];

    $('#tbl-representantes > tbody > tr').each(function(i, o) {
        
        var row = dt.row($(o)).data();
        
        if(row) {

            row.id_representante_tipo = $(o).find('select').val() || '';
    
            data.push(row)
            /* var json = {
                id: row.id || '',
                id_equipo: row.id_equipo,
                id_jugador: row.id_jugador,
                no_orden: row.no_orden,
                id_roster: row.id_roster || '',
                id_titular: row.id_titular || '',
                id_sustitucion: row.id_sustitucion || '',
            } */
        }
    });

    /* if(dt.data().count()) {

        dt.rows().every( function ( rowIdx, tableLoop, rowLoop ) {

            var row = this.data();

            data.push(row)
        });
    } */

    return data;
}

function seleccionadosParse(param) {

    var tmp = seleccionados();

    var data = '';

    console.log(tmp)

    for(var i in tmp) {

        if(data) data += ';';

        if(param.get_keys) {

            data += 'id,' + (tmp[i].id || '') + '|id_casilla,' + tmp[i].id_casilla + '|id_contacto,' + tmp[i].id_contacto;
            data += '|id_representante_tipo,' + (tmp[i].id_representante_tipo || '');
        }
        else data += (tmp[i][param.key] || '0');
    }

    return data;
}