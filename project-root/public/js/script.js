document.getElementById('type').addEventListener('change', function () {
    var type = this.value;
    document.getElementById('bookFields').style.display = (type === 'Book') ? 'block' : 'none';
    document.getElementById('dvdFields').style.display = (type === 'DVD') ? 'block' : 'none';
    document.getElementById('furnitureFields').style.display = (type === 'Furniture') ? 'block' : 'none';
});