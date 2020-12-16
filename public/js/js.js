var cuervo;

$(document).ready(function() {
    
    $('button').click(function(e) {
        
        e.preventDefault()
    });

    $('#btn-guardar-confirm').click(function() {
        
        $('#modal-confirm').modal('toggle');

        guardar(cuervo);
    });

    $('body')
    .on('click', '[data-widget="pushmenu"]', function() {
        
        var json = {
            sidebar_collapse: $('body').hasClass('sidebar-collapse') ? '' : 'sidebar-collapse'
        };

        var param = paramMaker({json: json, form: $('#form-search')});
        
        $.ajax({
            url: window.location.origin + '/sidebar',
            dataType: 'json',
            method: 'post',
            data: param,
            success: function(data) {

            },
            error: function(jqXHR, textStatus, erroThrown) {

            }
        });
    });

    $('.btn-formulario').css('visibility', 'visible');
});

function paramMaker(param) {

    param.json = param.json || {};

    var tmp = $(param.form).serializeArray();

    for (var i in tmp) {

        var name = tmp[i].name;

        if(name.substring(name.length - 1, name.length) == '_') {

            name = name.substring(0, name.length - 1);
        }

        param.json[name] = tmp[i].value || '';
    }

    return param.json;
}

function dataTableSetData(param) {

    for(var i in param) {

        var dt = $('#' + param[i].id).DataTable();

        dt.clear();
        dt.rows.add(param[i].data);
        dt.draw();
    }
}

function formReset(obj) {

    var _token = $(obj).find('input[name=_token]').val();

    $(obj).find('input[type=text], input[type=hidden], textarea, input[type=date], input[type=password], input[type=number]').val('');
    $(obj).find('input[type="file"]').val('');
    $(obj).find('input[type=checkbox]').prop('checked', false);
    // $(obj).find('select option:first-child').prop('selected', true);
    $(obj).find('select option').prop('selected', false);

    $(obj).find('input[name=_token]').val(_token);
}

function spinner(param) {

    param = param || {};

    if((($('#modal-spinner').data('bs.modal') || {})._isShown) || param.close) {
        
        setTimeout(function () {

            $('#modal-spinner').modal('hide');
        }, 1000);
    }
    else $('#modal-spinner').modal('show');
}

function dialog_alert(param) {

    var title = param.title || 'Sistema';
    var body = param.body || 'Body...';

    $('#' + param.id + ' .modal-title').html(title);
    $('#' + param.id + ' .modal-body').html(body);
    
    $('#' + param.id).modal('toggle');
}

function modal_confirm(param) {

    param = param || {};

    cuervo = param;

    var title = param.title || 'Sistema';
    var message = param.message || 'Body...';

    $('#modal-confirm .modal-title').html(title);
    $('#modal-confirm .modal-body').html(message);

    $('#modal-confirm').modal('toggle');

    console.log(cuervo)
}

function guardar(param) {

    cuervo = {};

    param = param || {};

    if(!param.route) return false; 

    var metodo;

    if(param.metodo) metodo = param.metodo;
    else metodo = $('#id').val() ? 'update' : 'store';

    var param_ = paramMaker({form: $('#' + param.form)});

    if(param.param) {

        Object.keys(param.param).forEach(function(k) {

            eval('param_[k] = param.param[k]');
        });
    }

    $.ajax({
        url: window.location.origin + '/' + param.route + '/' + metodo,
        dataType: 'json',
        method: 'post',
        data: param_,
        success: function(data) {

            if(param.modals) {

                for(var i in param.modals) {

                    $('#' + param.modals[i].id).modal('toggle');
                }
            }

            /*dialog_alert({id: 'dialog-alert', body: data.message});

            var json = {};

            if(param) {

                if(param.param.id) {

                    json = { id: param.param.id };
                    
                    getData(json);
                }
            
                if(param.callback) {

                    eval(param.callback + '()')
                }
                
            }*/

            if(!param.id) {

                $('#id').val(data.id);

                param.id = data.id;
                param.nuevo = true;
            }

            rowUpdate(param);

            // console.log(param)
        
            if(param.callback) {

                if(param.callback.length) {

                    for(var i in param.callback) {

                        eval(param.callback[i]);
                    }
                }
            }

        },
        error: function(jqXHR, textStatus, erroThrown) {

            if(jqXHR.responseJSON) {

                var title = jqXHR.responseJSON.message;
                var error = '';

                for(var r in jqXHR.responseJSON.errors) {

                    if(error) error += '<br>';

                    error += jqXHR.responseJSON.errors[r];
                }

                dialog_alert({id: 'dialog-alert',title: title, body: error});
            }
        }
    });
}

function rowUpdate(param) {

    spinner();

    param = param || {};
    param.dataType = 'json';
    param = paramMaker({json: param, form: $('#form')});

    $.ajax({
        type: 'POST',
        method: 'post',
        dataType: 'json',
        url: param.route_data,
        cache: false,
        data: param,
        success :  function(data) {

            var dt = $('#' + param.id_table).DataTable();

            if(param.nuevo) {

                var tmp = dt.rows().data();

                dt.clear();

                tmp.push(data[0]);

                dt.rows.add(tmp);
                dt.draw();

                var table = $('#' + param.id_table).DataTable();
 
                dt.rows().every( function ( rowIdx, tableLoop, rowLoop ) {

                    var d = this.data();
                    
                    if(d.id == param.id) $('#row_id').val(rowIdx);
                });

                /*
                $('#' + param.id_table + ' > tbody > tr').each(function(i, o) {

                    var row = dt.rows($(o)).data();

                    console.log(row[0]);
                    console.log(param.id);
    
                    if(row[0].id == param.id)$('#row_id').val(i);
                });
                */
            }
            else {
                
                data[0].DT_RowIndex = param.DT_RowIndex;

                $('#' + param.id_table).dataTable().fnUpdate(data[0],param.row_id,undefined,false);

                if(param.DT_RowIndex) {

                    $('#' + param.id_table + '_wrapper .DTFC_LeftBodyWrapper .datatable > tbody > :nth-child(' + param.DT_RowIndex + ') > :nth-child(1)').text(data[0].contacto);
                
                    setTimeout(function() {

                    
                        var w = parseInt($('#tbl-data_wrapper .DTFC_LeftBodyLiner').css('width'));
    
                        w += 10;
    
                        console.log(w);
    
                        $('#' + param.id_table + '_wrapper .DTFC_LeftBodyLiner').css('width', w + 'px');
                    }, 1000);
                }
            }

            spinner({close: true});
        },
        error: function(jqXHR, textStatus, erroThrown) {
            
            spinner({close: true});
        }
    });
}

function dataTableClear(param) {

    for(var i in param) {

        var dt = $('#' + param[i].id).DataTable();

        dt.clear();
        dt.draw();
    }
}

// Script para datatable con acentos

jQuery.extend( jQuery.fn.dataTableExt.oSort, {
    "chinese-string-asc" : function (s1, s2) {
        return s1.localeCompare(s2);
    },
 
    "chinese-string-desc" : function (s1, s2) {
        return s2.localeCompare(s1);
    }
} );