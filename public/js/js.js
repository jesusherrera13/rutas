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

    $('#btn-nuevo, #btn-buscar').css('visibility', 'visible');
});

function paramMaker(param) {

    param.json = param.json || {};

    var tmp = $(param.form).serializeArray();

    for (var i in tmp) {

        if(tmp[i].value) {

            var name = tmp[i].name;

            if(name.substring(name.length - 1, name.length) == '_') {

                name = name.substring(0, name.length - 1);
            }

            param.json[name] = tmp[i].value || '';
        }
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

            dialog_alert({id: 'dialog-alert', body: data.message});

            var json = {};

            if(param) {

                if(param.param.id) json = { id: param.param.id };
            
                if(param.callback) {

                    eval(param.callback + '()')
                }
            }

            getData(json);
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