<div data-role="channels">
    <div class="form-group mb-3">
        <label for="channels" >{{ $label }}</label>
        <select class="form-control" id="channels" name="{{ $name }}[]" multiple>
            @foreach($channels as $channel)
                <option value="{{ $channel->id }}">{{ $channel->name }} ({{ $channel->type }})</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <input type="checkbox" name="all_channels" class="form-check-input" id="all_channels">
        <label for="all_channels" class="form-check-label">{{ __('webapp.add_all_bot_channels') }}</label>
    </div>
</div>

<script src="{{ asset('asset/js/components/channels.js?' . rand(1, 1000)) }}"></script>