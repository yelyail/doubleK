@extends('user.side')

@section('title', 'Double-K Computer')

@section('content')
            <div class="main p-3">
                <div class="row mb-4 align-items-center">
                    <div class="col-md-9">
                        <h1 class="prod_title">Inventory Reports</h1>
                    </div>
                    <div class="col-md-3 text-end">
                        <button type="button" class="btn btn-custom" id="plus-button" style="border-radius: 7px; height: 2.3rem; border: none;">
                            <i class="bi bi-printer"></i> Generate Reports
                        </button>
                    </div>
                </div>
                <div class="container mt-4">
                    <div class="row mb-2 align-items-center">
                        <div class="col-md-6 mt-4">
                            <div class="input-group search-bar">
                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                <input type="text" id="searchInput" class="form-control" placeholder="Search..." aria-label="Search">
                                <button class="btn custom-btn" type="button" onclick="filterTable()">Search</button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_filter"><b>Filter by Date:</b></label>
                                    <form id="dateFilterForm">
                                        <div class="input-group md-3">
                                            <span class="input-group-text"><b>From</b></span>
                                            <input type="date" id="from_date" name="from_date" class="form-control me-2" placeholder="From Date">
                                            <span class="input-group-text"><b>To</b></span>
                                            <input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date">
                                            <button type="submit" class="btn btn-custom">Filter</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="table-responsive">
                        <table class="table table-striped custom-table">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Stock In</th>
                                    <th>Quantity Sold</th>
                                    <th>Last Restock Date</th>
                                    <th>Return</th>
                                    <th>Supplier Name</th>
                                    <th>Print</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><button type="submit" class="btn btn-success"><i class="bi bi-printer"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('dateFilterForm').addEventListener('submit', function (e) {
            e.preventDefault(); 

            let fromDate = new Date(document.getElementById('from_date').value);
            let toDate = new Date(document.getElementById('to_date').value);
            let table = document.querySelector('.custom-table');
            let tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                let tdDate = tr[i].getElementsByTagName('td')[6]; 
                if (tdDate) {
                    let rowDate = new Date(tdDate.textContent.trim());

                    if ((!isNaN(fromDate.getTime()) && rowDate < fromDate) || (!isNaN(toDate.getTime()) && rowDate > toDate)) {
                        tr[i].style.display = 'none';
                    } else {
                        tr[i].style.display = '';
                    }
                }
            }
        });
    </script>
@endsection
