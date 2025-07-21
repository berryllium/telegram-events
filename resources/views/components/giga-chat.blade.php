<div class="form-group" data-role="gigachat" data-url="{{ config('app.url') }}" data-description-field-id="{{ $descriptionFieldId }}">
    <label for="gigachat-prompt">Генерация описания</label>
    <textarea
        class="form-control"
        id="gigachat-prompt"
        rows="7"
        placeholder="Введите запрос для нейросети"></textarea>
    <div id="gigachat-image-container" style="display: none;" class="p-3">
        <input type="hidden" name="gigachat-image" value="">
        <div><img src="" alt="" width="100%"></div>
        <div>
            <button id="gigachat-refresh-image-button" class="btn btn-success mt-3" type="button">Перерисовать</button>
            <button id="gigachat-remove-image-button" class="btn btn-danger mt-3" type="button">Удалить</button>
        </div>
    </div>
    <div class="form-check mt-2">
        <input id="gigachat-image-checkox" type="checkbox" class="form-check-input">
        <label class="form-check-label" for="gigachat-image-checkox">Нарисовать картинку</label>
    </div>
    <button id="gigachat-submit-button" class="btn btn-primary mt-3" type="button">Генерация</button>
</div>

<script src="{{ asset('asset/js/components/gigachat.js?' . rand(1, 1000)) }}"></script>