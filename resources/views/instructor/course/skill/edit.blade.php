@php
    $skill = App\Models\Course_skill::find($id);
@endphp

@if ($skill)
    <form class="ajaxForm" action="{{ route('instructor.course_skill.update', ['id' => $id]) }}" method="post">
        @csrf

        <div class="mb-3">
            <label class="form-label ol-form-label" for="name">{{ get_phrase('Name of skill') }}</label>
            <input class="form-control ol-form-control" type="text" value="{{$skill->name}}" id="name" name="name" required>
        </div>

        <div class="mb-3">
            <label class="form-label ol-form-label" for="percentage">{{ get_phrase('Percentage') }}</label>
            <input class="form-control ol-form-control" type="number" value="{{$skill->percentage}}" id="percentage" name="percentage" min="0" max="100" required>
        </div>

        <div class="mb-3">
            <label class="form-label ol-form-label" for="description">{{ get_phrase('Description') }}</label>
            <textarea class="form-control ol-form-control" id="description" name="description" rows="4">{{$skill->description}}</textarea>
        </div>
        <div class="mb-3">
            <button type="submit" class="btn ol-btn-primary">{{ get_phrase('Update Skill') }}</button>
        </div>
    </form>
@endif


@include('instructor.init')
