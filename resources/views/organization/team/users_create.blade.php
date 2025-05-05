@extends('layouts.organization')
@push('title', get_phrase('User add'))

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
                <form action="{{ route('organization.teams.users_add') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 pb-2">
                            <div class="eForm-layouts">
                                <!-- 🔹 Team Selection -->
                                <div class="fpb-7 mb-3">
                                    <label class="form-label ol-form-label">{{ get_phrase('Team Name') }}<span class="text-danger ms-1">*</span></label>
                                    <select class="ol-select2 select2-hidden-accessible" name="team" id="teamSelect" required>
                                        <option value="" disabled selected>{{ get_phrase('Select Team') }}</option>
                                        @foreach (App\Models\Team::where('organization_id', auth()->user()->id)->orderBy('name', 'desc')->get() as $team)
                                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- 🔹 Team Member Count (Readonly) -->
                                <div class="fpb-7 mb-3">
                                    <label class="form-label ol-form-label">{{ get_phrase('Team Members Limit') }}<span class="text-danger ms-1">*</span></label>
                                    <input type="text" name="team_members" id="teamMembersCount" class="form-control ol-form-control" readonly>
                                </div>

                                <!-- 🔹 Member Selection (Multiple) -->
                                <div class="fpb-7 mb-3">
                                    <label class="form-label ol-form-label">{{ get_phrase('Select Members') }}<span class="text-danger ms-1">*</span></label>
                                    <select class="ol-select2 select2-hidden-accessible" id="memberSelect" name="member_ids[]" multiple="multiple">
                                        @foreach (App\Models\User::where('status', 1)->where('organization_id', auth()->user()->id)->orderBy('name', 'desc')->get() as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>

                        <!-- 🔹 Submit Button -->
                        <div class="pt-2">
                            <button type="submit" class="btn ol-btn-primary float-end">{{ get_phrase('Submit') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    $(document).ready(function () {
        var $teamSelect = $('#teamSelect');
        var $teamMembersCount = $('#teamMembersCount');
        var $memberSelect = $('#memberSelect');

        // Function to update the member selection dropdown
        function updateMemberSelect(selectedMembers) {
            $memberSelect.val(null).trigger('change'); // Reset selection
            $memberSelect.find('option').prop('selected', false); // Deselect all
            selectedMembers.forEach(function(memberId) {
                $memberSelect.find('option[value="' + memberId + '"]').prop('selected', true);
            });
            $memberSelect.trigger('change'); // Refresh Select2 UI
        }

        // When the team selection changes
        $teamSelect.on('change', function () {
            var teamId = $(this).val();

            if (teamId) {
                $.ajax({
                    url: "{{ route('organization.teams.get_team_members') }}",
                    type: "GET",
                    data: { team_id: teamId },
                    success: function (response) {
                        if (response.success) {
                            // ✅ Update the team member limit (readonly)
                            $teamMembersCount.val(response.team_members);

                            // ✅ Update the selected members in Select2
                            updateMemberSelect(response.selected_members);
                        } else {
                            alert("Error fetching team details.");
                        }
                    },
                    error: function () {
                        alert("Error fetching team details.");
                    }
                });
            } else {
                // ✅ Reset UI if no team is selected
                $teamMembersCount.val('');
                updateMemberSelect([]);
            }
        });
    });
</script>
@endpush
