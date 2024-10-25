document.addEventListener('DOMContentLoaded', function() {
    window.filterCategory = function() {
            const category = document.getElementById('categoryfilter').value;
            document.getElementById('productTable').style.display = category === 'product' ? 'block' : 'none';
            document.getElementById('serviceInput').style.display = category === 'services' ? 'block' : 'none';
            document.getElementById('reserve').style.display = category === 'custDebt' ? 'block' : 'none';
        };
        filterCategory();
        $('#productModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var productId = button.attr('data-item-id');
            var productName = button.attr('data-name');
            var productCategory = button.attr('data-category');
            var productDesc = button.attr('data-desc');
            
            var productPrice = button.attr('data-price'); // Retrieve using .attr()
        
            var modal = $(this);
            modal.find('#modalProductName').text(productName);
            modal.find('#modalProductCategory').text(productCategory);
            modal.find('#modalProductDesc').text(productDesc);
            modal.find('#modalProductPrice').text(parseFloat(productPrice).toFixed(2));
            modal.find('#modalProductId').val(productId); 
        });
        $('#addProductButton').on('click', function() {
            const productName = $('#modalProductName').text().trim(); // Trim whitespace
            const productPrice = parseFloat($('#modalProductPrice').text());
            const quantity = parseInt($('#quantity').val());
            const productId = $('#modalProductId').val(); 
            if (isNaN(quantity) || quantity < 1) {
                alert("Please enter a valid quantity.");
                return;
            }

            const totalPrice = productPrice * quantity;
            const orderSummaryBody = document.getElementById('orderSummaryBody1');
            let existingRow = Array.from(orderSummaryBody.rows).find(row => 
                row.cells[0].innerText === productName &&
                parseFloat(row.cells[2].innerText.replace('₱ ', '').replace(',', '')) === productPrice
            );

            if (existingRow) {
                let existingQuantity = parseInt(existingRow.cells[1].innerText);
                existingRow.cells[1].innerText = existingQuantity + quantity;
                existingRow.cells[3].innerText = `₱ ${(parseFloat(existingRow.cells[3].innerText.replace('₱ ', '').replace(',', '')) + totalPrice).toFixed(2)}`;
            } else {
                const newRow = orderSummaryBody.insertRow();
                newRow.setAttribute('data-item-id', productId);  
                newRow.setAttribute('data-item-type', 'product');

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
            toggleNextButton();
            $('#productModal').modal('hide');
        });
        // Add Service to Order Summary
        document.querySelectorAll('.add-service').forEach(button => {
            button.addEventListener('click', function() {
                const serviceName = button.getAttribute('data-name');
                const serviceFee = parseFloat(button.getAttribute('data-fee')).toFixed(2);
                const serviceId = button.getAttribute('data-item-id'); 
                const orderSummaryBody = document.getElementById('orderSummaryBody1');
        
                // Check if the service already exists in the order summary
                let existingRow = Array.from(orderSummaryBody.rows).find(row =>
                    row.cells[0].innerText === serviceName &&
                    row.cells[2].innerText === '₱ ' + serviceFee
                );
        
                if (existingRow) {
                    let existingQuantity = parseInt(existingRow.cells[1].innerText);
                    existingRow.cells[1].innerText = existingQuantity + 1;
                    existingRow.cells[3].innerText = `₱ ${(parseFloat(existingRow.cells[3].innerText.replace('₱ ', '').replace(',', '')) + parseFloat(serviceFee)).toFixed(2)}`;
                } else {
                    const newRow = orderSummaryBody.insertRow();
                    newRow.setAttribute('data-item-id', serviceId);  
                    newRow.setAttribute('data-item-type', 'service');
        
                    newRow.innerHTML = `
                        <td>${serviceName}</td>
                        <td class="text-center">1</td>
                        <td>₱ ${serviceFee}</td>
                        <td>₱ ${serviceFee}</td>
                        <td><button class="btn btn-danger btn-sm remove-product"><i class="bi bi-x-circle"></i></button></td>
                    `;
                    newRow.querySelector('.remove-product').addEventListener('click', function() {
                        orderSummaryBody.deleteRow(newRow.rowIndex - 1);
                        updateTotalAmount();
                    });
                }
                updateTotalAmount();
                toggleNextButton();
            });
        });
        // Function to Update Total Amount
        let overallTotal = 0;

        function updateTotalAmount() {
            const orderSummaryBody = document.getElementById('orderSummaryBody1');
            overallTotal = 0; 
            for (let row of orderSummaryBody.rows) {
                overallTotal += parseFloat(row.cells[3].innerText.replace('₱ ', ''));
            }
            document.getElementById('totalAmount').innerText = '₱ ' + overallTotal.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('totalAmount1').innerText = '₱ ' + overallTotal.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
        function toggleNextButton() {
            const orderSummaryBody = document.getElementById('orderSummaryBody1');
            const nextButton = document.getElementById('nextToCustomerInfo');
            nextButton.disabled = orderSummaryBody.rows.length === 0;
        }
        // Steps step 1--> step2
        document.getElementById('nextToCustomerInfo').addEventListener('click', function() {
            showStep(2);
        });
        // step 2 --> step1
        document.getElementById('backToOrder').addEventListener('click', function() {
            showStep(1);
        });
        // step 3 --> step 2
        document.getElementById('backToCustomerInfo').addEventListener('click', function() {
            showStep(2);
        });

        function showStep(stepNumber) {
            const steps = document.querySelectorAll('.step');
            
            steps.forEach(step => {
                step.classList.remove('active'); 
                step.style.display = 'none';
            });

            const currentStep = document.querySelector('.step-' + stepNumber);
            if (currentStep) {
            currentStep.classList.add('active');
                currentStep.style.display = 'block'; 
            }
            updateProgressBar(stepNumber);
        }

        function updateProgressBar(stepNumber) {
            const progressLine = document.getElementById('progressLine');
            const progressSteps = document.querySelectorAll('.progress-step');
            
            progressSteps.forEach((step, index) => {
                if (index < stepNumber) {
                    step.classList.add('active');
                } else {
                    step.classList.remove('active');
                }
            });
            const progressWidth = ((stepNumber - 1) / (progressSteps.length - 1)) * 100;
            progressLine.style.width = progressWidth + '%';
        }

        // For storing data in step 2 customer Information
        document.getElementById('confirmPay').addEventListener('click', function(event) {
            event.preventDefault(); 
            populateConfirmation(); 
            showStep(3);
        });

        // Populate confirmation
        function populateConfirmation() {
            // Get input values
            const custNameInput = document.getElementById('custName').value || 'N/A';
            const addressInput = document.getElementById('address').value || 'N/A';
            const deliveryMethodSelect = document.getElementById('deliveryMethod');
            const deliveryMethod = deliveryMethodSelect.options[deliveryMethodSelect.selectedIndex]?.text || 'N/A';
            const deliveryDateInput = document.getElementById('deliveryDate').value || 'N/A';
            const paymentMethodSelect = document.getElementById('paymentMethod');
            const paymentMethod = paymentMethodSelect.options[paymentMethodSelect.selectedIndex]?.text || 'N/A';
        
            // Function to get the current date in the desired format (YYYY-MM-DD)
            function getCurrentDate() {
                const today = new Date();
                const day = String(today.getDate()).padStart(2, '0');
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const year = today.getFullYear();
                return `${year}-${month}-${day}`;
            }
            let amount = 0; 
        
            // Function to capitalize words
            function capitalizeWords(str) {
                return str
                    .split(' ')
                    .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                    .join(' ');
            }
        
            // Set customer name and address
            document.getElementById('finalCustomerName').innerText = capitalizeWords(custNameInput);
            document.getElementById('displayAddress').innerText = capitalizeWords(addressInput);
            document.getElementById('displayDeliveryMethod').innerText = capitalizeWords(deliveryMethod);
            document.getElementById('displayBillingAddress').innerText = capitalizeWords(addressInput);
            const currentDate = getCurrentDate();
            document.getElementById('displayDeliveryDate').innerText =
                deliveryMethodSelect.value === 'pickup' ? currentDate : deliveryDateInput;
        
            document.getElementById('displayBillingDate').innerText = currentDate;
            let paymentDetails = '';
        
            // Handle cash payment method
            if (paymentMethodSelect.value === 'cash') {
                amount = parseFloat(document.getElementById('cashAmount').value) || 0;
                paymentDetails = `Cash Amount: ₱ ${amount.toFixed(2)}`;
            } 
            // Handle GCash payment method
            else if (paymentMethodSelect.value === 'gcash') {
                const senderName = capitalizeWords(document.getElementById('senderName').value || 'N/A');
                amount = parseFloat(document.getElementById('gcashAmount').value) || 0;
                const referenceNum = document.getElementById('referenceNum').value || 'N/A';
                paymentDetails = `Sender Name: ${senderName}\nAmount: ₱ ${amount.toFixed(2)}\nReference: ${referenceNum}`;
            } 
            // Handle bank transfer payment method
            else if (paymentMethodSelect.value === 'banktransfer') {
                const bankName = capitalizeWords(document.getElementById('bankName').value || 'N/A');
                const accHold = capitalizeWords(document.getElementById('accHold').value || 'N/A');
                amount = parseFloat(document.getElementById('amount').value) || 0;
                const transactDate = document.getElementById('transactDate').value || 'N/A';
                const transactRef = document.getElementById('transactRef').value || 'N/A';
                paymentDetails = `Bank Name: ${bankName}\nAccount Holder: ${accHold}\nAmount: ₱ ${amount.toFixed(2)}\nTransaction Date: ${transactDate}\nTransaction Reference: ${transactRef}`;
            } 
            else {
                paymentDetails = 'Payment method not recognized.';
            }
            const change = amount - overallTotal;
            document.getElementById('displayChange').innerText = change >= 0 ? `Change: ₱ ${change.toFixed(2)}` : `Insufficient payment`;
            document.getElementById('displayPaymentDetails').innerText = paymentDetails || 'N/A';
        }
          
        // Handle Confirm Order Button
        document.getElementById('placeOrderButton').addEventListener('click', function(e) {
            e.preventDefault();
        
            Swal.fire({
                title: 'Are you sure you want to confirm this order?',
                text: "Make sure all your information is correct!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, confirm order!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let paymentMethodSelect = document.getElementById('paymentMethod');
                    let payment = 0;
        
                    if (paymentMethodSelect.value === 'cash') {
                        payment = parseFloat(document.getElementById('cashAmount').value) || 0;
                    } else if (paymentMethodSelect.value === 'gcash') {
                        payment = parseFloat(document.getElementById('gcashAmount').value) || 0;
                    } else if (paymentMethodSelect.value === 'banktransfer') {
                        payment = parseFloat(document.getElementById('amount').value) || 0;
                    }
                    if (payment < overallTotal) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Insufficient Payment!',
                            text: `Your payment is ₱${payment.toFixed(2)}, but the total amount is ₱${overallTotal.toFixed(2)}.`,
                            confirmButtonText: 'OK'
                        });
                        return; 
                    }
                    // Proceed with the order
                    populateConfirmation();
                    updateConfirmationSummary();
                    sendOrderToDatabase();
                }
            });
        });
        // For the table
        function updateConfirmationSummary() {
            const orderSummaryBody1 = document.getElementById('orderSummaryBody1'); // Step 1 table
            const orderSummaryBody = document.getElementById('orderSummaryBody');   // Step 3 table
            orderSummaryBody.innerHTML = '';

            Array.from(orderSummaryBody1.rows).forEach(row => {
                const newRow = orderSummaryBody.insertRow();
                const productCell = newRow.insertCell(0);
                const quantityCell = newRow.insertCell(1);
                const priceCell = newRow.insertCell(2);
                const totalCell = newRow.insertCell(3);

                const productName = row.cells[0].innerText;  
                const quantity = row.cells[1].innerText;     
                const price = parseFloat(row.cells[2].innerText.replace('₱ ', '')); 
                const total = parseFloat(quantity) * price;

                productCell.innerText = productName;
                quantityCell.innerText = quantity;
                priceCell.innerText = `₱ ${price.toFixed(2)}`; 
                totalCell.innerText = `₱ ${total.toFixed(2)}`;
            });
            updateTotalConfirmation();
        }
        function updateTotalConfirmation() {
            const orderSummaryBody = document.getElementById('orderSummaryBody'); // Step 3 table
            let totalAmount = 0;

            Array.from(orderSummaryBody.rows).forEach(row => {
                const total = parseFloat(row.cells[3].innerText.replace('₱ ', ''));
                totalAmount += total;
            });
            document.getElementById('totalConfirmation').innerText = `₱ ${totalAmount.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            return totalAmount;    
        }
        document.getElementById('nextToCustomerInfo').addEventListener('click', function() {
            updateConfirmationSummary();
        });
        function sendOrderToDatabase() {
            const customerName = document.getElementById('finalCustomerName').innerText;
            const address = document.getElementById('displayAddress').innerText;
            const deliveryMethod = document.getElementById('displayDeliveryMethod').innerText;
            const paymentMethodSelect = document.getElementById('paymentMethod');
            const paymentMethod = paymentMethodSelect.options[paymentMethodSelect.selectedIndex]?.text || 'N/A';
            let deliveryDate = document.getElementById('displayDeliveryDate').innerText;
            let billingDate = document.getElementById('displayBillingDate').innerText;
        
            if (deliveryMethod.trim() === 'pickup') {
                const currentDate = new Date();
                const formattedDate = currentDate.toISOString().slice(0, 10); 
            
                deliveryDate = formattedDate;
                billingDate = formattedDate;
    
                document.getElementById('displayBillingDate').innerText = billingDate; 
            }    
            if (!deliveryDate || deliveryDate === 'N/A') {
                deliveryDate = null;
            }
            if (!billingDate || billingDate === 'N/A') {
                billingDate = null;
            }
            let paymentDetails = '';
            let referenceNum = '';
            let payment = 0;
        
            if (paymentMethodSelect.value === 'cash') {
                payment = parseFloat(document.getElementById('cashAmount').value) || 0;
                paymentDetails = `Cash Amount: ₱ ${payment.toFixed(2)}`;
                referenceNum = null; 
            } else if (paymentMethodSelect.value === 'gcash') {
                const senderName = (document.getElementById('senderName')?.value || 'N/A');
                payment = parseFloat(document.getElementById('gcashAmount').value) || 0;
                referenceNum = document.getElementById('referenceNum')?.value || 'N/A';
                paymentDetails = `Sender Name: ${senderName}, Amount: ₱ ${payment.toFixed(2)}, Reference: ${referenceNum}`;
            } else if (paymentMethodSelect.value === 'banktransfer') {
                const bankName = (document.getElementById('bankName')?.value || 'N/A');
                const accHold = (document.getElementById('accHold')?.value || 'N/A');
                payment = parseFloat(document.getElementById('amount').value) || 0;
                const transactDate = document.getElementById('transactDate')?.value || 'N/A';
                referenceNum = document.getElementById('transactRef')?.value || 'N/A';
                paymentDetails = `Bank: ${bankName}, Account Holder: ${accHold}, Amount: ₱ ${payment.toFixed(2)}, Transaction Date: ${transactDate}, Transaction Reference: ${referenceNum}`;
            } else {
                paymentDetails = 'Payment method not recognized.';
            }
        
            // Collect order items data
            let orderItems = [];
            const orderSummaryBody1 = document.querySelector('#orderSummaryBody1'); // Assuming this is the correct tbody
        
            Array.from(orderSummaryBody1.rows).forEach(row => {
                let type = row.dataset.itemType;
                let id = row.dataset.itemId; 
                
                if (type && id) {
                    const productName = row.cells[0].innerText;
                    const quantity = parseInt(row.cells[1].innerText);
                    const price = parseFloat(row.cells[2].innerText.replace('₱ ', ''));
                    const total = parseFloat(quantity) * price;
        
                    if (type === 'service') {
                        orderItems.push({
                            type: type, 
                            id: id,
                            serviceName: productName,
                            quantity: quantity,
                            price: price,
                            total: total
                        });
                    } else if (type === 'product') {
                        orderItems.push({
                            type: type, 
                            id: id,
                            productName: productName, 
                            quantity: quantity,
                            price: price,
                            total: total
                        });
                    } else{
                        orderItems.push({
                            type: type, 
                            id: id, 
                            name: itemName, 
                            quantity: quantity,
                            price: price, 
                            total: total 
                        });
                    }
                } else {
                    console.error("Type or ID is missing for this row.");
                }
            });
            let totalAmount = orderItems.reduce((sum, item) => sum + item.total, 0);
        
            if (!deliveryDate || isNaN(Date.parse(deliveryDate))) {
                console.error('Invalid delivery date:', deliveryDate);
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Delivery Date',
                    text: 'Please provide a valid delivery date.',
                    confirmButtonText: 'OK'
                });
                return;
            }
        
            const payload = {
                customerName: customerName,
                address: address,
                deliveryMethod: deliveryMethod,
                deliveryDate: deliveryDate,
                paymentMethod: paymentMethod,
                billingDate: billingDate,
                referenceNum: referenceNum,
                payment: payment,
                orderItems: orderItems,
                totalAmount: totalAmount,
            };
            
            $.ajax({
                url: '/confirm/storeOrderReceipt',
                type: 'POST',
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: JSON.stringify(payload),
                success: function(response) {
                    if (response.warning) {
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Order Confirmed! :)',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.open(`/receipt/${response.ordDet_ID}`, '_blank'); 
                            window.location.reload();
                        });
                    }
                },                
                error: function(xhr) {
                    const errorMessage = xhr.responseJSON?.message || 'Something went wrong. Please try again!';            
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong :(',
                        text: errorMessage,
                        confirmButtonText: 'OK'
                    });
                }
            });
        }        
        toggleNextButton();
    });
function filterTable() {
    document.getElementById('searchInput').addEventListener('keyup', filterTable);

    let input = document.getElementById('searchInput');
    let filter = input.value.toLowerCase();
    let table = document.querySelector('.custom-table');
    let tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        let td = tr[i].getElementsByTagName('td');
        let found = false;
        if ((td[0] && td[0].textContent.toLowerCase().indexOf(filter) > -1) || 
            (td[1] && td[1].textContent.toLowerCase().indexOf(filter) > -1)) {
           found = true;
        }
        tr[i].style.display = found ? '' : 'none';
    }
}
// for the order if it is reserve or debt
document.getElementById('reservationButton').addEventListener('click', function(e) {
    e.preventDefault();
    Swal.fire({
        title: "Take a Credit?",
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: "Yes",
        denyButtonText: "No"
    }).then((result) => {
        if (result.isConfirmed) {
            const creditStatus = {
                'debt': 'Take a Debt',
                'reserve': 'Reserve an Item',
            };
            Swal.fire({
                title: 'Choose an Option',
                input: 'select',
                inputOptions: creditStatus,
                inputPlaceholder: 'Select Option',
                confirmButtonText: 'Confirm',
                showCancelButton: true,
                cancelButtonText: 'Cancel'
            }).then((inputOptions) => {
                if (inputOptions.isConfirmed) {  
                    let selectedOption = inputOptions.value;  
                    let paymentMethodSelect = document.getElementById('paymentMethod');
                    let payment = 0;
                    

                    if (paymentMethodSelect.value === 'cash') {
                        payment = parseFloat(document.getElementById('cashAmount').value) || 0;
                    } else if (paymentMethodSelect.value === 'gcash') {
                        payment = parseFloat(document.getElementById('gcashAmount').value) || 0;
                    } else if (paymentMethodSelect.value === 'banktransfer') {
                        payment = parseFloat(document.getElementById('amount').value) || 0;
                    }
                    Swal.fire({
                        title: "Order Confirmed for Credit",
                        text: `You chose to: ${selectedOption}.  Payment Amount: ₱${payment.toFixed(2)}`,
                        icon: "success"
                    }).then(() => {
                        sendCredit(selectedOption, payment); // Pass the correct selected option to sendCredit
                    });

                } else {
                    Swal.fire("Order Confirmed for Credit has been canceled", "", "info");
                }
            });
        } else if (result.isDenied) {
            Swal.fire("Order Confirmed for Credit has been canceled", "", "info");
        }
    });
});
// Function to send credit to the server
function sendCredit(selectedStatus) {
    const customerName = document.getElementById('finalCustomerName').innerText;
    const address = document.getElementById('displayAddress').innerText;
    const deliveryMethod = document.getElementById('displayDeliveryMethod').innerText;
    const paymentMethodSelect = document.getElementById('paymentMethod');
    const paymentMethod = paymentMethodSelect.options[paymentMethodSelect.selectedIndex]?.text || 'N/A';
    let deliveryDate = document.getElementById('displayDeliveryDate').innerText;
    let billingDate = document.getElementById('displayBillingDate').innerText;

    if (deliveryMethod.trim() === 'pickup') {
        const currentDate = new Date();
        const formattedDate = currentDate.toISOString().slice(0, 10); 
    
        deliveryDate = formattedDate;
        billingDate = formattedDate;

        document.getElementById('displayBillingDate').innerText = billingDate; 
    }    
    if (!deliveryDate || deliveryDate === 'N/A') {
        deliveryDate = null;
    }
    if (!billingDate || billingDate === 'N/A') {
        billingDate = null;
    }
    let paymentDetails = '';
    let referenceNum = '';
    let payment = 0;

    if (paymentMethodSelect.value === 'cash') {
        payment = parseFloat(document.getElementById('cashAmount').value) || 0;
        paymentDetails = `Cash Amount: ₱ ${payment.toFixed(2)}`;
        referenceNum = null; 
    } else if (paymentMethodSelect.value === 'gcash') {
        const senderName = (document.getElementById('senderName')?.value || 'N/A');
        payment = parseFloat(document.getElementById('gcashAmount').value) || 0;
        referenceNum = document.getElementById('referenceNum')?.value || 'N/A';
        paymentDetails = `Sender Name: ${senderName}, Amount: ₱ ${payment.toFixed(2)}, Reference: ${referenceNum}`;
    } else if (paymentMethodSelect.value === 'banktransfer') {
        const bankName = (document.getElementById('bankName')?.value || 'N/A');
        const accHold = (document.getElementById('accHold')?.value || 'N/A');
        payment = parseFloat(document.getElementById('amount').value) || 0;
        const transactDate = document.getElementById('transactDate')?.value || 'N/A';
        referenceNum = document.getElementById('transactRef')?.value || 'N/A';
        paymentDetails = `Bank: ${bankName}, Account Holder: ${accHold}, Amount: ₱ ${payment.toFixed(2)}, Transaction Date: ${transactDate}, Transaction Reference: ${referenceNum}`;
    } else {
        paymentDetails = 'Payment method not recognized.';
    }

    // Collect order items data
    let orderItems = [];
    const orderSummaryBody1 = document.querySelector('#orderSummaryBody1'); // Assuming this is the correct tbody

    Array.from(orderSummaryBody1.rows).forEach(row => {
        let type = row.dataset.itemType;
        let id = row.dataset.itemId; 
        
        if (type && id) {
            const productName = row.cells[0].innerText;
            const quantity = parseInt(row.cells[1].innerText);
            const price = parseFloat(row.cells[2].innerText.replace('₱ ', ''));
            const total = parseFloat(quantity) * price;

            if (type === 'service') {
                orderItems.push({
                    type: type, 
                    id: id,
                    serviceName: productName,
                    quantity: quantity,
                    price: price,
                    total: total
                });
            } else if (type === 'product') {
                orderItems.push({
                    type: type, 
                    id: id,
                    productName: productName, 
                    quantity: quantity,
                    price: price,
                    total: total
                });
            } else{
                orderItems.push({
                    type: type, 
                    id: id, 
                    name: itemName, 
                    quantity: quantity,
                    price: price, 
                    total: total 
                });
            }
        } else {
            console.error("Type or ID is missing for this row.");
        }
    });
    let totalAmount = orderItems.reduce((sum, item) => sum + item.total, 0);

    if (!deliveryDate || isNaN(Date.parse(deliveryDate))) {
        console.error('Invalid delivery date:', deliveryDate);
        Swal.fire({
            icon: 'error',
            title: 'Invalid Delivery Date',
            text: 'Please provide a valid delivery date.',
            confirmButtonText: 'OK'
        });
        return;
    }

    const payload = {
        customerName: customerName,
        address: address,
        deliveryMethod: deliveryMethod,
        deliveryDate: deliveryDate,
        paymentMethod: paymentMethod,
        billingDate: billingDate,
        referenceNum: referenceNum,
        payment: payment,
        orderItems: orderItems,
        totalAmount: totalAmount,
        credit_type: selectedStatus
    };
    
    console.log(payload);
    $.ajax({
        url: '/storeCredit',
        type: 'POST',
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: JSON.stringify(payload),
        success: function(response) {
            if (response.warning) {
            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'Order Confirmed! :)',
                    text: response.message,
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.reload();
                });
            }
        },                
        error: function(xhr) {
            const errorMessage = xhr.responseJSON?.message || 'Something went wrong. Please try again!';            
            Swal.fire({
                icon: 'error',
                title: 'Something went wrong :(',
                text: errorMessage,
                confirmButtonText: 'OK'
            });
        }
    });
}        
function confirmCancel(event) {
    event.preventDefault();

    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you really want to cancel this order?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, cancel it!',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = event.target.closest('form');
            form.submit();
            Swal.fire({
                title: 'Success!',
                text: 'Order canceled successfully.',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }
    });
}
document.querySelectorAll('.pay-button').forEach(button => {
    button.addEventListener('click', function () {
        const creditID = this.getAttribute('data-credit-id');
        const customerName = this.getAttribute('data-customer-name');
        const totalPrice = this.getAttribute('data-total-price');
        const initialPayment = this.getAttribute('data-initial-payment');
        const remainingBalance = this.getAttribute('data-remaining-balance');
        const reservedDebtDate = this.getAttribute('data-reserved-debt-date');

        // Set values in the modal
        document.getElementById('modalCreditID').value = creditID; // Hidden input
        document.getElementById('modalCustomerName').innerText = customerName;
        document.getElementById('modalTotalPrice').innerText = totalPrice;
        document.getElementById('modalInitialPayment').innerText = initialPayment;
        document.getElementById('modalRemainingBalance').innerText = remainingBalance;
        document.getElementById('modalReservedDebtDate').innerText = reservedDebtDate;

    });
});
function confirmPayment(event) {
    event.preventDefault(); // Prevent the default button behavior
    const form = document.getElementById('paymentForm');
    const formData = new FormData(form); 

    const remainingBalance = parseFloat(document.getElementById('modalRemainingBalance').innerText.replace(/[^0-9.-]+/g, ""));
    const paymentAmount = parseFloat(formData.get('paymentAmount')); 

    // Check for valid payment amount
    if (isNaN(paymentAmount) || paymentAmount <= 0) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Amount',
            text: 'Please enter a valid payment amount.',
            confirmButtonText: 'OK'
        });
        return;
    }

    // Check if payment amount is less than remaining balance
    if (paymentAmount < remainingBalance) {
        Swal.fire({
            icon: 'error',
            title: 'Insufficient Payment',
            text: `Payment amount is not enough. You need to pay at least ${remainingBalance}.`,
            confirmButtonText: 'OK'
        });
        return;
    }
    Swal.fire({
        icon: 'success',
        title: 'Valid Payment',
        text: `Payment amount is valid. Proceeding with the payment.`,
        confirmButtonText: 'OK'
    }).then(() => {
        fetch('/confirmPayment', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData 
        })
        .then(response => {
            console.log('Response Status:', response.status);
            if (!response.ok) {
                return response.text().then(errorText => {
                    throw new Error(errorText);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Payment Confirmed',
                    text: 'Your payment has been successfully processed.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.open(`/receipt/${formData.get('creditID')}`, '_blank'); 
                    window.location.reload(); 
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message,
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'There was a problem with your request.',
                confirmButtonText: 'OK'
            });
        });
    });
}
