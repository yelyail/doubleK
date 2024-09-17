// for the invetory
function toggleStatus(button) {
    if (button.textContent === "Available") {
        button.textContent = "Unavailable";
        button.classList.remove("btn-success");
        button.classList.add("btn-danger");
    } else {
        button.textContent = "Available";
        button.classList.remove("btn-danger");
        button.classList.add("btn-success");
    }
}
//for reservation
function reserveStat(button) {
    switch (button.textContent) {
        case "Pending":
            button.textContent = "Cancel";
            button.classList.remove("btn-warning");
            button.classList.add("btn-danger");
            break;
        case "Complete":
            button.textContent = "Pending";
            button.classList.remove("btn-success");
            button.classList.add("btn-warning");
            break;
        case "Cancel":
            button.textContent = "Complete";
            button.classList.remove("btn-danger");
            button.classList.add("btn-success");
    }
}
//for order to change quantity
function changeQuantity(button, change) {
    const input = button.parentElement.querySelector('input[type="number"]');
    let currentQuantity = parseInt(input.value);
    
    const newQuantity = currentQuantity + change;
    if (newQuantity >= 1) {
        input.value = newQuantity;
    }
}
//for resetting the quantity to 0
function resetQuantities() {
    const quantityInputs = document.querySelectorAll('#inventoryTableBody input[type="number"]');
        quantityInputs.forEach(input => {
        input.value = 0; 
    });
}

//for the password visibility
function passVisib() {
    var passwordField = document.getElementById("password");
    var toggleIcon = document.querySelector(".password-toggle");
    
    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleIcon.classList.remove("fa-eye");
        toggleIcon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        toggleIcon.classList.remove("fa-eye-slash");
        toggleIcon.classList.add("fa-eye");
    }
}