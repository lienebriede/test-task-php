// Type switcher function
document.getElementById('productType').addEventListener('change', function () {
    switchType.call(this);
});

function switchType() {
    const type = this.value;
    const types = ['Book', 'DVD', 'Furniture'];

    types.forEach(function (item) {
        const element = document.getElementById(item);
        if (item === type) {
            element.classList.remove('d-none');
            element.classList.add('d-block');
        } else {
            element.classList.remove('d-block');
            element.classList.add('d-none');
        }
    });
}

// Product Add
document.addEventListener('DOMContentLoaded', function () {

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

    /** Cancel btn */
    document.getElementById('cancelButton').addEventListener('click', function () {
        window.location.href = 'index.php';
    });

    /** Save btn */
    document.getElementById('submitButton').addEventListener('click', function () {
        console.log('Save button clicked.');
        if (validateForm()) {
            document.getElementById('product_form').submit();
        } else {
            alert('Please, submit required data.');
        }
    });
});