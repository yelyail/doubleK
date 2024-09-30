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

// for adding a product/services
$('#productModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var productId = button.data('id'); 
    var productName = button.data('name');
    var productCategory = button.data('category');
    var productDesc = button.data('desc');
    var productPrice = button.data('price');
    saveOrderSummary();

    var modal = $(this);
    modal.find('#modalProductName').text(productName);
    modal.find('#modalProductCategory').text(productCategory);
    modal.find('#modalProductDesc').text(productDesc);
    modal.find('#modalProductPrice').text(productPrice);
    modal.find('#product_id').val(productId);
    
    $('#addProductButton').off('click').on('click', function() {
        const quantity = parseInt($('#quantity').val());
        const totalPrice = parseFloat(productPrice) * quantity;

        const orderSummaryBody = document.getElementById('orderSummaryBody');

        let existingRow = Array.from(orderSummaryBody.rows).find(row => row.cells[0].innerText === productName);
        if (existingRow) {
            let existingQuantity = parseInt(existingRow.cells[1].innerText);
            existingRow.cells[1].innerText = existingQuantity + quantity;
            existingRow.cells[3].innerText = `₱ ${(parseFloat(existingRow.cells[3].innerText.replace('₱ ', '').replace(',', '')) + totalPrice).toFixed(2)}`;
        } else {
            const newRow = orderSummaryBody.insertRow();
            newRow.innerHTML = `
                <td>${productName}</td>
                <td class="text-center">${quantity}</td>
                <td>₱ ${parseFloat(productPrice).toFixed(2)}</td>
                <td>₱ ${totalPrice.toFixed(2)}</td>
                <td><button class="btn btn-danger btn-sm remove-product"><i class="bi bi-x-circle"></i></button></td>
            `;

            newRow.querySelector('.remove-product').addEventListener('click', function() {
                orderSummaryBody.deleteRow(newRow.rowIndex - 1); 
                updateTotalAmount(); 
            });
        }
        updateTotalAmount();
        $('#productModal').modal('hide');
    });
});

document.querySelectorAll('.add-service').forEach(button => {
    button.addEventListener('click', function() {
        const serviceName = this.getAttribute('data-name');
        const serviceFee = parseFloat(this.getAttribute('data-fee'));

        const orderSummaryBody = document.getElementById('orderSummaryBody');

        let existingRow = Array.from(orderSummaryBody.rows).find(row => row.cells[0].innerText === serviceName);
        if (existingRow) {
            let existingQuantity = parseInt(existingRow.cells[1].innerText);
            existingRow.cells[1].innerText = existingQuantity + 1;
            existingRow.cells[3].innerText = `₱ ${(parseFloat(existingRow.cells[3].innerText.replace('₱ ', '').replace(',', '')) + serviceFee).toFixed(2)}`;
        } else {
            const newRow = orderSummaryBody.insertRow();
            newRow.innerHTML = `
                <td>${serviceName}</td>
                <td class="text-center">1</td>
                <td>₱ ${serviceFee.toFixed(2)}</td>
                <td>₱ ${serviceFee.toFixed(2)}</td>
                <td><button class="btn btn-danger btn-sm remove-service"><i class="bi bi-x-circle"></i></button></td>
            `;

            newRow.querySelector('.remove-service').addEventListener('click', function() {
                orderSummaryBody.deleteRow(newRow.rowIndex - 1); 
                updateTotalAmount(); 
            });
        }

        updateTotalAmount();
    });
});
