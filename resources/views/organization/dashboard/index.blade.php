@extends('layouts.organization')
@push('title', get_phrase('Dashboard'))
@push('meta')@endpush
@push('css')
   <style>
    .user-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 12px 20px;
        margin-bottom: 10px;
        background: #fff;
        transition: 0.3s;
    }
    .user-card:hover {
        background: #f9f9f9;
    }
    .badge {
        color: #fff;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 13px;
    }
</style>

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

@php
use App\Models\Team;
use App\Models\User;

$organization_user = User::where('organization_id', auth()->user()->id)->count();
$team = Team::where('organization_id', auth()->user()->id)->count();
$teams = Team::where('organization_id', auth()->user()->id)->get();
$users = User::where('organization_id', auth()->user()->id)->get();

// Assign team colors
$colors = ['#4CAF50', '#2196F3', '#FF9800', '#E91E63', '#9C27B0', '#00BCD4', '#FFC107', '#795548'];
$teamColors = [];
$teamMembersMap = [];

foreach ($teams as $index => $t) {
    $teamColors[$t->id] = $colors[$index % count($colors)];
    $memberIds = is_array($t->member_ids) ? $t->member_ids : json_decode($t->member_ids, true);
    foreach ($memberIds ?? [] as $id) {
        $teamMembersMap[$id][] = [
            'team_name' => $t->name,
            'color' => $teamColors[$t->id],
        ];
    }
}
@endphp


<div class="row g-2 g-sm-3 my-3 row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
    <div class="col">
        <div class="ol-card card-hover">
            <div class="ol-card-body px-20px py-3">
                <p class="title card-title-hover fs-18px my-2">{{ $organization_user }}</p>
                <p class="sub-title fs-14px">{{ get_phrase('Number of Members') }}</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="ol-card card-hover">
            <div class="ol-card-body px-20px py-3">
                <p class="title card-title-hover fs-18px my-2">{{ $team }}</p>
                <p class="sub-title fs-14px">{{ get_phrase('Number of Team') }}</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="ol-card card-hover">
            <div class="ol-card-body px-20px py-3">
                <p class="title card-title-hover fs-18px my-2">{{ $teamMembersMap ? count($teamMembersMap) : 0 }}</p>
                <p class="sub-title fs-14px">{{ get_phrase('Number of Team Members') }}</p>
            </div>
        </div>
    </div>
</div>

{{-- User List Section --}}
<div class="mt-4">
    <h5 class="fs-18px mb-3"><i class="fi-rr-users me-2"></i>{{ get_phrase('Organization Users') }}</h5>
    <div class="row">
        @forelse($users as $user)
            <div class="col-md-6 col-lg-4">
                <div class="user-card">
                    <h6 class="mb-1">{{ $user->name }}</h6>
                    <p class="mb-1 text-muted">{{ $user->email }}</p>

                    @if(isset($teamMembersMap[$user->id]))
                        <p class="mb-1"><strong>{{ get_phrase('Team Member') }}</strong></p>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($teamMembersMap[$user->id] as $teamInfo)
                                <span class="badge" style="background-color: {{ $teamInfo['color'] }};">
                                    {{ $teamInfo['team_name'] }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0"><em>{{ get_phrase('Not in any team') }}</em></p>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-muted">{{ get_phrase('No users found.') }}</p>
        @endforelse
    </div>
</div>

@endsection
