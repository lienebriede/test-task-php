document.addEventListener('DOMContentLoaded', function () {
    const productTypeSelect = document.getElementById('productType');
    const submitButton = document.getElementById('submitButton');
    const cancelButton = document.getElementById('cancelButton');
    const skuInput = document.getElementById('sku');
    const skuError = document.getElementById('skuError');
    const massDeleteButton = document.getElementById('massDeleteButton');
    const checkboxes = document.querySelectorAll('.delete-checkbox');

    const typeFields = {
        'DVD': ['size'],
        'Furniture': ['height', 'width', 'length'],
        'Book': ['weight']
    };

    if (productTypeSelect) {
        productTypeSelect.addEventListener('change', function () {
            const selectedType = this.value;
            updateFieldsVisibility(selectedType);
        });
    }

    /** Update fields visibility */
    function updateFieldsVisibility(selectedType) {
        const types = Object.keys(typeFields);

        types.forEach(function (type) {
            const element = document.getElementById(type);
            if (element) {
                element.classList.toggle('d-none', type !== selectedType);
                element.classList.toggle('d-block', type === selectedType);
                toggleRequiredFields(typeFields[type], type === selectedType);
            }
        });
    }

    /** Manage required attribute */
    function toggleRequiredFields(fields, required) {
        fields.forEach(function (fieldId) {
            const field = document.getElementById(fieldId);
            if (field) {
                field.toggleAttribute('required', required);
            }
        });
    }

    /** Validate numeric fields */
    function validateNumericFields() {
        let isValid = true;
        const numericFields = {
            'price': 'Price',
            'size': 'Size',
            'height': 'Height',
            'width': 'Width',
            'length': 'Length',
            'weight': 'Weight'
        };

        for (let [fieldId, fieldName] of Object.entries(numericFields)) {
            const field = document.getElementById(fieldId);
            const errorElement = document.getElementById(`${fieldId}Error`);
            if (field && errorElement) {
                if (field.value.trim() && !isNumeric(field.value.trim())) {
                    errorElement.classList.remove('d-none');
                    isValid = false;
                } else {
                    errorElement.classList.add('d-none');
                }
            }
        }
        return isValid;
    }

    /** Check if value is numeric */
    function isNumeric(value) {
        return !isNaN(parseFloat(value)) && isFinite(value);
    }

    /** Form data validation */
    function validateForm() {
        let isValid = true;
        const requiredFields = document.querySelectorAll('#product_form [required]');

        requiredFields.forEach((field) => {
            if (!field.value.trim()) {
                isValid = false;
            }
        });

        return isValid && validateNumericFields();
    }

    /** Make AJAX request to check SKU uniqueness */
    async function checkSKUUnique(sku) {
        try {
            const response = await fetch('check_sku.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ sku: sku }),
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const data = await response.json();
            return data.unique;
        } catch (error) {
            return false;
        }
    }

    /** Mass delete button */
    if (massDeleteButton) {
        massDeleteButton.addEventListener('click', function () {
            const deleteIds = [];
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    deleteIds.push(checkbox.value);
                }
            });

            if (deleteIds.length === 0) {
                console.log('No products selected for deletion.');
                return;
            }

            deleteProducts(deleteIds);
        });
    }

    function deleteProducts(ids) {
        fetch('delete-products.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                mass_delete: true,
                delete_ids: ids
            }),
        })
            .then(response => {
                if (response.ok) {
                    console.log('Products deleted successfully.');
                    location.reload();
                } else {
                    throw new Error('Failed to delete products.');
                }
            })
            .catch(error => {
                console.error('Error deleting products:', error);
            });
    }

    /** Save button */
    if (submitButton) {
        submitButton.addEventListener('click', async function () {
            skuError.classList.add('d-none');
            const sku = skuInput.value.trim();
            const isSKUUnique = await checkSKUUnique(sku);

            if (!isSKUUnique) {
                skuError.classList.remove('d-none');
                return;
            }

            if (validateForm()) {
                document.getElementById('product_form').submit();
            } else {
                var myModal = new bootstrap.Modal(document.getElementById('customAlertModal'));
                myModal.show();
            }
        });
    }

    /** Cancel button */
    if (cancelButton) {
        cancelButton.addEventListener('click', function () {
            window.location.href = 'index.php';
        });
    }

    // Initiallly hide all fields
    updateFieldsVisibility('');
});