@extends('layouts.admin')
@push('title', get_phrase('Subscription Courses'))
@section('content')
    <div class="ol-card radius-8px">
        <div class="ol-card-body my-3 py-12px px-20px">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
                <h4 class="title fs-16px">
                    <i class="fi-rr-settings-sliders me-2"></i>
                    {{ get_phrase('Manage Subscription Package Courses') }}
                </h4>

                <a
                    href="{{ route('admin.subscription_package.create') }}"class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                    <span class="fi-rr-plus"></span>
                    <span>{{ get_phrase('Add New Subscription') }}</span>
                </a>
            </div>
        </div>
    </div>


    <!-- Start Admin area -->
    <div class="row">
        <div class="col-12">
            <div class="ol-card">
                <div class="ol-card-body p-3 mb-3">
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
                                <a href="{{ route('admin.membership.settings') }}" class="me-2" data-bs-toggle="tooltip"
                                    title="{{ get_phrase('Clear') }}"><i class="fi-rr-cross-circle"></i></a>
                            @endif
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <form action="{{ route('admin.membership.settings') }}" method="get">
                                <div class="row row-gap-3">
                                    <div class="col-md-9">
                                        <div class="search-input flex-grow-1">
                                            <input type="text" name="search" value="{{ request('search') }}"
                                                placeholder="{{ get_phrase('Search Package Name') }}"
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
                            @if ($packages->count() > 0)
                                <div
                                    class="admin-tInfo-pagi d-flex justify-content-between justify-content-center align-items-center flex-wrap gr-15">
                                    <p class="admin-tInfo">
                                        {{ get_phrase('Showing') . ' ' . count($packages) . ' ' . get_phrase('of') . ' ' . $packages->total() . ' ' . get_phrase('data') }}
                                    </p>
                                </div>
                                <div class="table-responsive course_list overflow-auto" id="course_list">
                                    <table class="table eTable eTable-2 print-table">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">{{ get_phrase('Title') }}</th>
                                                <th scope="col">{{ get_phrase('Package Type') }}</th>
                                                <th scope="col">{{ get_phrase('Period') }}</th>
                                                <th scope="col" class="print-d-none">{{ get_phrase('Status') }}</th>
                                                <th scope="col">{{ get_phrase('Price') }}</th>
                                                <th scope="col" class="print-d-none">{{ get_phrase('Options') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($packages as $key => $row)

                                                <tr>
                                                    <td>{{ ++$key }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.subscription_package.edit', $row->id) }}"
                                                            class="hover-effect" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="Edit Subscription">
                                                            {{ ucfirst($row->title) }}
                                                        </a>
                                                    </td>

                                                    <td>
                                                        <div class="dAdmin_profile d-flex align-items-center min-w-200px">
                                                            <div class="dAdmin_profile_name">
                                                                {{ $row->type }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="dAdmin_profile d-flex align-items-center min-w-200px">
                                                            <div class="dAdmin_profile_name">
                                                                @if ($row->type == 'Monthly')
                                                                    <p class="sub-title2 text-12px pt-1">
                                                                        {{ "($row->period Month)" }}</p>
                                                                @else
                                                                    <p class="sub-title2 text-12px pt-1">
                                                                        {{ "($row->period Year)" }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="print-d-none">
                                                        @php
                                                        if ($row->status == 1) {
                                                            $status = 'active';
                                                        } else {
                                                            $status = 'inactive';
                                                        }
                                                        @endphp
                                                        <span class="badge bg-{{ $status }}">
                                                            {{ get_phrase(ucfirst($status)) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="dAdmin_info_name min-w-150px">
                                                            <p>{{ currency($row->price) }}</p>
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
                                                                        href="{{ route('admin.subscription_package.edit', ['id' => $row->id]) }}">{{ get_phrase('Edit Package') }}</a>
                                                                </li>

                                                                @if ($row->status == 'active')
                                                                    <li>
                                                                        <a class="dropdown-item"
                                                                            onclick="confirmModal('{{ route('admin.subscription_package.status', ['type' => 'inactive', 'id' => $row->id]) }}')"
                                                                            href="javascript:void(0)">{{ get_phrase('Make As Inactive') }}</a>
                                                                    </li>
                                                                @else
                                                                    <li>
                                                                        <a class="dropdown-item"
                                                                            onclick="confirmModal('{{ route('admin.subscription_package.status', ['type' => 'active', 'id' => $row->id]) }}')"
                                                                            href="javascript:void(0)">{{ get_phrase('Make As Active') }}</a>
                                                                    </li>
                                                                @endif
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        onclick="confirmModal('{{ route('admin.subscription_package.delete', $row->id) }}')"
                                                                        href="javascript:void(0)">{{ get_phrase('Delete Package') }}</a>
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
                                        {{ get_phrase('Showing') . ' ' . count($packages) . ' ' . get_phrase('of') . ' ' . $packages->total() . ' ' . get_phrase('data') }}
                                    </p>
                                    {{ $packages->links() }}
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

    <div class="row justify-content-center">
        <div class="col-xl-12">
            <div class="ol-card p-4">
                <div class="ol-card-body">
                    <div class="col-md-12 pb-3">
                        <h4 class="title mt-4 mb-3">{{ get_phrase('Membership page settings') }}</h4>
                        <form action="{{ route('admin.membership.settings.update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type" value="affiliate_page">
                            <!--  Title -->
                            <div class="fpb-7 mb-3">
                                <label class="form-label ol-form-label" for="title">{{ get_phrase('Title') }}<span
                                        class="required">*</span></label>
                                <input type="text" name="title" id="title" class="form-control ol-form-control"
                                    value="{{ $membership_details->title ?? '' }}">
                            </div>

                            <!--  Subtitle -->
                            <div class="fpb-7 mb-3">
                                <label class="form-label ol-form-label" for="subtitle">{{ get_phrase('Sub title') }}<span
                                        class="required">*</span></label>
                                <textarea name="subtitle" id="subtitle" class="form-control ol-form-control" rows="3">{{ $membership_details->subtitle ?? '' }}</textarea>
                            </div>
                            <!--  Thumbnail -->
                            <div class="fpb-7 mb-3">
                                <label class="form-label ol-form-label" for="thumbnail">{{ get_phrase('Thumbnail') }}<span
                                        class="required">*</span></label>
                                @if (!empty($membership_details->thumbnail))
                                    <img src="{{ asset($membership_details->thumbnail) }}" alt="Thumbnail" class="img-fluid mb-3"
                                        style="width: 200px; height: 150px;">
                                @endif
                                <input type="file" name="thumbnail" id="thumbnail" class="form-control ol-form-control" accept="image/*">
                            </div>

                            <div class="fpb-7 mb-3">
                                <label class="form-label ol-form-label" for="member_count">{{ get_phrase('Member Count') }}<span
                                        class="required">*</span></label>
                                <input type="number" name="member_count" id="member_count" class="form-control ol-form-control"
                                    value="{{ $membership_details->member_count ?? '' }}">
                            </div>

                            <div class="fpb-7 mb-3">
                                <label class="form-label ol-form-label" for="package_section_title">{{ get_phrase('Title') }}<span
                                        class="required">*</span></label>
                                <input type="text" name="package_section_title" id="package_section_title" class="form-control ol-form-control"
                                    value="{{ $membership_details->package_section_title ?? '' }}">
                            </div>


                            <!-- Submit Button -->
                            <div class="fpb-7 mb-3">
                                <button type="submit" class="btn ol-btn-primary">{{ get_phrase('Update') }}</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
