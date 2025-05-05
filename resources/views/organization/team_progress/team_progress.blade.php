@extends('layouts.organization')

@push('title', get_phrase('Subscription Progress'))
@push('meta')@endpush
@push('css')
<style>
    .hidden-row {
        display: none;
    }
    .course-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 15px;
    }
    .course-card {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 8px;
        background: #f9f9f9;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .course-not-started {
        background: #f8f9fa;
    }
    .course-in-progress {
        background: #ffeeba;
    }
    .course-completed {
        background: #c3e6cb;
    }
</style>
@endpush

@section('content')
    <div class="ol-card radius-8px">
        <div class="ol-card-body my-3 py-4 px-20px">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
                <h4 class="title fs-16px">
                    <i class="fi-rr-settings-sliders me-2"></i>
                    {{ get_phrase('Subscription Progress') }}
                </h4>
            </div>
        </div>
    </div>

    <!-- Subscription Details -->
    <div class="card shadow-sm p-3 mb-4 rounded border-0">
        <div class="card-body">
            <h5 class="card-title">Subscription Details</h5>
            <p class="text-muted">Subscription Type: <strong>{{ $subscription->subscription_type }}</strong></p>
            <p class="text-muted">License Amount: <strong>{{ $subscription->license_amount }}</strong></p>
            <p class="text-muted">Entry Date:
                <strong>{{ \Carbon\Carbon::parse($subscription->entry_date)->format('d M Y') }}</strong></p>
            <p class="text-muted">Expiry Date:
                <strong>{{ \Carbon\Carbon::parse($subscription->expiry_date)->format('d M Y') }}</strong></p>
        </div>
    </div>

    <!-- Teams and Users -->
    @foreach ($teams as $team)
        <div class="card shadow-sm p-3 mb-4 rounded border-0">
            <div class="card-body">
                <h5 class="card-title">Team: {{ $team->name }}</h5>
                <p class="text-muted">Created On:
                    <strong>{{ \Carbon\Carbon::parse($team->created_at)->format('d M Y') }}</strong></p>
                <p class="text-muted">Last Updated:
                    <strong>{{ \Carbon\Carbon::parse($team->updated_at)->format('d M Y') }}</strong></p>
                <p class="text-muted">Team Member Capecity:
                    <strong>{{ $team->team_members}}</strong></p>
                <p class="text-muted">
                    Total Members:
                    <strong>
                        {{ count(is_string($team->member_ids) ? json_decode($team->member_ids, true) : (is_array($team->member_ids) ? $team->member_ids : [])) }}
                    </strong>
                </p>

                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Overall Progress (%)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usersWithProgress as $user)
                            @php
                                $teamMemberIds = is_string($team->member_ids) ? json_decode($team->member_ids, true) : $team->member_ids;
                                if (!is_array($teamMemberIds)) {
                                    $teamMemberIds = [];
                                }
                            @endphp

                            @if (in_array($user['user_id'], $teamMemberIds))
                                <tr>
                                    <td>{{ $user['name'] }}</td>
                                    <td>{{ $user['email'] }}</td>
                                    <td>{{ $user['phone'] }}</td>
                                    <td>{{ $user['overall_progress'] }}%</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" onclick="toggleCourses({{ $user['user_id'] }})">Explore</button>
                                    </td>
                                </tr>
                                <tr id="courses-{{ $user['user_id'] }}" class="hidden-row">
                                    <td colspan="5">
                                        <strong>Course Progress:</strong>
                                        <div class="course-grid">
                                            @foreach ($user['courses'] as $course)
                                                @php
                                                    $courseClass = 'course-not-started';
                                                    if ($course['completion_percentage'] > 0 && $course['completion_percentage'] < 100) {
                                                        $courseClass = 'course-in-progress';
                                                    } elseif ($course['completion_percentage'] == 100) {
                                                        $courseClass = 'course-completed';
                                                    }
                                                @endphp
                                                <div class="course-card {{ $courseClass }}">
                                                    <h6>{{ $course['course_name'] }}</h6>
                                                    <p><strong>Progress:</strong> {{ $course['completion_percentage'] }}%</p>
                                                    <p><strong>Lessons:</strong> {{ $course['total_number_of_completed_lessons'] }}/{{ $course['total_number_of_lessons'] }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@endsection

@push('js')
<script>
    function toggleCourses(userId) {
        let row = document.getElementById('courses-' + userId);
        if (row.style.display === 'none' || row.style.display === '') {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    }
</script>
@endpush
