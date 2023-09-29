window.addEventListener('load', () => {
    const webApp =  window.Telegram.WebApp
    const form = $('#webapp-form')
    let ajaxObj = false
    webApp.expand()
    webApp.MainButton.text = "Send";
    webApp.MainButton.show()

    Telegram.WebApp.onEvent("mainButtonClicked", async function(){
        if(ajaxObj) return
        ajaxObj = $.ajax({
            url: form.attr("action"),
            type: 'POST',
            data: new FormData(form[0]),
            success: function (response) {
                if(response.message_id) {
                    webApp.sendData(JSON.stringify(response))
                } else if(response.error) {
                    alert(response.error)
                }
            },
            error: function () {
                alert('Unexpected error')
            },
            complete: function() {
                ajaxObj = false
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    $('[name="place"]').change(function (){
        $('[name="address"]').val($(this).val()).trigger('change')
    })

})