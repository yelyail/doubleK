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

// for progress bar
document.addEventListener('DOMContentLoaded', function () {
    const progressLine = document.getElementById('progressLine');
    const steps = document.querySelectorAll('.progress-step');

    function setInitialProgress() {
        steps.forEach((step) => {
            if (step.classList.contains('active')) {
                progressLine.style.width = `20%`;
            }
        });
    }

    window.navigateTo = function(page, index) {
        steps.forEach((step, idx) => {
            if (idx <= index) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });

        progressLine.style.width = `${((index + 1) / steps.length) * 100}%`;
        window.location.href = page;
    };

    steps.forEach((step, index) => {
        step.onclick = function() {
            if (index === 0 || steps[index - 1].classList.contains('active')) {
                navigateTo(step.dataset.route, index);
            }
        };
    });

    setInitialProgress();
}); 