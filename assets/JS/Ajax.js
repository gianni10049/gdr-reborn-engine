function Ajax(path,data,success,type = 'get'){
        $.ajax({
            url: path,
            type: type,
            data: data,
            contentType: false,
            processData: false,
            success: function (data) {

                if(success != false) {
                    success(data);
                }
            }
        });
}