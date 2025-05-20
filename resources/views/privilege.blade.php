@extends('layouts.app')

@section('header', 'الصلاحيات')

@section('content')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <!--**********************************
                    Content body start
                ***********************************-->
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">صلاحيات الأدمن</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table header-border table-responsive-sm">
                                <thead>
                                    <tr>
                                        @foreach ($privileges as $privilege)
                                            <th>{{ $privilege->name }}</th>
                                        @endforeach
                                        <th>اسم الأدمن</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($admins as $admin)
                                        <tr>
                                            @php
                                                $adminPrivilegeIds = $admin->privileges->pluck('id')->toArray();
                                            @endphp

                                            @foreach ($privileges as $privilege)
                                                <td>
                                                    <input type="checkbox" class="form-check-input toggle-privilege"
                                                        data-admin-id="{{ $admin->id }}"
                                                        data-privilege-id="{{ $privilege->id }}"
                                                        @if (in_array($privilege->id, $adminPrivilegeIds)) checked @endif>
                                                </td>
                                            @endforeach

                                            <td>{{ $admin->full_name }}</td>
                                            <td><strong>{{ $loop->iteration }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div><!-- /.table-responsive -->
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div>
        </div>
    </div>
    <!--**********************************
                    Content body end
                ***********************************-->

@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.toggle-privilege').on('change', function() {
                const $checkbox = $(this); // Store reference to checkbox
                const adminId = $checkbox.data('admin-id');
                const privilegeId = $checkbox.data('privilege-id');
                const isChecked = $checkbox.is(':checked');

                $.ajax({
                    url: "{{ route('checkPrivilege') }}",
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        admin_id: adminId,
                        privilege_id: privilegeId,
                        status: isChecked
                    },
                    success: function(response) {
                        if (response.success) {
                            // Optional: Add success message
                            // toastr.success('Privilege updated successfully');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);

                        // Revert checkbox using stored reference
                        $checkbox.prop('checked', !isChecked);

                        // Show error message
                        alert('Failed to update privilege. Please try again.');
                    }
                });
            });
        });
    </script>
@endsection
