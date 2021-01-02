function Ajax(path,data,success,type = 'get'){
        $.ajax({
            url: path,
            type: type,
            data: data,
            contentType: false,
            processData: false,
            success: function (response) {

                if(success != false) {
                    success(response);
                }
            }
        });
}


function Load(selector, path, data, JS = false, callback = false) {
    $(selector).load(path, data, function () {
        if (JS != false) {
            $.getScript(JS);
        }

        if (callback != false) {
            callback();
        }
    });
}
