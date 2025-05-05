@extends('layouts.organization')

@push('title', get_phrase('Subscription'))
@push('meta')@endpush
@push('css')
@endpush

@section('content')
    <div class="ol-card radius-8px">
        <div class="ol-card-body my-3 py-4 px-20px">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
                <h4 class="title fs-16px">
                    <i class="fi-rr-settings-sliders me-2"></i>
                    {{ get_phrase('Subscription') }}
                </h4>
                <a href="{{ route('organization.teams.users') }}" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                    <span class="fi-rr-plus"></span>
                    <span>{{ get_phrase('Add User in Team') }}</span>
                </a>
            </div>
        </div>
    </div>

    @foreach ($subscriptions as $row)
        @php
            $users = App\Models\User::where('organization_id', $row->user_id)->get();
            $teams = App\Models\Team::where('organization_id', $row->user_id)->get();
            $usersWithTeams = collect();
        @endphp

        <div class="row mt-4 p-3">
            <div class="card shadow-sm p-3 mb-4 rounded border-0">
                <div class="card-body">
                    <h5 class="card-title">Subscription Type: {{ $row->subscription_type }}</h5>
                    <p class="text-muted">License Amount: <strong>{{ $row->license_amount }}</strong></p>
                    <p class="text-muted">Entry Date: <strong>{{ \Carbon\Carbon::parse($row->entry_date)->format('d M Y') }}</strong></p>
                    <p class="text-muted">Expiry Date: <strong>{{ \Carbon\Carbon::parse($row->expiry_date)->format('d M Y') }}</strong></p>
                </div>
            </div>

            @foreach ($teams as $team)
                {{-- @php
                    $memberIds = $team->member_ids ? json_decode($team->member_ids, true) : [];
                    $teamUsers = $users->filter(fn($user) => in_array((string) $user->id, $memberIds));
                    $usersWithTeams = $usersWithTeams->merge($teamUsers);
                @endphp --}}
                @php
                // Ensure member_ids is properly formatted as an array
                $memberIds = is_string($team->member_ids) ? json_decode($team->member_ids, true) : (is_array($team->member_ids) ? $team->member_ids : []);

                // Filter users who belong to this team
                $teamUsers = $users->filter(fn($user) => in_array((string) $user->id, $memberIds));

                // Ensure $usersWithTeams is a collection before merging
                if (!isset($usersWithTeams)) {
                    $usersWithTeams = collect([]);
                }

                $usersWithTeams = $usersWithTeams->merge($teamUsers);
            @endphp

                <div class="card shadow-sm p-3 mb-4 rounded border-0">
                    <div class="card-body">
                        <h5 class="card-title">Team: {{ $team->name }}</h5>
                        <p class="text-muted">Created On: <strong>{{ \Carbon\Carbon::parse($team->created_at)->format('d M Y') }}</strong></p>
                        <p class="text-muted">Last Updated: <strong>{{ \Carbon\Carbon::parse($team->updated_at)->format('d M Y') }}</strong></p>
                        <p class="text-muted">Total Members: <strong>{{ $team->team_members }}</strong></p>
                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#team-{{ $team->id }}" aria-expanded="false">
                            View Team Members
                        </button>
                        <div class="collapse mt-3" id="team-{{ $team->id }}">
                            @if ($teamUsers->count() > 0)
                                <ul class="list-group">
                                    @foreach ($teamUsers as $user)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>{{ $user->name }} ({{ $user->email }})</span>
                                            {{-- <a href="{{ route('organization.users.edit', $user->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a> --}}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">No users assigned to this team.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            @php
                $usersWithoutTeams = $users->diff($usersWithTeams);
            @endphp

            @if ($usersWithoutTeams->count() > 0)
                <div class="card shadow-sm p-3 mb-4 rounded border-0">
                    <div class="card-body">
                        <h5 class="card-title">Users Without Teams</h5>
                        <ul class="list-group">
                            @foreach ($usersWithoutTeams as $user)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $user->name }} ({{ $user->email }})</span>
                                    {{-- <a href="{{ route('organization.users.edit', $user->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a> --}}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    @endforeach
@endsection

@push('js')
@endpush
