const searchInput = document.getElementById('searchQuery');
const tables = document.getElementsByClassName('table-container');

// Loop through each table
for (let i = 0; i < tables.length; i++) {
    const dataTable = tables[i].querySelector('table');
    const rows = dataTable.getElementsByTagName('tr');

    // Add event listener to the search input
    searchInput.addEventListener('input', function () {
        const searchText = searchInput.value.toLowerCase();

        // Loop through all table rows, excluding the header row
        for (let i = 1; i < rows.length; i++) { // Start loop from index 1 to skip the header row
            const cells = rows[i].getElementsByTagName('td');
            let found = false;

            // Loop through all table cells in the current row
            for (let j = 0; j < cells.length; j++) {
                const cellText = cells[j].textContent.toLowerCase();
                if (cellText.includes(searchText)) {
                    found = true;
                    break;
                }
            }

            // Show or hide the row based on the search result
            if (found) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    });
}