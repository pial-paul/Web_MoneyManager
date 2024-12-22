// Function to Load Table Data
function loadTable(tableId, data) {
  const tableBody = document
    .getElementById(tableId)
    .getElementsByTagName("tbody")[0];
  tableBody.innerHTML = ""; // Clear existing rows

  data.forEach((item) => {
    const row = document.createElement("tr");
    row.innerHTML = `
        <td>${item.date}</td>
        <td>$${item.amount}</td>
        <td>${item.description}</td>
      `;
    tableBody.appendChild(row);
  });
}

// Function to Sort Table by Date
function sortTable(tableId, columnIndex) {
  const table = document.getElementById(tableId);
  const rows = Array.from(table.rows).slice(1);
  const sortedRows = rows.sort((a, b) => {
    const dateA = new Date(a.cells[columnIndex].textContent.trim());
    const dateB = new Date(b.cells[columnIndex].textContent.trim());
    return dateA - dateB;
  });
  const tableBody = table.getElementsByTagName("tbody")[0];
  tableBody.innerHTML = "";
  sortedRows.forEach((row) => tableBody.appendChild(row));
}

// Function to Filter Table
function filterTable(tableId, searchBoxId) {
  const filter = document.getElementById(searchBoxId).value.toLowerCase();
  const rows = document
    .getElementById(tableId)
    .getElementsByTagName("tbody")[0].rows;
  Array.from(rows).forEach((row) => {
    const cells = row.cells;
    const match = Array.from(cells).some((cell) =>
      cell.textContent.toLowerCase().includes(filter)
    );
    row.style.display = match ? "" : "none";
  });
}
