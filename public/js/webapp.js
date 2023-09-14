window.addEventListener('load', () => {
    const webApp =  window.Telegram.WebApp
    const form = $('#webapp-form')
    webApp.expand()
    webApp.MainButton.text = "Отправить";
    webApp.MainButton.show()

    Telegram.WebApp.onEvent("mainButtonClicked", async function(){
        $.ajax({
            url: form.attr("action"),
            type: 'POST',
            data: new FormData(form[0]),
            success: function (response) {
                webApp.sendData(response.data)
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
})