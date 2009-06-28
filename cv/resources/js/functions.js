function addline(table, params) {

//    alert(table);
    var row = table.insertRow(table.rows.length);
    row.id = table.id+"_row"+table.rows.length;
    for(elem in params) {
        var cell = row.insertCell(row.cells.length);
        cell.innerHTML = "<input type='text' id="+row.id+"_"+elem+" />";
    }
}
