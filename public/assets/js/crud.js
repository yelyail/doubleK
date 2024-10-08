
document.addEventListener('DOMContentLoaded', function() {
    const clientData = JSON.parse(document.getElementById('clientData').value);

    window.editClient = function(userId) {
        const client = clientData.find(client => client.user_ID == userId);

        if (client) {
            populateEditForm(client);
            showEditModal(userId);
        }
    };
    function populateEditForm(client) {
        document.getElementById('editEmployeeId').value = client.user_ID;
        document.getElementById('editEmployeeName').value = client.fullname;
        document.getElementById('editUserName').value = client.username;
        document.getElementById('editPhoneNumber').value = client.user_contact;
        document.getElementById('editJobRole').value = client.jobtitle; // Ensure this matches your job roles
    }

    function showEditModal(userId) {
        const editModal = new bootstrap.Modal(document.getElementById('editEmployeeModal'));
        editModal.show();
        document.getElementById('editEmployeeForm').action = `/admin/employee/${userId}/update`;
    }
});


//for the delivery option
document.getElementById("deliveryMethod").addEventListener("change", function() {
    var deliveryDateInput = document.getElementById("deliverDate");
    if (this.value === "deliver") {
        deliveryDateInput.style.display = "block";
    } else {
        deliveryDateInput.style.display = "none";
    }
});

document.getElementById('paymentMethod').addEventListener('change', function() {
    const cashInput = document.getElementById('cashAmountInput');
    const gcashInput = document.getElementById('gcashDetailsInput');
    const bankTransferInput = document.getElementById('bankTransferDetails');

    cashInput.style.display = 'none';
    gcashInput.style.display = 'none';
    bankTransferInput.style.display = 'none';

    switch (this.value) {
        case 'cash':
            cashInput.style.display = 'block';
            break;
        case 'gcash':
            gcashInput.style.display = 'block';
            break;
        case 'banktransfer':
            bankTransferInput.style.display = 'block';
            break;
    }
});


