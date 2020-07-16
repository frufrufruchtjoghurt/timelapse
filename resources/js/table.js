function searchUser(table, filter, search_list) {
  const table_body = table.tBodies[0];
  const tr = Array.from(table_body.querySelectorAll("tr"));

  for (let i = 0; i < tr.length; ++i)
  {
    const td = tr[i].querySelectorAll("td");
    let txt_value = "";
    search_list.forEach(item => {
      txt_value += (td[item].textContent || td[item].innerText);
    });

    if (txt_value.toUpperCase().indexOf(filter) > -1)
    {
      tr[i].style.display = "";
    }
    else
    {
      tr[i].style.display = "none";
    }
  }
}

document.querySelectorAll(".search-user").forEach(input_field => {
  input_field.addEventListener("keyup", () => {
    const table_element = document.querySelector("table");
    const filter = input_field.value.toUpperCase();
    let search_cols = Array();
    table_element.querySelectorAll(".searchable").forEach(col => {
      search_cols.push(Array.prototype.indexOf.call(col.parentElement.children, col));
    });

    searchUser(table_element, filter, search_cols);
  });
});

function sortTable(table, column, asc = true)
{
  const dir_mod = asc ? 1 : -1;
  const table_body = table.tBodies[0];
  const rows = Array.from(table_body.querySelectorAll("tr"));

  const sorted_rows = rows.sort((a, b) => {
    const a_col_text = a.querySelectorAll("td")[column].textContent.trim();
    const b_col_text = b.querySelectorAll("td")[column].textContent.trim();

    return a_col_text > b_col_text ? (1 * dir_mod) : (-1 * dir_mod);
  });

  while (table_body.firstChild)
  {
    table_body.removeChild(table_body.firstChild);
  }

  table_body.append(...sorted_rows);

  table.querySelectorAll("th").forEach(th => th.classList.remove("th-sort-asc", "th-sort-desc"));
  table.querySelector(`th:nth-child(${ column + 1 })`).classList.toggle("th-sort-asc", asc);
  table.querySelector(`th:nth-child(${ column + 1 })`).classList.toggle("th-sort-desc", !asc);
}

document.querySelectorAll(".table-sortable th").forEach(header_cell => {
  if (!header_cell.classList.contains("no-sort"))
  {
    header_cell.addEventListener("click", () => {
      const table_element = header_cell.parentElement.parentElement.parentElement;
      const header_index = Array.prototype.indexOf.call(header_cell.parentElement.children, header_cell);
      const is_ascending = header_cell.classList.contains("th-sort-asc");

      sortTable(table_element, header_index, !is_ascending);
    });
  }
});
