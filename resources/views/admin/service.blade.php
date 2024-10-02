
@extends('admin.side')

@section('title', 'Double-K Computer')

@section('content')
<div class="main p-3">
    <div class="text">
        <h1 class="prod_title">Services</h1>
    </div>
    <div class="container mt-5">
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
                <button type="button" class="btn btn-custom" id="plus-button" style="border-radius: 7px; height: 2.3rem; border: none;" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    <i class="bi bi-plus"></i> Add Service
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped custom-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Service Name</th>
                        <th>Description</th>
                        <th>Service Fee</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($services->isEmpty())
                        <td colspan="8" class="text-center">No Services Available.</td>
                    @else
                        @foreach ($services as $service)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ ucwords(strtolower($service->service_name)) }}</td>
                                <td>{{ ucwords(strtolower($service->description)) }}</td>
                                <td>₱ {{ number_format($service->service_fee,2) }}</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 5px;">
                                        @if($service->service_status == 0) 
                                                <button class="btn btn-success btn-sm" onclick="editService('{{$service->service_ID}}')">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm archive-btn" data-serviceID="{{ $service->service_ID }}">
                                                    <i class="bi bi-archive"></i>
                                                </button>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Adding a Service -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Service</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="inventoryForm" action="{{ route('storeService') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="servicesName" class="form-label">Service Name</label>
                        <input type="text" class="form-control" id="servicesName" name="serviceName" placeholder="Enter service name">
                    </div>
                    <div class="mb-3">
                        <label for="serviceDesc" class="form-label">Description</label>
                        <textarea class="form-control" id="serviceDesc" rows="3" name="description" placeholder="Enter a description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="serviceFee" class="form-label">Service Fee</label>
                        <input type="text" class="form-control" id="serviceFee" name="serviceFee" placeholder="Enter price">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit " class="btn btn-success" id="saveInventory">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Editing services -->
<div class="modal fade" id="editServiceModal" tabindex="-1" aria-labelledby="editServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Edit Service</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editServiceform" method="POST" action="{{ route('updateService', ['id' => 'service_ID']) }}">
                @csrf
                    <input type="hidden" id="editServiceId" name="id">
                    <div class="mb-3">
                        <label for="editServiceName" class="form-label">Service Name</label>
                        <input type="text" class="form-control" id="editServiceName" name="service_name" required> 
                    </div>
                    <div class="mb-3">
                        <label for="editServiceDesc" class="form-label">Description</label>
                        <textarea class="form-control" id="editServiceDesc" rows="3" name="description" placeholder="Enter a description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editServiceFee" class="form-label">Service Fee</label>
                        <input type="text" class="form-control" id="editServiceFee" name="service_fee" placeholder="Enter price">
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
<input type="hidden" id="serviceData" value='@json($services)'>
<script>
    function editService(serviceID) {
        $.ajax({
            url: '/admin/service/' + serviceID + '/editServices', 
            type: 'GET',
            success: function (data) {
                $('#editServiceId').val(data.service_ID);
                $('#editServiceName').val(data.service_name);
                $('#editServiceDesc').val(data.description);
                $('#editServiceFee').val(data.service_fee);

                $('#editServiceModal').modal('show');
            },
            error: function () {
                alert('Error fetching supplier data');
            }
        });
    }
</script>
<script>
    $(document).ready(function() {
        $('.archive-btn').click(function() {
            var serviceID = $(this).data('serviceid'); 
            var row = $(this).closest('tr');

            Swal.fire({
                title: 'Services Archiving',
                text: 'Are you sure to archive this service?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Archive',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: `{{ url('/admin/service/') }}/${serviceID}/archive`,  // Corrected URL to use 'serviceID'
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
