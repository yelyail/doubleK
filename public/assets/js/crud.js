
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

