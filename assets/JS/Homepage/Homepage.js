$(function(){

    /* Login form send */
    Form('#Homepage #LoginForm','/login',LoginSuccess);

    /* Hyperlinks href in js */
    Hyperlink('#Homepage a','body');

    /* Login operation response manager */
    function LoginSuccess(datas){

        /* Parse json response and split results*/
        let data = JSON.parse(datas),
            text = data.text,
            type = data.type;

        if(type === 'success'){
            $('body').load('/');
        }
        else {
            /* Create notification */
            Notify(type, text);
        }
    }


});