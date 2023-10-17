window.addEventListener('load', function(){
    $('#type').change(function(){
        if($(this).val() === 'select' || $(this).val() === 'radio') {
            $('#dictionary-block').show()
        } else {
            $('#dictionary-block').hide()
        }
    })
})