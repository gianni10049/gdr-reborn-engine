$(function(){

    /* Form for change character */
    Form('.ChangeCharacterForm','/ChangeCharacter',OperationResponse);

    /* Form for favorite character */
    Form('.character-favorite-form','/SetFavoriteCharacter',OperationResponse);

    /* Ajax for logout character */
    $('.content-box .change-character-box .character-extra-option li.Logout a').on('click',function(e) {
        e.preventDefault();

        Ajax('/LogoutCharacter', {}, OperationResponse, 'get');
    });

    $('.content-box .change-character-box .character-extra-option li.Favorite a').on('click',function(e) {
        e.preventDefault();

        Ajax('/LeaveFavorite', {}, OperationResponse, 'get');
    });


    /* Change character response manager */
    function OperationResponse(data){

        let datas= JSON.parse(data);

        Notify(datas.type,datas.text);

        if(datas.type === 'success'){
            $('.central-box .content-box').load('/ChangeCharacter');
        }
    }

});