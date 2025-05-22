@extends('layouts.admin')
@push('title', get_phrase('Certificate Manager'))
@section('content')
    <style>
        .hover-effect {
            text-decoration: none;
            color: #333;
            transition: color 0.3s ease, text-decoration 0.3s ease;
        }

        .hover-effect:hover {
            color: #007bff;
            text-decoration: underline;
        }

        .badge {
            font-size: 12px;
            padding: 5px 10px;
        }
    </style>
    <div class="ol-card radius-8px">
        <div class="ol-card-body my-3 py-12px px-20px">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
                <h4 class="title fs-16px">
                    <i class="fi-rr-settings-sliders me-2"></i>
                    {{ get_phrase('Manage Certificate') }}
                </h4>

                <a
                    href="{{ route('admin.certificate_program.create') }}"class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                    <span class="fi-rr-plus"></span>
                    <span>{{ get_phrase('Add New Certificate') }}</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row g-2 g-sm-3 mb-3 row-cols-1 row-cols-sm-2 row-cols-md-4 row-cols-lg-4 row-cols-xl-5">
        <div class="col">
            <a href="{{ route('admin.certificate_program', ['status' => 'active']) }}" class="d-block">
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
            <a href="{{ route('admin.certificate_program', ['status' => 'inactive']) }}" class="d-block">
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
    </div>

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
                                <a href="{{ route('admin.certificate_program') }}" class="me-2" data-bs-toggle="tooltip"
                                    title="{{ get_phrase('Clear') }}"><i class="fi-rr-cross-circle"></i></a>
                            @endif
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <form action="{{ route('admin.certificate_program') }}" method="get">
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
                            @if ($certificate_programs->count() > 0)
                                <div
                                    class="admin-tInfo-pagi d-flex justify-content-between justify-content-center align-items-center flex-wrap gr-15">
                                    <p class="admin-tInfo">
                                        {{ get_phrase('Showing') . ' ' . count($certificate_programs) . ' ' . get_phrase('of') . ' ' . $certificate_programs->total() . ' ' . get_phrase('data') }}
                                    </p>
                                </div>
                                <div class="table-responsive overflow-auto course_list" id="course_list">
                                    <table class="table eTable eTable-2 print-table">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">{{ get_phrase('Title') }}</th>
                                                <th scope="col">{{ get_phrase('Certificate Courses') }}</th>
                                                <th scope="col" class="print-d-none">{{ get_phrase('Status') }}</th>
                                                <th scope="col" class="print-d-none">{{ get_phrase('Options') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($certificate_programs as $key => $row)
                                                @php

                                                    $certificate_courses = is_string($row->course_ids)
                                                        ? json_decode($row->course_ids, true)
                                                        : $row->course_ids;
                                                @endphp
                                                <tr>
                                                    <th scope="row">
                                                        <p class="row-number">{{ ++$key }}</p>
                                                    </th>
                                                    <td>
                                                        <div class="dAdmin_profile d-flex align-items-center min-w-200px">
                                                            <div class="dAdmin_profile_name">
                                                                <h4 class="title fs-14px">
                                                                    <a href="{{ route('admin.certificate_program.edit', $row->id) }}"
                                                                        class="hover-effect" data-bs-toggle="tooltip"
                                                                        data-bs-placement="top" title="Edit Certificate">
                                                                        {{ ucfirst($row->title) }}
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-wrap gap-2">
                                                            @if (is_array($certificate_courses) && !empty($certificate_courses))
                                                                @foreach (App\Models\Course::whereIn('id', $certificate_courses)->get() as $key => $item)
                                                                    <span
                                                                        class="badge bg-info text-dark">{{ ++$key . '. ' . $item->title }}</span>
                                                                @endforeach
                                                            @else
                                                                <span class="badge bg-warning text-dark">No courses</span>
                                                            @endif
                                                        </div>
                                                    </td>

                                                    <td class="print-d-none">
                                                        <span
                                                            class="badge bg-{{ $row->status == 'active' ? 'success' : 'secondary' }}">
                                                            {{ ucfirst($row->status) }}
                                                        </span>
                                                    </td>

                                                    <td class="print-d-none">
                                                        <div class="d-flex flex-column gap-2">
                                                            @if (!empty($row->final_pdf) && file_exists(public_path($row->final_pdf)))
                                                                <a href="{{ asset($row->final_pdf) }}" target="_blank"
                                                                    class="btn btn-sm btn-outline-primary">
                                                                    <i class="fas fa-file-pdf"></i> View PDF
                                                                </a>
                                                                <a href="#" class="btn btn-sm btn-outline-success"
                                                                onclick="ajaxModal('{{ route('modal', ['admin.certificate_program.generate_final_question', 'id' => $row->id]) }}', '{{ get_phrase('Generate Final Question') }}', 'modal-xl')">
                                                                    <i class="fas fa-question-circle"></i> Generate Final Question
                                                                </a>
                                                            @else
                                                                <a href="{{ route('admin.certificate_program.edit', [$row->id, 'tab' => 'pdf']) }}"
                                                                    class="btn btn-sm btn-outline-warning">
                                                                    <i class="fas fa-upload"></i> Upload PDF
                                                                </a>
                                                            @endif

                                                            {{-- Dropdown actions --}}
                                                            <div class="dropdown">
                                                                <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                                    type="button" data-bs-toggle="dropdown">
                                                                    <i class="fas fa-ellipsis-v"></i>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('admin.certificate_program.edit', [$row->id, 'tab' => 'basic']) }}">{{ get_phrase('Edit Certificate') }}</a>
                                                                    </li>
                                                                    @if ($row->status == 'active')
                                                                        <li>
                                                                            <a class="dropdown-item"
                                                                                onclick="confirmModal('{{ route('admin.course.status', ['type' => 'inactive', 'id' => $row->id]) }}')"
                                                                                href="#">{{ get_phrase('Make As Inactive') }}</a>
                                                                        </li>
                                                                    @else
                                                                        <li>
                                                                            <a class="dropdown-item"
                                                                                onclick="confirmModal('{{ route('admin.course.status', ['type' => 'active', 'id' => $row->id]) }}')"
                                                                                href="#">{{ get_phrase('Make As Active') }}</a>
                                                                        </li>
                                                                    @endif
                                                                    <li>
                                                                        <a class="dropdown-item"
                                                                            onclick="confirmModal('{{ route('admin.certificate_program.delete', $row->id) }}')"
                                                                            href="javascript:void(0)">{{ get_phrase('Delete Certificate') }}</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
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
                                        {{ get_phrase('Showing') . ' ' . count($certificate_programs) . ' ' . get_phrase('of') . ' ' . $certificate_programs->total() . ' ' . get_phrase('data') }}
                                    </p>
                                    {{ $certificate_programs->links() }}
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
@endsection
