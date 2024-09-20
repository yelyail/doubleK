@extends('admin.side')

@section('title', 'Double-K Computer')

@section('content')
<div class="main p-3">
    <div class="text">
        <h1>Sales Transaction</h1>
    </div>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="row mb-3 align-items-center">
                        <div class="container mt-4">
                            <div class="progress-container">
                                <div class="progress-line"></div>
                                <div class="progress-line-active" id="progressLine" style="width: 0%; left: 0;"></div>

                                <div class="progress-step active" data-route="{{ route('adminOrder') }}" data-index="0">
                                    <div class="step-icon"><i class="bi bi-cart"></i></div>
                                    <div class="progress-label">Shopping</div>
                                </div>

                                <div class="progress-step active" data-route="{{ route('custInfo') }}" data-index="1">
                                    <div class="step-icon"><i class="bi bi-person"></i></div>
                                    <div class="progress-label">Customer Information</div>
                                </div>

                                <div class="progress-step" data-route="{{ route('confirm') }}" data-index="3">
                                    <div class="step-icon"><i class="bi bi-check-circle"></i></div>
                                    <div class="progress-label">Preview & Confirm</div>
                                </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="input-group search-bar mt-3">
                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                <input type="text" id="searchInput" class="form-control" placeholder="Search..." aria-label="Search">
                                <button class="btn custom-btn" type="button" onclick="filterTable()">Search</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Filters</h6>
                            <select class="form-select" id="categoryfilter">
                                <option value="">Select Category</option>
                                <option value="Monitor">Monitor</option>
                                <option value="System Unit">System Unit</option>
                                <option value="Cables">Cables</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped custom-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category Name</th>
                                    <th>Product Name</th>
                                    <th>Stocks</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody id="inventoryTableBody">
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- for the progress bar -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const progressLine = document.getElementById('progressLine');
        const steps = document.querySelectorAll('.progress-step');

        function setInitialProgress() {
            steps.forEach((step) => {
                if (step.classList.contains('active')) {
                    progressLine.style.width = `20%`;
                }
            });
        }

        window.navigateTo = function(page, index) {
            steps.forEach((step, idx) => {
                if (idx <= index) {
                    step.classList.add('active');
                } else {
                    step.classList.remove('active');
                }
            });

            progressLine.style.width = `${((index + 1) / steps.length) * 100}%`;
            window.location.href = page;
        };

        steps.forEach((step, index) => {
            step.onclick = function() {
                if (index === 0 || steps[index - 1].classList.contains('active')) {
                    navigateTo(step.dataset.route, index);
                }
            };
        });

        setInitialProgress();
    });
</script>
@endsection

