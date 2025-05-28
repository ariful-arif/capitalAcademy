@extends('layouts.admin')
@push('title', get_phrase('Subscription List'))
@section('content')
    <div class="ol-card radius-8px">
        <div class="ol-card-body my-3 py-12px px-20px">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
                <h4 class="title fs-16px">
                    <i class="fi-rr-settings-sliders me-2"></i>
                    {{ get_phrase('Manage Subscription List') }}
                </h4>
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
                                <a href="{{ route('admin.membership.subscriptions') }}" class="me-2"
                                    data-bs-toggle="tooltip" title="{{ get_phrase('Clear') }}"><i
                                        class="fi-rr-cross-circle"></i></a>
                            @endif
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <form action="{{ route('admin.membership.subscriptions') }}" method="get">
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
                            @if ($subscription_list->count() > 0)
                                <div
                                    class="admin-tInfo-pagi d-flex justify-content-between justify-content-center align-items-center flex-wrap gr-15">
                                    <p class="admin-tInfo">
                                        {{ get_phrase('Showing') . ' ' . count($subscription_list) . ' ' . get_phrase('of') . ' ' . $subscription_list->total() . ' ' . get_phrase('data') }}
                                    </p>
                                </div>
                                <div class="table-responsive course_list overflow-auto" id="course_list">
                                    <table class="table eTable eTable-2 print-table">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">{{ get_phrase('User') }}</th>
                                                <th scope="col">{{ get_phrase('Package') }}</th>
                                                <th scope="col">{{ get_phrase('Package Type') }}</th>
                                                <th scope="col">{{ get_phrase('Price') }}</th>
                                                <th scope="col">{{ get_phrase('Purchase Date') }}</th>
                                                <th scope="col">{{ get_phrase('Expiry Date') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($subscription_list as $key => $row)
                                                <tr>
                                                    <td>{{ ++$key }}</td>
                                                    <td>
                                                        <div class="dAdmin_profile d-flex align-items-center min-w-200px">
                                                            <div class="dAdmin_profile_name">
                                                                {{ $row->user->name }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="dAdmin_profile d-flex align-items-center min-w-200px">
                                                            <div class="dAdmin_profile_name">
                                                                {{ $row->package->title }}
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <div class="dAdmin_profile d-flex align-items-center min-w-200px">
                                                            <div class="dAdmin_profile_name">
                                                                @if ($row->package->type == 'Monthly')
                                                                    <p class="sub-title2 text-12px pt-1">
                                                                        {{ $row->package->type . '(' . $row->package->period . ' Month)' }}
                                                                    </p>
                                                                @else
                                                                    <p class="sub-title2 text-12px pt-1">
                                                                        {{ $row->package->type . '(' . $row->package->period . ' Year)' }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="dAdmin_profile d-flex align-items-center min-w-200px">
                                                            <div class="dAdmin_profile_name">
                                                                {{ currency($row->paid_amount) }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="dAdmin_info_name min-w-250px">
                                                            <p>{{ date('F d Y', $row->purchase_date) }}</p>

                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="dAdmin_info_name min-w-250px">
                                                            <p>{{ date('F d Y', $row->expiry_date) }}</p>
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
                                        {{ get_phrase('Showing') . ' ' . count($subscription_list) . ' ' . get_phrase('of') . ' ' . $subscription_list->total() . ' ' . get_phrase('data') }}
                                    </p>
                                    {{ $subscription_list->links() }}
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
