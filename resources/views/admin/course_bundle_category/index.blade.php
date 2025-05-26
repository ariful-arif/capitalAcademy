@extends('layouts.admin')
@push('title', get_phrase('Bundle Categories'))
@push('meta')
@endpush
@push('css')
@endpush
@section('content')
    <div class="ol-card radius-8px">
        <div class="ol-card-body my-3 py-12px px-20px">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
                <h4 class="title fs-16px">
                    <i class="fi-rr-settings-sliders me-2"></i>
                    {{ get_phrase('Bundle category list') }}
                </h4>

                <a onclick="showRightModal('{{ route('view', ['path' => 'admin.course_bundle_category.add']) }}', '{{ get_phrase('Add bundle category') }}')" href="#" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                    <span class="fi-rr-plus"></span>
                    <span>{{ get_phrase('Add category') }}</span>
                </a>
            </div>
        </div>
    </div>


    <div class="ol-card p-4">
        <div class="ol-card-body">

            <div class="row print-d-none mb-3 mt-3 row-gap-3">
                <div class="col-md-6 pt-2 pt-md-0">
                    <div class="custom-dropdown">
                        <button class="dropdown-header btn ol-btn-light">
                            {{ get_phrase('Export') }}
                            <i class="fi-rr-file-export ms-2"></i>
                        </button>
                        <ul class="dropdown-list">
                            <li>
                                <a class="dropdown-item" href="#" onclick="downloadPDF('.print-table', 'admin-list')"><i class="fi-rr-file-pdf"></i> {{ get_phrase('PDF') }}</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="window.print();"><i class="fi-rr-print"></i> {{ get_phrase('Print') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <form class="form-inline" action="{{ route('admin.course_bundle.categories') }}" method="get">
                        <div class="row row-gap-3">
                            <div class="col-md-9">
                                <input type="text" class="form-control ol-form-control" name="search" value="{{ request('search') }}" placeholder="{{ get_phrase('Search user') }}" />
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn ol-btn-primary w-100" id="submit-button"> {{ get_phrase('Search') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    @if (count($bundle_categories) > 0)
                        <div class="admin-tInfo-pagi d-flex justify-content-between justify-content-center align-items-center flex-wrap gr-15">
                            <p class="admin-tInfo">
                                {{ get_phrase('Showing') . ' ' . count($bundle_categories) . ' ' . get_phrase('of') . ' ' . $bundle_categories->total() . ' ' . get_phrase('data') }}
                            </p>
                        </div>
                        <div class="table-responsive course_list" id="course_list">
                            <table class="table eTable eTable-2 print-table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">{{ get_phrase('Title') }}</th>
                                        <th scope="col">{{ get_phrase('Icon') }}</th>
                                        <th scope="col">{{ get_phrase('Number of bundles') }}</th>
                                        <th class="print-d-none" scope="col">{{ get_phrase('Options') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bundle_categories as $key => $row)
                                        <tr>
                                            <th scope="row">
                                                <p class="row-number">{{ ++$key }}</p>
                                            </th>
                                            <td>
                                                <h4 class="title fs-14px">{{ $row->title }}</h4>
                                            </td>
                                            <td>
                                                <img src="{{get_image($row->icon)}}" width="40px" alt="">
                                            </td>
                                            <td>
                                                {{ $row->bundles->count() }} {{ get_phrase('Bundles') }}
                                            </td>
                                            <td class="print-d-none">
                                                <div class="dropdown ol-icon-dropdown ol-icon-dropdown-transparent">
                                                    <button class="btn ol-btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span class="fi-rr-menu-dots-vertical"></span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" onclick="showRightModal('{{ route('view', ['path' => 'admin.course_bundle_category.edit', 'id' => $row->id]) }}', '{{ get_phrase('Edit bundle category') }}')" href="#">{{ get_phrase('Edit') }}</a>
                                                        </li>

                                                        <li>
                                                            <a class="dropdown-item" onclick="confirmModal('{{ route('admin.course_bundle.category.delete', $row->id) }}')" href="javascript:void(0)">{{ get_phrase('Delete') }}</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        @include('admin.no_data')
                    @endif
                    <!-- Data info and Pagination -->
                    @if (count($bundle_categories) > 0)
                        <div class="admin-tInfo-pagi d-flex justify-content-between justify-content-center align-items-center flex-wrap gr-15">
                            <p class="admin-tInfo">
                                {{ get_phrase('Showing') . ' ' . count($bundle_categories) . ' ' . get_phrase('of') . ' ' . $bundle_categories->total() . ' ' . get_phrase('data') }}
                            </p>
                            {{ $bundle_categories->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection


@push('js')
@endpush
