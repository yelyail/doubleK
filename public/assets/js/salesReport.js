
document.getElementById('searchInput').addEventListener('keyup', filterTable);
document.getElementById('payment_method_filter').addEventListener('change', filterTable);
document.getElementById('filterButton').addEventListener('click', filterTable); 

function filterTable() {
    let searchInput = document.getElementById('searchInput').value.toLowerCase();
    let selectedPaymentMethod = document.getElementById('payment_method_filter').value.toLowerCase().trim(); // Use trim() for selected payment method

    let fromDate = document.getElementById('from_date').value ? new Date(document.getElementById('from_date').value) : null;
    let toDate = document.getElementById('to_date').value ? new Date(document.getElementById('to_date').value) : null;

    let table = document.querySelector('.cstm-table');
    let tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        let td = tr[i].getElementsByTagName('td');
        let showRow = true;

        if ((td[0] && td[0].textContent.toLowerCase().indexOf(searchInput) === -1) && 
            (td[1] && td[1].textContent.toLowerCase().indexOf(searchInput) === -1)) {
            showRow = false;
        }
        let tdPaymentType = td[5];
        if (tdPaymentType) {
            let paymentTypeText = tdPaymentType.textContent.toLowerCase().trim(); 
            if (selectedPaymentMethod && paymentTypeText !== selectedPaymentMethod) {
                showRow = false;
            }
        }
        let tdDate = td[7];
        if (tdDate) {
            let rowDate = new Date(tdDate.textContent.trim());
            if ((fromDate && rowDate < fromDate) || (toDate && rowDate > toDate)) {
                showRow = false;
            }
        }
        
        tr[i].style.display = showRow ? '' : 'none';
    }
}

function toggleFilter() {
    var filterDropdown = document.getElementById("payment_method_filter");
    filterDropdown.style.display = (filterDropdown.style.display === "none" || filterDropdown.style.display === "") ? "block" : "none";
}


function filterSalesReport(event) {
    event.preventDefault(); 
    
    const fromDate = document.getElementById('from_date').value;
    const toDate = document.getElementById('to_date').value;

    fetch(`{{ route('generateSalesReport') }}?from_date=${fromDate}&to_date=${toDate}`)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('tbody');
            tableBody.innerHTML = ''; 
            
            data.orderReceipts.forEach(orderReceipt => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${orderReceipt.customer_name}</td>
                    <td>${orderReceipt.particulars}</td>
                    <td>${orderReceipt.quantity_ordered}</td>
                    <td>₱ ${parseFloat(orderReceipt.unit_price).toFixed(2)}</td>
                    <td>₱ ${parseFloat(orderReceipt.payment).toFixed(2)}</td>
                    <td>${orderReceipt.payment_type || 'N/A'}</td>
                    <td>${orderReceipt.reference_num || 'N/A'}</td>
                    <td>${orderReceipt.order_date}</td>
                    <td>${orderReceipt.warranty}</td>
                    <td>${orderReceipt.sales_recipient}</td>
                    <td>
                        <button type="button" class="btn btn-outline-secondary btn-repair" 
                                ${orderReceipt.particulars && orderReceipt.warranty > 0 ? '' : 'disabled'}>
                            Request Repair
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error fetching filtered data:', error);
        });
}

function downloadReport() {
    const fromDate = document.getElementById('from_date').value;
    const toDate = document.getElementById('to_date').value;
    
    if (!fromDate || !toDate) {
        Swal.fire({
            title: 'Missing Date',
            text: 'Please select both "From" and "To" dates for generating the report.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return; 
    }
    let checkUrl = `{{ route('generateSalesReport') }}?download=true&from_date=${fromDate}&to_date=${toDate}`;
    fetch(checkUrl)
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    if (err.error) {
                        Swal.fire({
                            title: 'No Records Found',
                            text: err.error,
                            icon: 'info',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
            window.location.href = checkUrl;
        })
        .catch(err => {
            console.error('Error:', err);
            Swal.fire({
                title: 'Error',
                text: 'An error occurred while generating the report. Please try again later.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
}

function showTransferAlert(ordDet_ID) {
    Swal.fire({
        title: "Requesting Repair",
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: "Yes",
        denyButtonText: "No"
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Reasons for Requesting a Repair',
                input: 'select',
                inputOptions: {
                    'Defective Hardware Components': 'Defective Hardware Components',
                    'Incompatibility with Other Components': 'Incompatibility with Other Components',
                    'Overheating or Performance Degradation': 'Overheating or Performance Degradation',
                    'Others': 'Others'
                },
                inputPlaceholder: 'Select reason',
                confirmButtonText: 'Confirm',
                showCancelButton: true,
                cancelButtonText: 'Cancel'
            }).then((reason) => {
                if (reason.isConfirmed) {
                    $.ajax({
                        url: '/admin/return',  
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',  
                            ordDet_ID: ordDet_ID,
                            reason: reason.value
                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Requesting Repair Confirmed",
                                text: response.success,
                                icon: "success"
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: "Error",
                                text: xhr.responseJSON.error || "There was an issue submitting the request.",
                                icon: "error"
                            });
                        }
                    });
                } else {
                    Swal.fire("Requesting Repair has been canceled", "", "info");
                }
            });
        } else if (result.isDenied) {
            Swal.fire("Requesting Repair has been canceled", "", "info");
        }
    });
}
