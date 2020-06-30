function Ajax(selector,action,path,data,success,type = 'get'){


    $(selector).on(action,function(e){
        e.preventDefault();

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

    });


}