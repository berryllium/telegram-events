<form class="row mb-4 d-flex justify-content-end">
    @yield('filters')
    <div class="col-lg-2 col-6 my-1 text-end text-lg-start">
        <button type="submit" class="btn btn-primary">{{ __('webapp.search') }}</button>
    </div>
</form>