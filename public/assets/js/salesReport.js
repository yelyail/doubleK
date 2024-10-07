// Attach keyup event listener to the search input for filtering the table based on search terms
document.getElementById('searchInput').addEventListener('keyup', filterTable);

document.getElementById('dateFilterForm').addEventListener('submit', function (e) {
    e.preventDefault();
    filterTable(); // Trigger the combined filtering function
});
document.getElementById('payment_method_filter').addEventListener('change', filterTable);

function filterTable() {
    let searchInput = document.getElementById('searchInput').value.toLowerCase();
    let selectedPaymentMethod = document.getElementById('payment_method_filter').value.toLowerCase();
    
    let fromDate = new Date(document.getElementById('from_date').value);
    let toDate = new Date(document.getElementById('to_date').value);

    let table = document.querySelector('.cstm-table');
    let tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        let td = tr[i].getElementsByTagName('td');
        let found = false;
        let showRow = true;

        if ((td[0] && td[0].textContent.toLowerCase().indexOf(searchInput) > -1) || 
            (td[1] && td[1].textContent.toLowerCase().indexOf(searchInput) > -1)) {
            found = true;
        }
        if (!found) {
            showRow = false;
        }
        let tdPaymentMethod = td[6]; 
        if (tdPaymentMethod) {
            let paymentMethodText = tdPaymentMethod.textContent.toLowerCase();
            if (selectedPaymentMethod !== "" && paymentMethodText !== selectedPaymentMethod) {
                showRow = false;
            }
        }
        let tdDate = td[8]; 
        if (tdDate) {
            let rowDate = new Date(tdDate.textContent.trim());
            if ((!isNaN(fromDate.getTime()) && rowDate < fromDate) || (!isNaN(toDate.getTime()) && rowDate > toDate)) {
                showRow = false;
            }
        }
        tr[i].style.display = showRow ? '' : 'none';
    }
}

function toggleFilter() {
    var filterDropdown = document.getElementById("payment_method_filter");
    if (filterDropdown.style.display === "none" || filterDropdown.style.display === "") {
        filterDropdown.style.display = "block";
    } else {
        filterDropdown.style.display = "none";
    }
}
