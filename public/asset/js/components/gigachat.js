window.addEventListener('load', function () {

    const block = $('[data-role="gigachat"]')
    const url = block.data('url')
    const input = block.find('#gigachat-prompt')
    const descriptionInput = $('#' + block.data('descriptionFieldId'))
    const button = block.find('button')

    input.on('change', () => button.text('Генерация'))

    button.on('click', () => {
        button.prop('disabled', true)
        $.ajax({
            url: `${url}/api/gigachat/generate`,
            method: 'post',
            data: {
                prompt: input.val()
            },
            success: function (response) {
                if (response?.success) {
                    descriptionInput.val(response.description)
                } else {
                    Telegram.WebApp.showAlert(response?.error || 'Something went wrong')
                }
                button.text('Попробовать еще')
            },
            error: () => Telegram.WebApp.showAlert('Непредвиденная ошибка, обратитесь к администратору'),
            complete: function () {
                button.prop('disabled', false)
            }
        })
    })


})