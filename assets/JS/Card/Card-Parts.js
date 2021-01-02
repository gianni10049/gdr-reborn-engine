$(function (){

    $('#card-container .internal-container .parts-card .single-part-internal .part-name').on('click',function(){


        let character = $(this).closest('.parts-card').data('character'),
            part = $(this).closest('.single-part').data('part');

        Load(
            '#card-container .internal-container .content-container',
            '/Card-Part-Data',
            {'character':character,'part':part}
        );
    });


});