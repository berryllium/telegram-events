<div data-role="places-lists">
    <div class="form-group mb-3">
        <label for="places-lists" >{{ __('webapp.places-lists') }}</label>
        <select class="form-control" id="places-lists" name="places-lists[]" multiple>
            @foreach($places-lists as $places-list)
                <option value="{{ $places-list->id }}">{{ $places-list->name }} ({{ $places-list->type }})</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <input type="checkbox" name="all_places-lists" class="form-check-input" id="all_places-lists">
        <label for="all_places-lists" class="form-check-label">{{ __('webapp.add_all_bot_places-lists') }}</label>
    </div>
</div>

<script src="{{ asset('asset/js/components/bot-places-list.js?' . rand(1, 1000)) }}"></script>