window.addEventListener('load', function(){
    const fileInput = $('#file')
    const listBlock = $('[data-role="fileList"]')
    window.fileCollection = []

    // Перехватываем событие выбора файла
    fileInput.on('change', function(event) {
        bindFiles(event.target.files)
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

    // Перетаскивание файлов в форму
    var dropArea = $('.drop-area');

    dropArea.click(() => console.log('click'))

    dropArea.on('dragenter dragover', function (e) {
        console.log('dragenter dragover')
        e.preventDefault();
    });

    dropArea.on('drop', function (e) {
        e.preventDefault()
        bindFiles(e.originalEvent.dataTransfer.files)
    });

    function checkFile(file) {
        const limit = getType(file) === 'image' ? 20 : 50;
        if(file.size > 1024 * 1024 * limit) {
            alert(`Файл ${file.name} слишком большой, максимум для этого типа файлов -  ${limit} мегабайт`)
            return false
        }
        return true;
    }

    function renderListItem(file, i) {
        return `
        <li id="file-item-${i}" class="list-group-item d-flex justify-content-between align-items-center">
            <span class="w-75">
                <span id="file-img-box-${i}" class="d-inline-block w-25 me-2 border border-light-gray text-center">${renderPreview(file, i)}</span>
                <span>${file.name.substring(0,7)}${file.name.length > 15 ? '...' : ''} (${(file.size / 1024 / 1024).toFixed(2)}Mb)</span>
            </span>
            <i class="bi bi-trash" data-file="${i}" data-role="delete-file"></i>
        </li>`
    }

    function renderPreview(file, i) {
        if(getType(file) === 'image') {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onloadend = function() {
                $(`#file-img-box-${i}`).html(`<img src="${reader.result}" class="w-100">`)
            }
            return '<i class="bi bi-camera fs-1 p-2"></i>'
        } else if(getType(file) === 'video') {
            return '<i class="bi bi-camera-reels fs-1 p-2"></i>'
        } else {
            return '<i class="bi bi-file-text fs-1 p-2 ></i>'
        }
    }

    function getType(file) {
        return file.type.split('/')[0]
    }

    function bindFiles(files) {
        Array.prototype.forEach.call(files, function(file) {
            if(checkFile(file)) {
                const id = Math.random().toString(16).slice(2)
                window.fileCollection[id] = file
                listBlock.append(renderListItem(file, id))
            } else {
                return false
            }
        })
    }

})