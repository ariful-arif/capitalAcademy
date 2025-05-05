@extends('layouts.organization')
@push('title', get_phrase('Create Team'))

@section('content')
    <div class="row mb-5">
        <div class="col-lg-12">
            <div class="ol-card radius-8px">
                <div class="ol-card-body my-3 py-4 px-20px">
                    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
                        <h4 class="title fs-16px">
                            <i class="fi-rr-settings-sliders me-2"></i>
                            {{ get_phrase('Add new Team') }}
                        </h4>
                    </div>
                </div>
            </div>
            <div class="ol-card p-3">
                <div class="ol-card-body">
                    <form action="{{ route('organization.teams.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 pb-2">
                                <div class="eForm-layouts">
                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label"
                                            for="name">{{ get_phrase('Team Name') }}<span
                                                class="text-danger ms-1">*</span></label>
                                        <input type="text" name = "name" class="form-control ol-form-control"
                                            placeholder="{{ get_phrase('Enter team name') }}" required>
                                    </div>

                                    <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label"
                                            for="title">{{ get_phrase('Team member') }}<span
                                                class="text-danger ms-1">*</span></label>
                                        <input type="number" name = "team_members" class="form-control ol-form-control"
                                            placeholder="{{ get_phrase('Enter team members amount') }}" required>
                                    </div>

                                    {{-- <div class="fpb-7 mb-3">
                                        <label class="form-label ol-form-label"
                                            for="multiple_user_id">{{ get_phrase('Select users') }}<span
                                                class="required text-danger">*</span>
                                        </label>
                                        <select class="ol-select2 select2-hidden-accessible" name="member_ids[]"
                                            multiple="multiple" required data-placeholder="Select Users">
                                            <option value=""  disabled>{{ get_phrase('Select Users') }}</option>
                                            @foreach (App\Models\User::where('status', 1)->where('organization_id', auth()->user()->id)->orderBy('name', 'desc')->get() as $course)
                                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}


                                </div>
                            </div>

                            <div class="pt-2">
                                <button type="submit"
                                    class="btn ol-btn-primary float-end">{{ get_phrase('Submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')

@endpush
