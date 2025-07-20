<div class="form-group" data-role="gigachat" data-url="{{ config('app.url') }}" data-description-field-id="{{ $descriptionFieldId }}">
    <label for="gigachat-prompt">Генерация описания</label>
    <textarea
        class="form-control"
        id="gigachat-prompt"
        rows="7"
        placeholder="Введите запрос для нейросети"></textarea>
    <button class="btn btn-primary mt-2" type="button">Генерация</button>
</div>

<script src="{{ asset('asset/js/components/gigachat.js?' . rand(1, 1000)) }}"></script>