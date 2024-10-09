
document.getElementById('searchInput').addEventListener('keyup', filterTable);
document.getElementById('payment_method_filter').addEventListener('change', filterTable);
document.getElementById('filterButton').addEventListener('click', filterTable); // Add click event to filter button

// Function to filter the table
function filterTable() {
    let searchInput = document.getElementById('searchInput').value.toLowerCase();
    let selectedPaymentMethod = document.getElementById('payment_method_filter').value.toLowerCase();

    let fromDate = document.getElementById('from_date').value ? new Date(document.getElementById('from_date').value) : null;
    let toDate = document.getElementById('to_date').value ? new Date(document.getElementById('to_date').value) : null;

    let table = document.querySelector('.cstm-table');
    let tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        let td = tr[i].getElementsByTagName('td');
        let showRow = true;

        // Check Customer Name and Particulars (td[0] and td[1])
        if ((td[0] && td[0].textContent.toLowerCase().indexOf(searchInput) === -1) && 
            (td[1] && td[1].textContent.toLowerCase().indexOf(searchInput) === -1)) {
            showRow = false;
        }
        let tdPaymentType = td[5];
        if (tdPaymentType) {
            let paymentTypeText = tdPaymentType.textContent.toLowerCase();
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

