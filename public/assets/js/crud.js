
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
        document.getElementById('editJobRole').value = client.jobtype; // Ensure this matches your job roles
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

//for cash
document.getElementById("paymentMethod").addEventListener("change", function() {
    var cashAmountInput = document.getElementById("cashAmountInput");
    if (this.value === "cash") {
        cashAmountInput.style.display = "block";
    } else {
        cashAmountInput.style.display = "none";
    }
});

//for gcash
document.getElementById("paymentMethod").addEventListener("change", function() {
    var gcashDetailsInput = document.getElementById("gcashDetailsInput");
    if (this.value === "gcash") {
        gcashDetailsInput.style.display = "block";
    } else {
        gcashDetailsInput.style.display = "none";
    }
});

//for banktransfer
document.getElementById("paymentMethod").addEventListener("change", function() {
    var banktransfer = document.getElementById("bankTransferDetails");
    if (this.value === "banktransfer") {
        banktransfer.style.display = "block";
    } else {
        banktransfer.style.display = "none";
    }
});

