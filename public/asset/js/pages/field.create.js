window.addEventListener('load', function(){
    $('#type').change(function(){
        if($(this).val() === 'select' || $(this).val() === 'radio' || $(this).val() === 'tags') {
            $('#dictionary-block').show()
        } else {
            $('#dictionary-block').hide()
        }
    })
})