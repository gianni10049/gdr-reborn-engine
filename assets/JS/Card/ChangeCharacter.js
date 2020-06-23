$(function(){

    /* Form for change character */
    Form('.ChangeCharacterForm','/ChangeCharacter',ChangeCharacterSuccess);

    /* Ajax for logout character */
    Ajax('.content-box .change-character-box .character-logout a','click','/LogoutCharacter',{},ChangeCharacterSuccess,'get');

    /* Change character response manager */
    function ChangeCharacterSuccess(data){

        let datas= JSON.parse(data);

        Notify(datas.type,datas.text);

        if(datas.type === 'success'){
            $('.central-box .content-box').load('/ChangeCharacter');
        }
    }

});