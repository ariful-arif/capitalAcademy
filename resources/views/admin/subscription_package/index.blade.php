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

    <div class="row g-2 g-sm-3 mb-3 row-cols-1 row-cols-sm-2 row-cols-md-4 row-cols-lg-4 row-cols-xl-5">
        <div class="col">
            {{-- <a href="{{ route('admin.courses', ['status' => 'active']) }}" class="d-block">
                <div class="ol-card card-hover h-100">
                    <div class="ol-card-body px-3 py-12px">
                        <div class="d-flex align-items-center cg-12px">
                            <div>
                                <p class="sub-title fs-14px fw-semibold mb-2">{{ $active_courses }}</p>
                                <h6 class="title fs-14px mb-1">{{ get_phrase('Active courses') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </a> --}}
        </div>
        <div class="col">
            {{-- <a href="{{ route('admin.courses', ['status' => 'pending']) }}" class="d-block">
                <div class="ol-card card-hover h-100">
                    <div class="ol-card-body px-3 py-12px">
                        <div class="d-flex align-items-center cg-12px">
                            <div>
                                <p class="sub-title fs-14px fw-semibold mb-2">{{ $pending_courses }}</p>
                                <h6 class="title fs-14px mb-1">{{ get_phrase('Pending courses') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </a> --}}
        </div>
        <div class="col">
            {{-- <a href="{{ route('admin.courses', ['status' => 'upcoming']) }}" class="d-block">
                <div class="ol-card card-hover h-100">
                    <div class="ol-card-body px-3 py-12px">
                        <div class="d-flex align-items-center cg-12px">
                            <div>
                                <p class="sub-title fs-14px fw-semibold mb-2">{{ $upcoming_courses }}</p>
                                <h6 class="title fs-14px mb-1">{{ get_phrase('Upcoming courses') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </a> --}}
        </div>
        <div class="col">
            {{-- <a href="{{ route('admin.courses', ['price' => 'free']) }}" class="d-block">
                <div class="ol-card card-hover h-100">
                    <div class="ol-card-body px-3 py-12px">
                        <div class="d-flex align-items-center cg-12px">
                            <div>
                                <p class="sub-title fs-14px fw-semibold mb-2">{{ $free_courses }}</p>
                                <h6 class="title fs-14px mb-1">{{ get_phrase('Free courses') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </a> --}}
        </div>
        <div class="col">

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
                                <a href="{{ route('admin.subscription_package') }}" class="me-2" data-bs-toggle="tooltip"
                                    title="{{ get_phrase('Clear') }}"><i class="fi-rr-cross-circle"></i></a>
                            @endif
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <form action="{{ route('admin.subscription_package') }}" method="get">
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
                            @if ($subscription_package->count() > 0)
                                <div
                                    class="admin-tInfo-pagi d-flex justify-content-between justify-content-center align-items-center flex-wrap gr-15">
                                    <p class="admin-tInfo">
                                        {{ get_phrase('Showing') . ' ' . count($subscription_package) . ' ' . get_phrase('of') . ' ' . $subscription_package->total() . ' ' . get_phrase('data') }}
                                    </p>
                                </div>
                                <div class="table-responsive course_list overflow-auto" id="course_list">
                                    <table class="table eTable eTable-2 print-table">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">{{ get_phrase('Package Name') }}</th>
                                                <th scope="col">{{ get_phrase('Subscription Type') }}</th>
                                                <th scope="col">{{ get_phrase('Package Type') }}</th>
                                                <th scope="col" class="print-d-none">{{ get_phrase('Status') }}</th>
                                                <th scope="col">{{ get_phrase('Price') }}</th>
                                                <th scope="col">{{ get_phrase('Discount Price') }}</th>
                                                <th scope="col" class="print-d-none">{{ get_phrase('Options') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($subscription_package as $key => $row)

                                                <tr>
                                                    <td>{{ ++$key }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.subscription_package.edit', $row->id) }}"
                                                            class="hover-effect" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="Edit Subscription">
                                                            {{ ucfirst($row->package_name) }}
                                                        </a>
                                                            {{-- {{ $row->package_name }} --}}
                                                        </td>

                                                    <td>
                                                        <div class="dAdmin_profile d-flex align-items-center min-w-200px">
                                                            <div class="dAdmin_profile_name">
                                                                {{ $row->subscription_type }}
                                                                
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="dAdmin_profile d-flex align-items-center min-w-200px">
                                                            <div class="dAdmin_profile_name">
                                                                {{ $row->package_type }}
                                                                @if ($row->package_type == 'Monthly')
                                                                    <p class="sub-title2 text-12px pt-1">
                                                                        {{ "($row->package_duration Month)" }}</p>
                                                                @else
                                                                    <p class="sub-title2 text-12px pt-1">
                                                                        {{ "($row->package_duration Year)" }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="print-d-none">
                                                        <span
                                                            class="badge bg-{{ $row->status }}">{{ get_phrase(ucfirst($row->status)) }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="dAdmin_info_name min-w-150px">
                                                            @if ($row->is_paid == 0)
                                                                <p class="eBadge ebg-soft-success">
                                                                    {{ get_phrase('Free') }}
                                                                </p>
                                                            @else
                                                                <p>{{ currency($row->price) }}</p>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if ($row->discount_flag == 1)
                                                            {{ currency($row->discounted_price) }}
                                                        @else
                                                            <p class="sub-title2 text-12px">
                                                                {{ get_phrase('No Discount') }}
                                                            </p>
                                                        @endif
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
                                        {{ get_phrase('Showing') . ' ' . count($subscription_package) . ' ' . get_phrase('of') . ' ' . $subscription_package->total() . ' ' . get_phrase('data') }}
                                    </p>
                                    {{ $subscription_package->links() }}
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
