window.addEventListener('load', function () {

    const block = $('[data-role="gigachat"]')
    const url = block.data('url')
    const input = block.find('#gigachat-prompt')
    const descriptionInput = $('#' + block.data('descriptionFieldId'))
    const submitButton = block.find('#gigachat-submit-button')
    const refreshImageButton = block.find('#gigachat-refresh-image-button')
    const deleteImageButton = block.find('#gigachat-remove-image-button')
    const imageCheckbox = block.find('#gigachat-image-checkox')
    const imageContainer = block.find('#gigachat-image-container')
    const spinner = $('[data-role="spinner"]')

    input.on('change', () => submitButton.text('Генерация'))

    refreshImageButton.on('click', refreshImage)
    deleteImageButton.on('click', deleteImage)


    submitButton.on('click', () => {
        submitButton.prop('disabled', true)
        spinner.removeClass('d-none')
        $.ajax({
            url: `${url}/api/gigachat/generate`,
            method: 'post',
            data: {
                prompt: input.val(),
                imageCheckbox: imageCheckbox.prop('checked') ? 1 : 0
            },
            success: function (response) {
                if (response?.success) {
                    descriptionInput.val(response.description)
                    if (response?.image) {
                        imageContainer.show()
                        imageContainer.find('img').attr('src', response.image)
                        imageContainer.find('input').prop('disabled', false).val(response.image_path)
                    } else {
                        deleteImage()
                    }
                } else {
                    Telegram.WebApp.showAlert(response?.error || 'Something went wrong')
                }
                submitButton.text('Попробовать еще')
            },
            error: () => Telegram.WebApp.showAlert('Непредвиденная ошибка, обратитесь к администратору'),
            complete: function () {
                submitButton.prop('disabled', false)
                spinner.addClass('d-none')
            }
        })
    })

    function refreshImage() {
        refreshImageButton.prop('disabled', true)
        spinner.removeClass('d-none')
        $.ajax({
            url: `${url}/api/gigachat/refresh-image`,
            method: 'post',
            data: {
                prompt: descriptionInput.val(),
            },
            success: function (response) {
                if (response?.image) {
                    imageContainer.show()
                    imageContainer.find('img').attr('src', response.image)
                    imageContainer.find('input').prop('disabled', false).val(response.image_path)
                } else {
                    Telegram.WebApp.showAlert(response?.error || 'Something went wrong')
                }
                submitButton.text('Попробовать еще')
            },
            error: () => Telegram.WebApp.showAlert('Непредвиденная ошибка, обратитесь к администратору'),
            complete: function () {
                refreshImageButton.prop('disabled', false)
                spinner.addClass('d-none')
            }
        })
    }

    function deleteImage() {
        imageContainer.hide()
        imageContainer.find('input').prop('disabled', true)
        imageContainer.find('img').attr('src', '')
    }

})