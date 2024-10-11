document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('searchInput').addEventListener('keyup', filterTable);
    document.getElementById('payment_method_filter').addEventListener('change', filterTable);
    document.getElementById('filter-button').addEventListener('click', filterTable); 
    function filterTable() {
        let table = document.querySelector('.cstm-table');
        let searchInput = document.getElementById('searchInput').value.toLowerCase();
        let fromDate = document.getElementById('from_date').value ? new Date(document.getElementById('from_date').value) : null;
        let toDate = document.getElementById('to_date').value ? new Date(document.getElementById('to_date').value) : null;
        let selectedPaymentMethod = document.getElementById('payment_method_filter').value.toLowerCase().trim(); 
        let tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            let td = tr[i].getElementsByTagName('td');
            let showRow = true;

            // Search input filtering
            if ((td[0] && td[0].textContent.toLowerCase().indexOf(searchInput) === -1) && 
                (td[1] && td[1].textContent.toLowerCase().indexOf(searchInput) === -1)) {
                showRow = false;
            }

            // Payment method filteringz
            let tdPaymentType = td[5];
            if (tdPaymentType) {
                let paymentTypeText = tdPaymentType.textContent.toLowerCase().trim(); 
                if (selectedPaymentMethod && paymentTypeText !== selectedPaymentMethod) {
                    showRow = false;
                }
            }
            // Date filtering
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
    function generateSalesReport() {
        let fromDate = document.getElementById('from_date').value;
        let toDate = document.getElementById('to_date').value;

        if (fromDate && toDate) {
            let params = new URLSearchParams({
                from_date: fromDate,
                to_date: toDate
            });
            window.location.href = "{{ route('generateSalesReport') }}?from_date=" + fromDate + "&to_date=" + toDate + "&download=true";
        } else {
            alert("Please select a valid date range before generating the report.");
        }
    }
    document.getElementById('plus-button').addEventListener('click', generateInventoryReport);
});

function toggleFilter() {
    var filterDropdown = document.getElementById("payment_method_filter");
    filterDropdown.style.display = (filterDropdown.style.display === "none" || filterDropdown.style.display === "") ? "block" : "none";
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
                if (reason.isConfirmed && reason.value) {
                    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    $.ajax({
                        url: '/admin/return',
                        method: 'POST',
                        data: {
                            _token: token,
                            ordDet_ID: ordDet_ID,
                            reason: reason.value
                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Requesting Repair Confirmed",
                                text: response.success,
                                icon: "success"
                            }).then(() => {
                                document.getElementById('repair-btn-' + ordDet_ID).style.display = 'none';
                                document.getElementById('ongoing-btn-' + ordDet_ID).style.display = 'block';
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: "Error",
                                text: xhr.responseJSON?.error || "There was an issue submitting the request.",
                                icon: "error"
                            });
                        }
                    });
                } else if (!reason.value) {
                    Swal.fire("You must select a reason for the repair request.", "", "warning");
                } else {
                    Swal.fire("Requesting Repair has been canceled", "", "info");
                }
            });
        } else if (result.isDenied) {
            Swal.fire("Requesting Repair has been canceled", "", "info");
        }
    });
}
