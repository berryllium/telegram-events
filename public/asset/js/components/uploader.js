window.onload = function(){
    const fileInput = $('#file')
    const listBlock = $('[data-role="fileList"]')
    window.fileCollection = []

    // Перехватываем событие выбора файла
    fileInput.on('change', function(event) {
        Array.prototype.forEach.call(event.target.files, function(file) {
            if(checkFile(file)) {
                window.fileCollection.push(file)
            } else {
                return false
            }
        })
        renderList()
    });

    // Очистка файлов
    $('[data-role="clean-files"]').on('click', function() {
        $(this).siblings('input[name="files"]').val('')
        window.fileCollection = []
        renderList()
    });

    function checkFile(file) {
        if(file.size > 1048576 * 50) {
            alert(`Файл ${file.name} слишком большой, максимум 50 мегабайт`)
            return false
        }
        return true;
    }

    function renderList() {
        listBlock.text('')
        window.fileCollection.forEach(file => listBlock.append(`<li>${file.name}</li>`))
    }

}