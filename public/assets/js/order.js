// for filter 
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById("categoryfilter").addEventListener("change", function() {
        var productTable = document.getElementById("productTable");
        var serviceInput = document.getElementById("serviceInput");
        var custDebtInput = document.getElementById("custDebtInput");

        productTable.style.display = "none";
        serviceInput.style.display = "none";
        custDebtInput.style.display="none";

        if (this.value === "product") {
            productTable.style.display = "block";
        } else if (this.value === "services") {
            serviceInput.style.display = "block";
        }else if (this.value === "reservation") {
            reservationInput.style.display = "block";
        }else if (this.value === "custDebt") {
            custDebtInput.style.display = "block";
        }
    });

    document.getElementById("categoryfilter").dispatchEvent(new Event("change"));
});
