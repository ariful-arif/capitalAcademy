@extends('layouts.organization')
@push('title', get_phrase('Dashboard'))
@push('meta')@endpush
@push('css')
@endpush
@section('content')
    <div class="ol-card radius-8px">
        <div class="ol-card-body my-3 py-4 px-20px">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
                <h4 class="title fs-16px">
                    <i class="fi-rr-settings-sliders me-2"></i>
                    {{ get_phrase('Dashboard') }}
                </h4>
            </div>
        </div>
    </div>

    {{-- <div class="row g-2 g-sm-3 my-3 row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
        <div class="col">
            <div class="ol-card card-hover">
                <div class="ol-card-body px-20px py-3">
                    <p class="title card-title-hover fs-18px my-2">
                        {{ count_course_by_instructor(auth()->user()->id) }}
                    </p>
                    <p class="sub-title fs-14px">{{ get_phrase('Number of Courses') }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="ol-card card-hover">
                <div class="ol-card-body px-20px py-3">
                    <p class="title card-title-hover fs-18px my-2">
                        {{ count_instructor_lesson(auth()->user()->id) }}
                    </p>
                    <p class="sub-title fs-14px">{{ get_phrase('Number of Lessons') }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="ol-card card-hover">
                <div class="ol-card-body px-20px py-3">
                    <p class="title card-title-hover fs-18px my-2">
                        {{ count_student_by_instructor(auth()->user()->id) }}
                    </p>
                    <p class="sub-title fs-14px">{{ get_phrase('Number of Enrollment') }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="ol-card card-hover">
                <div class="ol-card-body px-20px py-3">
                    <p class="title card-title-hover fs-18px my-2">
                        {{ total_enrolled() }}
                    </p>
                    <p class="sub-title fs-14px">{{ get_phrase('Number of Students') }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="ol-card card-hover">
                <div class="ol-card-body px-20px py-3">
                    <p class="title card-title-hover fs-18px my-2">
                        {{ App\Models\User::where('role', 'instructor')->count() }}</p>
                    <p class="sub-title fs-14px">{{ get_phrase('Number of Instructor') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="ol-card p-3">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="title fs-14px">{{ get_phrase('Instructor Revenue This Year') }}</h2>
                    </div>
                    <div class="col-md-6 text-end">
                        <a class="btn-link" href="{{ route('instructor.payout.reports') }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ get_phrase('Instructor Revenue') }}"><i class="fi-rr-arrow-alt-right"></i></a>
                    </div>
                </div>
                <div class="ol-card-body">
                    <canvas id="myChart" class="mw-100 w-100" height="320px"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row my-3">
        <div class="col-md-5">
            <div class="ol-card">
                <div class="ol-card-body p-3">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="title fs-14px">{{ get_phrase('Course Status') }}</h4>
                        </div>
                        <div class="col-md-6 text-end">
                            <a class="btn-link" href="{{ route('instructor.courses') }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ get_phrase('Explore Courses') }}"><i class="fi-rr-arrow-alt-right"></i></a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center g-30px flex-wrap flex-xl-nowrap justify-content-center">
                        <div class="pie-chart-sm">
                            <canvas id="pie2"></canvas>
                        </div>
                        <div class="pie-chart-sm-details">
                            <ul class="color-info-list">
                                <li>
                                    <span class="info-list-color bg-active"></span>
                                    <span class="title2 fs-14px">{{ get_phrase('Active') }}</span>
                                </li>
                                <li>
                                    <span class="info-list-color bg-upcoming"></span>
                                    <span class="title2 fs-14px">{{ get_phrase('Upcoming') }}</span>
                                </li>
                                <li>
                                    <span class="info-list-color bg-pending"></span>
                                    <span class="title2 fs-14px">{{ get_phrase('Pending') }}</span>
                                </li>
                                <li>
                                    <span class="info-list-color bg-private"></span>
                                    <span class="title2 fs-14px">{{ get_phrase('Private') }}</span>
                                </li>
                                <li>
                                    <span class="info-list-color bg-draft"></span>
                                    <span class="title2 fs-14px">{{ get_phrase('Draft') }}</span>
                                </li>
                                <li>
                                    <span class="info-list-color bg-inactive"></span>
                                    <span class="title2 fs-14px">{{ get_phrase('Inactive') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="ol-card" id = 'unpaid-instructor-revenue'>
                <div class="ol-card-body p-3">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="title text-14px mb-3">{{ get_phrase('Pending Requested withdrawal') }}</h4>
                        </div>
                        <div class="col-md-6 text-end">
                            <a class="btn-link" href="{{ route('instructor.payout.reports') }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ get_phrase('Instructor Payout') }}"><i class="fi-rr-arrow-alt-right"></i></a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <tbody>
                                @php
                                    $payouts = App\Models\Payout::where('user_id', auth()->user()->id)
                                        ->limit(20)
                                        ->orderBy('id')
                                        ->get();
                                @endphp
                                @foreach ($payouts as $payout)
                                    <tr>
                                        <td>
                                            <p class="title fs-14px">{{ get_phrase('Name') }}: {{ $payout->user->name }}</p>
                                            <small>{{ get_phrase('Email') }}: <span class="text-muted font-13">{{ $payout->user->email }}</span></small>
                                        </td>
                                        <td>
                                            <p class="title fs-14px">{{ currency($payout->amount) }}</p>
                                            <small><span class="text-muted font-13">{{ get_phrase('Requested withdrawal amount') }}</span></small>
                                        </td>
                                        <td>
                                            @if ($payout->status == 1)
                                                <span class="badge bg-success">{{ get_phrase('Paid') }}</span>
                                            @else
                                                <span class="badge bg-warning">{{ get_phrase('Pending') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $courses = App\Models\Course::where('user_id', auth()->user()->id)
            ->get()
            ->groupBy('status');
        $active = isset($courses['active']) ? $courses['active']->count() : 0;
        $upcoming = isset($courses['upcoming']) ? $courses['upcoming']->count() : 0;
        $pending = isset($courses['pending']) ? $courses['pending']->count() : 0;
        $private = isset($courses['private']) ? $courses['private']->count() : 0;
        $draft = isset($courses['draft']) ? $courses['draft']->count() : 0;
        $inactive = isset($courses['inactive']) ? $courses['inactive']->count() : 0;
    @endphp --}}
@endsection

