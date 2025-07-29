@extends('base')
@section('title', __('webapp.report'))
@section('content')

<form action="{{ route('report.process') }}" class="col-lg-6 col-12 m-auto">
    <div class="mb-3">
        <label for="type" class="form-label">{{ __('webapp.type') }}</label>
        <select name="type" id="type" class="form-select">
            <option value="author">{{ __('webapp.author') }}</option>
            <option value="place">{{ __('webapp.places.place') }}</option>
            <option value="user">{{ __('webapp.user') }}</option>
            <option value="perAuthor">По автору</option>
        </select>
        @error('type')
        <div class="form-text text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <div class="input-group per-author-block mb-2" style="display: none;">
            <select name="author" id="author" class="form-select">
                @foreach($authors as $author)
                <option value="{{ $author->id }}">{{ $author->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="input-group">
            <label class="w-50">{{ __('webapp.reports.from') }}</label>
            <label class="w-50">{{ __('webapp.reports.to') }}</label>
        </div>
        <div class="input-group">
            <label class="d-none" for="from"></label>
            <label class="d-none" for="to"></label>
            <input type="datetime-local" class="form-control @error('from') is-invalid @enderror" name="from" id="from" value="{{ old('from') }}">
            <input type="datetime-local" class="form-control @error('to') is-invalid @enderror" name="to" id="to" value="{{ old('to') }}">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">{{ __('webapp.reports.get') }}</button>
</form>

<script>
    window.addEventListener('load', function() {
        console.log('ready')
        $('#type').on('change', function(e) {
            if(e.target.value === 'perAuthor') {
                $('.per-author-block').show()
            } else {
                console.log(e.target.value)
                $('.per-author-block').hide()
            }
        })
    })
</script>

@endsection