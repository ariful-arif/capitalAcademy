<form action="{{ route('admin.course_bundle.category.store') }}" method="post" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <div class="col-12">
            <div class="mb-3">
                <label for="title" class="form-label ol-form-label">{{ get_phrase('Category Title') }}</label>
                <input type="text" name="title" class="form-control ol-form-control" id="title" placeholder="{{ get_phrase('Enter your category name') }}" aria-label="{{ get_phrase('Enter your unique category name') }}" required />
            </div>

            <div class="mb-3">
                <label for="icon-picker" class="form-label ol-form-label">{{ get_phrase('Pick Your Icon') }}</label>
                <input type="file" name="icon" class="form-control ol-form-control" id="icon" accept="image/*" />
            </div>

            <div class="mb-3">
                <label for="description" class="form-label ol-form-label">{{ get_phrase('Category Description') }} <small class="text-muted">({{ get_phrase('optional') }})</small></label>
                <textarea name="description" rows="4" class="form-control ol-form-control" id="description" placeholder="{{ get_phrase('Enter your description') }}" aria-label="{{ get_phrase('Enter your description') }}"></textarea>
            </div>

            <div class="mb-2">
                <button class="btn ol-btn-primary">{{ get_phrase('Submit') }}</button>
            </div>
        </div>
    </div>
</form>
