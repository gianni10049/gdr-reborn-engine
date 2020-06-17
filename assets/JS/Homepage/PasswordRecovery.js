$(function(){

    /* Login form send */
    Form('#PasswordRecovery #PasswordRecoveryForm','/PasswordRecovery',PasswordRecoverySuccess);

    /* Hyperlinks href in js */
    Hyperlink('#PasswordRecovery a','body');

    /* Login operation response manager */
    function PasswordRecoverySuccess(datas){

        /* Parse json response and split results*/
        let data = JSON.parse(datas),
            text = data.text,
            type = data.type;

        /* Create notification */
        Notify(type, text);

        /* If type is success */
        if(type === 'success'){

            /* Return to main */
            setTimeout(function(){location.href='/'},5000);
        }
    }

});