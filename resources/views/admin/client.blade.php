@extends('admin.side')

@section('title', 'Double-K Computer')

@section('content')
<div class="main p-3">
    <div class="text">
        <h1 class="prod_title">User Information</h1>
    </div>
    <div class="container mt-7">
        <div class="row mb-4 align-items-center">
            <div class="col-md-8">
                <div class="input-group search-bar">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search..." aria-label="Search">
                    <button class="btn custom-btn" type="button" onclick="filterTable()">Search</button>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <button type="button" class="btn btn-custom" id="plus-button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    <i class="bi bi-plus"></i> Add User
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped custom-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee Name</th>
                        <th>Username</th>
                        <th>Job Role</th>
                        <th>Phone Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($clients->isEmpty())
                        <tr><td colspan="6">No Employee</td></tr>
                    @else
                        @foreach($clients as $client)
                            @if($client->archived == 0)  
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ ucwords(strtolower($client->fullname)) }}</td>
                                    <td>{{ $client->username }}</td>
                                    <td>{{ $client->jobtype == 0 ? 'Helper' : ($client->jobtype == 2 ? 'Staff' : 'Admin') }}</td>
                                    <td>{{ '+63 ' . substr($client->user_contact, 0, 3) . ' ' . substr($client->user_contact, 3, length: 3) . ' ' . substr($client->user_contact, 6) }}</td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 5px;">
                                            <button class="btn btn-success btn-sm" onclick="editClient('{{ $client->user_ID }}')">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm archive-btn" data-employeeid="{{ $client->user_ID }}">
                                                <i class="bi bi-archive"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Employee -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Add Employee</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="inventoryForm" action="{{ route('storeClient') }}" method="POST">
                    @csrf 
                    <div class="mb-3">
                        <label for="employeeName" class="form-label">Employee Name</label>
                        <input type="text" class="form-control" name="fullname" id="employeeName" placeholder="Enter employee name" required>
                    </div>
                    <div class="mb-3">
                        <label for="userName" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" id="userName" placeholder="Enter username" required>
                    </div>
                    <div class="mb-3">
                        <label for="jobRole" class="form-label">Job Role</label>
                        <select class="form-control" name="jobtype" required>
                            <option value="" disabled selected hidden>Job Role</option>
                            <option value="2">Staff</option>
                            <option value="0">Helper</option>
                        </select>  
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phoneNumber" class="form-label">Phone Number</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="countryCode" value="+63" readonly>
                                <input type="tel" class="form-control" name="user_contact" id="phoneNumber" placeholder="9123876394" pattern="[0-9]{10}" required>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="mb-1">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" placeholder="********"/>
                                <span class="input-group-text password-toggle" onclick="passVisib()">
                                    <i class="fa fa-fw fa-eye" id="eyeIcon"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" >Close</button>
                    <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Edit Employee -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Edit Employee</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editEmployeeForm" method="POST">
                    @csrf
                    <input type="hidden" id="editEmployeeId" name="id">
                    <div class="mb-3">
                        <label for="editEmployeeName" class="form-label">Employee Name</label>
                        <input type="text" class="form-control" id="editEmployeeName" name="fullname" required>
                    </div>
                    <div class="mb-3">
                        <label for="editUserName" class="form-label">Username</label>
                        <input type="text" class="form-control" id="editUserName" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="editJobRole" class="form-label">Job Role</label>
                        <select class="form-control" name="jobtype" id="editJobRole" required>
                            <option value="" disabled selected hidden>Job Role</option>
                            <option value="2">Staff</option>
                            <option value="0">Helper</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editPhoneNumber" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="editPhoneNumber" name="user_contact" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="clientData" value='@json($clients)'>
<script>
    $(document).ready(function() {
        $('.archive-btn').click(function() {
            var employeeID = $(this).data('employeeid'); 
            var row = $(this).closest('tr'); 

            Swal.fire({
                title: 'Employee Archiving',
                text: 'Are you sure to archive this employee?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Archive',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: `{{ url('/admin/employee/') }}/${employeeID}/archive`,
                        data: {
                            '_token': '{{ csrf_token() }}',
                        },
                        success: function(data) {
                            row.remove();
                            Swal.fire('Archived', 'Archived successfully', 'success');
                        },
                        error: function(data) {
                            console.error(data);
                            Swal.fire('Error!', data.responseJSON.message || 'There was an error archiving.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
