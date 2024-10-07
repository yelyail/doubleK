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
