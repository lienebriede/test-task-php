document.addEventListener('DOMContentLoaded', function () {

    const productTypeSelect = document.getElementById('productType');
    const submitButton = document.getElementById('submitButton');
    const cancelButton = document.getElementById('cancelButton');
    const typeFields = {
        'DVD': ['size'],
        'Furniture': ['height', 'width', 'length'],
        'Book': ['weight']
    };

    productTypeSelect.addEventListener('change', function () {
        switchType(this.value);
    });

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
                console.log(`Field "${field.id}" is empty.`);
            }
        });

        return isValid;
    }

    /** Save btn */
    submitButton.addEventListener('click', function () {
        console.log('Save button clicked.');
        if (validateForm()) {
            document.getElementById('product_form').submit();
        } else {
            alert('Please, submit required data.');
        }
    });

    /** Cancel btn */
    cancelButton.addEventListener('click', function () {
        window.location.href = 'index.php';
    });
});