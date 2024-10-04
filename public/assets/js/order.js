    document.addEventListener('DOMContentLoaded', function() {
        // Handle Category Filtering
        window.filterCategory = function() {
            const category = document.getElementById('categoryfilter').value;
            document.getElementById('productTable').style.display = category === 'product' ? 'block' : 'none';
            document.getElementById('serviceInput').style.display = category === 'services' ? 'block' : 'none';
            document.getElementById('reserve').style.display = category === 'custDebt' ? 'block' : 'none';
        };
        filterCategory();
        // Handle Product Modal Population
        $('#productModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var productId = button.data('id'); 
            var productName = button.data('name');
            var productCategory = button.data('category');
            var productDesc = button.data('desc');
            var productPrice = button.data('price');

            var modal = $(this);
            modal.find('#modalProductName').text(productName);
            modal.find('#modalProductCategory').text(productCategory);
            modal.find('#modalProductDesc').text(productDesc);
            modal.find('#modalProductPrice').text(parseFloat(productPrice).toFixed(2));
            modal.find('#product_id').val(productId);
        });
        // Add Product to Order Summary
        $('#addProductButton').on('click', function() {
            const productName = $('#modalProductName').text();
            const productPrice = parseFloat($('#modalProductPrice').text());
            const quantity = parseInt($('#quantity').val());

            if (isNaN(quantity) || quantity < 1) {
                alert("Please enter a valid quantity.");
                return;
            }

            const totalPrice = productPrice * quantity;
            const orderSummaryBody = document.getElementById('orderSummaryBody1');

            // Check if the product already exists in the order summary
            let existingRow = Array.from(orderSummaryBody.rows).find(row => 
                row.cells[0].innerText === productName &&
                parseFloat(row.cells[2].innerText.replace('₱ ', '')) === productPrice
            );

            if (existingRow) {
                // If the row exists, update the quantity and total
                let existingQuantity = parseInt(existingRow.cells[1].innerText);
                existingRow.cells[1].innerText = existingQuantity + quantity;
                existingRow.cells[3].innerText = `₱ ${(parseFloat(existingRow.cells[3].innerText.replace('₱ ', '').replace(',', '')) + totalPrice).toFixed(2)}`;
            } else {
                // Create a new row
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
            toggleNextButton();
            $('#productModal').modal('hide');
        });
        // Add Service to Order Summary
        document.querySelectorAll('.add-service').forEach(button => {
            button.addEventListener('click', function() {
                const serviceName = button.getAttribute('data-name');
                const serviceFee = parseFloat(button.getAttribute('data-fee')).toFixed(2);

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
                    // Create a new row
                    const newRow = orderSummaryBody.insertRow();
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
        function updateTotalAmount() {
            const orderSummaryBody = document.getElementById('orderSummaryBody1');
            let overallTotal = 0;
            for (let row of orderSummaryBody.rows) {
                overallTotal += parseFloat(row.cells[3].innerText.replace('₱ ', ''));
            }
            document.getElementById('totalAmount').innerText = '₱ ' + overallTotal.toFixed(2);
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

            // Calculate and set the width of the progress line
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
            const custNameInput = document.getElementById('custName').value || 'N/A';
            const addressInput = document.getElementById('address').value || 'N/A';
            const deliveryMethodSelect = document.getElementById('deliveryMethod');
            const deliveryMethod = deliveryMethodSelect.options[deliveryMethodSelect.selectedIndex]?.text || 'N/A';
            const deliveryDateInput = document.getElementById('deliveryDate').value || 'N/A';
            const paymentMethodSelect = document.getElementById('paymentMethod');
            const paymentMethod = paymentMethodSelect.options[paymentMethodSelect.selectedIndex]?.text || 'N/A';
            
            // For the billing date
            const currentDate = new Date().toLocaleDateString(); 
            function capitalizeWords(str) {
            return str
                .split(' ') 
                .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                .join(' '); 
            }

            document.getElementById('finalCustomerName').innerText = capitalizeWords(custNameInput);
            document.getElementById('displayAddress').innerText = capitalizeWords(addressInput);
            document.getElementById('displayDeliveryMethod').innerText = capitalizeWords(deliveryMethod);
            document.getElementById('displayDeliveryDate').innerText = deliveryMethodSelect.value === 'deliver' ? deliveryDateInput : currentDate; 
            document.getElementById('displayPaymentMethod').innerText = capitalizeWords(paymentMethod);
            document.getElementById('displayBillingAddress').innerText = capitalizeWords(addressInput);
            document.getElementById('displayBillingDate').innerText = currentDate;

            let paymentDetails = '';
            if (paymentMethodSelect.value === 'cash') {
                const cashAmount = document.getElementById('cashAmount').value;
                paymentDetails = `Cash Amount: ₱ ${parseFloat(cashAmount).toFixed(2) || 'N/A'}`;
            } else if (paymentMethodSelect.value === 'gcash') {
                const senderName = capitalizeWords(document.getElementById('senderName').value || 'N/A');
                const gcashAmount = document.getElementById('gcashAmount').value;
                const formattedGcashAmount = `₱ ${parseFloat(gcashAmount).toFixed(2) || 'N/A'}`; 
                const referenceNum = document.getElementById('referenceNum').value || 'N/A';
                paymentDetails = `Sender Name: ${senderName}
                    Amount: ${formattedGcashAmount}
                    Reference: ${referenceNum}`;

                    console.log('Payment Type:', paymentMethod);  // Should log "gcash"
                    console.log('Gcash CustTRYTUomer Name:', document.getElementById('senderName').value);
                    console.log('Gcash Payment:', document.getElementById('gcashAmount').value);
                    console.log('Gcash Reference Number:', document.getElementById('referenceNum').value);

            } else if (paymentMethodSelect.value === 'banktransfer') {
                const bankName = capitalizeWords(document.getElementById('bankName').value || 'N/A');
                const accHold = capitalizeWords(document.getElementById('accHold').value || 'N/A');
                const amount = document.getElementById('amount').value;
                const formattedAmount = `₱ ${parseFloat(amount).toFixed(2) || 'N/A'}`; 
                const transactDate = document.getElementById('transactDate').value || 'N/A';
                const transactRef = document.getElementById('transactRef').value || 'N/A';
                paymentDetails = `Bank: ${bankName}
                    Account Holder: ${accHold}
                    Amount: ${formattedAmount}
                    Transaction Date: ${transactDate}
                    Transaction Reference: ${transactRef}`;

                    console.log('Payment Type:', paymentMethod);  // Should log "gcash"
                    console.log('Gcash Customer Name:', document.getElementById('accHold').value);
                    console.log('Gcash Payment:', document.getElementById('amount').value);
                    console.log('Gcash Reference Number:', document.getElementById('transactRef').value);
            } else {
            paymentDetails = 'Payment method not recognized.';
            }

            document.getElementById('displayPaymentDetails').innerText = paymentDetails || 'N/A';
        }

        // Handle Confirm Order Button
        document.getElementById('placeOrderButton').addEventListener('click', function(e) {
            e.preventDefault(); // Prevent the form from submitting immediately

            Swal.fire({
                title: 'Are you sure you want to place this order?',
                text: "Make sure all your information is correct!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, place order!'
            }).then((result) => {
                if (result.isConfirmed) {
                    populateConfirmation();
                    updateConfirmationSummary();
                    sendOrderToDatabase(); 

                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to place the order.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Handle Reservation Button
        document.getElementById('reservationButton').addEventListener('click', function() {
            const reservationItems = [];
            const orderSummaryBody = document.getElementById('orderSummaryBody1');

            for (let row of orderSummaryBody.rows) {
                const productId = row.getAttribute('data-product-id');  // Product ID
                const serviceId = row.getAttribute('data-service-id');  // Service ID (if any)
                const quantity = row.cells[1].innerText;                // Quantity
                const price = row.cells[2].innerText.replace('₱ ', ''); // Price
                const total = row.cells[3].innerText.replace('₱ ', ''); // Total price

                reservationItems.push({
                    product_id: productId ? parseInt(productId) : null,
                    service_id: serviceId ? parseInt(serviceId) : null,
                    quantity: parseInt(quantity),
                    total: parseFloat(total)
                });
            }

            const deliveryMethod = document.getElementById('displayDeliveryMethod').innerText;
            const deliveryDate = document.getElementById('displayDeliveryDate').innerText;
            const customerName = document.getElementById('finalCustomerName').innerText; // Get customer name
            const finalTotal = document.getElementById('totalConfirmation').innerText.replace('₱ ', '');

            // Populate the reservation table
            const reserveTableBody = document.querySelector('#reserve tbody');
            reserveTableBody.innerHTML = ''; // Clear existing rows

            reservationItems.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${customerName}</td>
                    <td>${item.product_id ? 'Product ID: ' + item.product_id : 'Service ID: ' + item.service_id}</td>
                    <td>${item.quantity}</td>
                    <td>₱ ${item.total.toFixed(2)}</td>
                    <td>₱ ${finalTotal}</td>
                    <td>${deliveryDate}</td>
                `;
                reserveTableBody.appendChild(row);
            });

            // Show the reservation table
            document.getElementById('reserve').style.display = 'block';

            // Submit reservation data via AJAX (optional if you want to store it on the server)
            $.ajax({
                url: "{{ route('storeReservation') }}", 
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    reservationItems: reservationItems,
                    deliveryMethod: deliveryMethod,
                    deliveryDate: deliveryDate,
                    finalTotal: finalTotal
                },
                success: function(response) {
                    alert('Reservation made successfully!');
                    // Optional: You can refresh the table here or handle the response as needed
                },
                error: function(xhr) {
                    alert('An error occurred while making the reservation.');
                }
            });
        });


        // For the table
        function updateConfirmationSummary() {
            const orderSummaryBody1 = document.getElementById('orderSummaryBody1'); // Step 1 table
            const orderSummaryBody = document.getElementById('orderSummaryBody');   // Step 3 table
            orderSummaryBody.innerHTML = '';

            // Iterate through each row in Step 1's order summary and copy to Step 3
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

                // Assign the values to the new row in Step 3
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
        }

        // Ensure the updateConfirmationSummary function is called when moving to Step 3
        document.getElementById('nextToCustomerInfo').addEventListener('click', function() {
            updateConfirmationSummary();
        });
        
        // storing of data
        function sendOrderToDatabase() {
            const customerName = document.getElementById('finalCustomerName').innerText;
            const address = document.getElementById('displayAddress').innerText;
            const deliveryMethod = document.getElementById('displayDeliveryMethod').innerText;
            const deliveryDate = document.getElementById('displayDeliveryDate').innerText;
            const paymentMethod = document.getElementById('displayPaymentMethod').innerText;
            const billingDate = document.getElementById('displayBillingDate').innerText;

            let paymentDetails = '';
            let referenceNum = '';
            let payment = 0;

            if (paymentMethod === 'Cash') {
                payment = parseFloat(document.getElementById('cashAmount').value) || 0;
                paymentDetails = `Cash Amount: ₱ ${payment.toFixed(2)}`;
                referenceNum = null; 
            } else if (paymentMethod === 'Gcash') {
                const senderName = (document.getElementById('senderName')?.value || 'N/A');
                payment = parseFloat(document.getElementById('gcashAmount').value) || 0;
                referenceNum = document.getElementById('referenceNum')?.value || 'N/A';
                paymentDetails = `Sender Name: ${senderName}, Amount: ₱ ${payment.toFixed(2)}, Reference: ${referenceNum}`;
            } else if (paymentMethod === 'BankTransfer') {
                const bankName = (document.getElementById('bankName')?.value || 'N/A');
                const accHold = (document.getElementById('accHold')?.value || 'N/A');
                payment = parseFloat(document.getElementById('amount').value) || 0;
                const transactDate = document.getElementById('transactDate')?.value || 'N/A';
                referenceNum = document.getElementById('transactRef')?.value || 'N/A';
                paymentDetails = `Bank: ${bankName}, Account Holder: ${accHold}, Amount: ₱ ${payment.toFixed(2)}, Transaction Date: ${transactDate}, Transaction Reference: ${referenceNum}`;
            } else {
                paymentDetails = 'Payment method not recognized.';
            }

            console.log('Payment Details:', paymentDetails);  
            console.log('Final Payload Reference Number:', referenceNum);  

            // Collect order items data
            const orderItems = [];
            const orderSummaryBody = document.getElementById('orderSummaryBody1');

            Array.from(orderSummaryBody.rows).forEach(row => {
                const type = row.getAttribute('data-item-type'); // Get item type
                const id = row.getAttribute('data-item-id');     // Get item ID
                const quantity = parseInt(row.cells[1].innerText);
                const price = parseFloat(row.cells[2].innerText);
                const total = parseFloat(row.cells[3].innerText);

                // Ensure type and id are valid
                if (type && id) {
                    orderItems.push({
                        type: type,
                        id: id,
                        quantity: quantity,
                        price: price,
                        total: total
                    });
                }
            });

            const payload = {
                customerName: customerName,
                address: address,
                deliveryMethod: deliveryMethod,
                deliveryDate: deliveryDate,
                paymentMethod: paymentMethod,
                billingDate: billingDate,
                referenceNum: referenceNum,
                payment: payment,
                orderItems: orderItems
            };

            console.log('Payload:', payload);

            axios.post("{{ route('storeReceipt') }}", payload, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Success:', response.data);
            })
            .catch(error => {
                if (error.response) {
                    console.error('HTTP error:', error.response.status);
                    console.error('Error details:', error.response.data);
                } else if (error.request) {
                    console.error('Network error: Please check your internet connection.');
                } else {
                    console.error('Error:', error.message);
                }
            });
            console.log("Route: {{ route('storeReceipt') }}");
            console.log(orderItems);  // Check if orderItems are being collected correctly
            console.error('Error details:', error.response.data);  // It might give you more specific information.


            console.log("CSRF Token:", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        }
        toggleNextButton();
    });