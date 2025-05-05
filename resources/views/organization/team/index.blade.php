@extends('layouts.organization')
@push('title', get_phrase('Teams'))
@section('content')
    <style>
        .hover-effect {
            text-decoration: none;
            color: #333;
            transition: color 0.3s ease, text-decoration 0.3s ease;
        }

        .hover-effect:hover {
            color: #007bff;
            /* Change color on hover */
            text-decoration: underline;
            /* Underline on hover */
        }
    </style>
    <div class="ol-card radius-8px">
        <div class="ol-card-body my-3 py-12px px-20px">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
                <h4 class="title fs-16px">
                    <i class="fi-rr-settings-sliders me-2"></i>
                    {{ get_phrase('Manage Teams') }}
                </h4>

                <a
                    href="{{ route('organization.teams.create') }}"class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                    <span class="fi-rr-plus"></span>
                    <span>{{ get_phrase('Add New Team') }}</span>
                </a>
            </div>
        </div>
    </div>

    {{-- <div class="row g-2 g-sm-3 mb-3 row-cols-1 row-cols-sm-2 row-cols-md-4 row-cols-lg-4 row-cols-xl-5">
        <div class="col">
            <a href="{{ route('organization.teams', ['status' => 'active']) }}" class="d-block">
            <div class="ol-card card-hover h-100">
                <div class="ol-card-body px-3 py-12px">
                    <div class="d-flex align-items-center cg-12px">
                        <div>
                            <p class="sub-title fs-14px fw-semibold mb-2">{{ $active_certificate }}</p>
                            <h6 class="title fs-14px mb-1">{{ get_phrase('Active Certificate') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('organization.teams', ['status' => 'inactive']) }}" class="d-block">
            <div class="ol-card card-hover h-100">
                <div class="ol-card-body px-3 py-12px">
                    <div class="d-flex align-items-center cg-12px">
                        <div>
                            <p class="sub-title fs-14px fw-semibold mb-2">{{ $inactive_certificate }}</p>
                            <h6 class="title fs-14px mb-1">{{ get_phrase('Inactive Certificate') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div> --}}

    <!-- Start Admin area -->
    <div class="row">
        <div class="col-12">
            <div class="ol-card">
                <div class="ol-card-body p-3 mb-5">
                    <div class="row mt-3 mb-4">
                        <div class="col-md-6 d-flex align-items-center gap-3">
                            <div class="custom-dropdown ms-2">
                                <button class="dropdown-header btn ol-btn-light">
                                    {{ get_phrase('Export') }}
                                    <i class="fi-rr-file-export ms-2"></i>
                                </button>
                                <ul class="dropdown-list">
                                    <li>
                                        <a class="dropdown-item export-btn" href="#"
                                            onclick="downloadPDF('.print-table', 'course-list')"><i
                                                class="fi-rr-file-pdf"></i> {{ get_phrase('PDF') }}</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item export-btn" href="#" onclick="window.print();"><i
                                                class="fi-rr-print"></i> {{ get_phrase('Print') }}</a>
                                    </li>
                                </ul>
                            </div>




                            @if (isset($_GET) && count($_GET) > 0)
                                <a href="{{ route('organization.teams') }}" class="me-2" data-bs-toggle="tooltip"
                                    title="{{ get_phrase('Clear') }}"><i class="fi-rr-cross-circle"></i></a>
                            @endif
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <form action="{{ route('organization.teams') }}" method="get">
                                <div class="row row-gap-3">
                                    <div class="col-md-9">
                                        <div class="search-input flex-grow-1">
                                            <input type="text" name="search" value="{{ request('search') }}"
                                                placeholder="{{ get_phrase('Search Title') }}"
                                                class="ol-form-control form-control" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn ol-btn-primary w-100"
                                            id="submit-button">{{ get_phrase('Search') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            @if ($teams->count() > 0)
                                <div
                                    class="admin-tInfo-pagi d-flex justify-content-between justify-content-center align-items-center flex-wrap gr-15">
                                    <p class="admin-tInfo">
                                        {{ get_phrase('Showing') . ' ' . count($teams) . ' ' . get_phrase('of') . ' ' . $teams->total() . ' ' . get_phrase('data') }}
                                    </p>
                                </div>
                                <div class="table-responsive overflow-auto course_list" id="course_list">
                                    <table class="table eTable eTable-2 print-table">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">{{ get_phrase('Name') }}</th>
                                                <th scope="col">{{ get_phrase('Team members') }}</th>
                                                <th scope="col">{{ get_phrase('Member info') }}</th>
                                                <th scope="col" class="print-d-none">{{ get_phrase('Options') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($teams as $key => $row)
                                                @php
                                                    $team_members = is_string($row->member_ids)
                                                        ? json_decode($row->member_ids, true)
                                                        : $row->member_ids;
                                                @endphp
                                                <tr>
                                                    <th scope="row">
                                                        <p class="row-number">{{ ++$key }}</p>
                                                    </th>
                                                    <td>
                                                        <div class="dAdmin_profile d-flex align-items-center min-w-200px">
                                                            <div class="dAdmin_profile_name">
                                                                <h4 class="title fs-14px">
                                                                    <a href="{{ route('organization.teams.edit', $row->id) }}"
                                                                        class="hover-effect" data-bs-toggle="tooltip"
                                                                        data-bs-placement="top" title="Edit Team">
                                                                        {{ ucfirst($row->name) }}
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                        </div>

                                                    </td>
                                                    <td class="print-d-none">
                                                        <div class="sub-title2 text-12px">{{ $row->team_members }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="sub-title2 text-12px">
                                                            {{-- <a href="{{ route('admin.courses', ['category' => $row->category->slug ?? '']) }}">{{ category_by_course($row->category_id)->title }}</a> --}}
                                                            @if (is_array($team_members) && !empty($team_members))
                                                                @foreach (App\Models\User::whereIn('id', $team_members)->get() as $key => $item)
                                                                    <p class="sub-title2 text-12px">
                                                                        {{ ++$key . '. ' . $item->name }}</p>
                                                                @endforeach
                                                            @else
                                                                <p class="sub-title2 text-12px">No user available
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </td>



                                                    <td class="print-d-none">
                                                        <div class="dropdown ol-icon-dropdown ol-icon-dropdown-transparent">
                                                            <button class="btn ol-btn-secondary dropdown-toggle"
                                                                type="button" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <span class="fi-rr-menu-dots-vertical"></span>
                                                            </button>

                                                            <ul class="dropdown-menu">

                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('organization.teams.edit', [$row->id, 'tab' => 'basic']) }}">{{ get_phrase('Edit Team') }}</a>
                                                                </li>

                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        onclick="confirmModal('{{ route('organization.teams.delete', $row->id) }}')"
                                                                        href="javascript:void(0)">{{ get_phrase('Delete Team') }}</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div
                                    class="admin-tInfo-pagi d-flex justify-content-between justify-content-center align-items-center flex-wrap gr-15">
                                    <p class="admin-tInfo">
                                        {{ get_phrase('Showing') . ' ' . count($teams) . ' ' . get_phrase('of') . ' ' . $teams->total() . ' ' . get_phrase('data') }}
                                    </p>
                                    {{ $teams->links() }}
                                </div>
                            @else
                                @include('admin.no_data')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Admin area -->
@endsection
