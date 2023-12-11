<div class="form-group mb-3 file-block">
    <div>{{ $name }}</div>
    <label for="file" class="drop-area d-flex justify-content-center align-items-center border border-1 border-secondary p-1">
        <i class="bi bi-cloud-arrow-up" style="font-size:72px;"></i>
    </label>
    <ul class="list-group mb-2 mt-2" data-role="fileList"></ul>
    <input id="file" class="form-control d-none" type="file" multiple>
    <input class="form-control d-none" type="file" name="files[]" multiple>
    <div class="d-flex justify-content-around">
        <label for="file" class="btn btn-primary col-4 me-2">{{__('webapp.add_files')}}</label>
        <div class="btn btn-danger col-4" data-role="clean-files">{{__('webapp.clean')}}</div>
    </div>
</div>
<script src="{{ asset('asset/js/components/uploader.js?' . rand(1, 1000)) }}"></script>