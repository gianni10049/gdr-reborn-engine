
function Hyperlink(selector,container){

    /* On selector click */
    $(selector).on('click',function(e){

        /* Prevent default href */
        e.preventDefault();

        /* Get link of the href */
        let value= $(this).attr('href');

        /* Load new page on the container */
        $(container).load(value);
    })
}