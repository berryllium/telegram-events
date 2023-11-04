window.onload = function(){
    const fileInput = $('#file')
    const listBlock = $('[data-role="fileList"]')
    window.fileCollection = []

    // Перехватываем событие выбора файла
    fileInput.on('change', function(event) {
        Array.prototype.forEach.call(event.target.files, function(file) {
            if(checkFile(file)) {
                const id = Math.random().toString(16).slice(2)
                window.fileCollection[id] = file
                listBlock.append(renderListItem(file, id))
            } else {
                return false
            }
        })
    });

    // Очистка файлов
    $('[data-role="clean-files"]').on('click', function() {
        $(this).siblings('input[name="files"]').val('')
        window.fileCollection = []
        listBlock.html('')
    });

    // Удаление файла
    listBlock.on('click', '[data-role="delete-file"]', function(){
        const id = $(this).data('file')
        delete window.fileCollection[id]
        $(`#file-item-${id}`).remove()
        console.log(window.fileCollection)
    })

    function checkFile(file) {
        if(file.size > 1024 * 1024 * 50) {
            alert(`Файл ${file.name} слишком большой, максимум 50 мегабайт`)
            return false
        }
        return true;
    }

    function renderListItem(file, i) {
        return `
        <li id="file-item-${i}" class="list-group-item d-flex justify-content-between align-items-center">
            <span class="w-75">
                <span id="file-img-box-${i}" class="d-inline-block w-25 me-2 border border-light-gray text-center">${renderPreview(file, i)}</span>
                <span>${file.name.substring(0,15)}${file.name.length > 15 ? '...' : ''}</span>
            </span>
            <i class="bi bi-trash" data-file="${i}" data-role="delete-file"></i>
        </li>`
    }

    function renderPreview(file, i) {
        const type = file.type.split('/')[0]
        if(type === 'image') {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onloadend = function() {
                $(`#file-img-box-${i}`).html(`<img src="${reader.result}" class="w-100">`)
            }
            return '<i class="bi bi-camera fs-1 p-2"></i>'
        } else if(type === 'video') {
            return '<i class="bi bi-camera-reels fs-1 p-2"></i>'
        } else {
            return '<i class="bi bi-file-text fs-1 p-2 ></i>'
        }
    }

}