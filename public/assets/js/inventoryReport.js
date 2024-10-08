function filterTable() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const table = document.querySelector('.cstm-table');
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        const row = tr[i];
        const td = row.getElementsByTagName('td');

        let found = false;
        let showRow = true;

        for (let j = 0; j < td.length; j++) {
            if (td[j] && td[j].textContent.toLowerCase().includes(searchInput)) {
                found = true;
                break;
            }
        }
        if (searchInput && !found) {
            showRow = false;
        }
        if (td[7]) { 
            const rowDate = new Date(td[7].textContent.trim());
            if (isNaN(rowDate.getTime())) {
                console.error(`Invalid date in row ${i}: ${td[7].textContent}`);
                showRow = false;
            } else {
                if ((!isNaN(currentFromDate?.getTime()) && rowDate < currentFromDate) || 
                    (!isNaN(currentToDate?.getTime()) && rowDate > currentToDate)) {
                    showRow = false;
                }
            }
        }

        row.style.display = showRow ? '' : 'none';
    }
}
