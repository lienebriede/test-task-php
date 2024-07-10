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