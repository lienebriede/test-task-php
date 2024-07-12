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
            switchType(this.value);
        });
    }

    /** Type switcher function */
    function switchType(selectedType) {
        const types = Object.keys(typeFields);

        types.forEach(function (type) {
            const element = document.getElementById(type);
            if (type === selectedType) {
                element.classList.remove('d-none');
                element.classList.add('d-block');
                toggleRequiredFields(typeFields[type], true);
            } else {
                element.classList.remove('d-block');
                element.classList.add('d-none');
                toggleRequiredFields(typeFields[type], false);
            }
        });
    }

    /** Manage required attribute */
    function toggleRequiredFields(fields, required) {
        fields.forEach(function (fieldId) {
            const field = document.getElementById(fieldId);
            if (field) {
                if (required) {
                    field.setAttribute('required', 'required');
                } else {
                    field.removeAttribute('required');
                }
            }
        });
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

        return isValid;
    }

    /** Make AJAX request to check SKU uniqness */
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

    /** Mass delete btn */
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
                    location.reload(); // Reload the page to reflect changes
                } else {
                    throw new Error('Failed to delete products.');
                }
            })
            .catch(error => {
                console.error('Error deleting products:', error);
            });
    }

    /** Save btn */
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

    /** Cancel btn */
    if (cancelButton) {
        cancelButton.addEventListener('click', function () {
            window.location.href = 'index.php';
        });
    }
});