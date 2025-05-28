<div class="course_skill_form">
    @if (isset($id))
        @include('admin.course.skill.edit')
    @elseif(isset($course_id))
        @include('admin.course.skill.add')
    @endif
</div>

<h5 class="mb-3 mt-4">{{ get_phrase('Skill List') }}</h5>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>{{ get_phrase('Skill Name') }}</th>
            <th>{{ get_phrase('Percentage') }}</th>
            <th>{{ get_phrase('Description') }}</th>
            <th>{{ get_phrase('Actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach (App\Models\Course_skill::where('course_id', $course_id ?? '')->orderBy('id', 'desc')->get() as $skill)
            <tr>
                <td>{{ $skill->name }}</td>
                <td>{{ $skill->percentage }}%</td>
                <td>{{ $skill->description }}</td>
                <td class="text-end">
                    <a href="#" onclick="loadView('{{ route('view', ['path' => 'admin.course.skill.edit', 'id' => $skill->id]) }}', '.course_skill_form')" class="btn btn-sm btn-primary me-2">{{ get_phrase('Edit') }}</a>
                    <a href="#" onclick="confirmModal('{{ route('admin.course_skill.delete', ['id' => $skill->id]) }}', true)" class="btn btn-sm
                        btn-danger">{{get_phrase('Delete') }}</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>