function addline(table, params) {

//    alert(table);
    var row = table.insertRow(table.rows.length);
    row.id = table.id+"_row"+table.rows.length;
    var cell = row.insertCell(row.cells.length);
    var html = "<table>";
    for(elem in params) {
        html = html + "<tr><td>"+params[elem][0]+"</td><td>";
        if(params[elem][1] == 'textarea') {
            html = html + "<textarea id='"+row.id+"_"+params[elem][0]+"' cols='30' rows='5' name='"+row.id+"_"+params[elem][0]+"'>.</textarea>";
        } else {
            html = html + "<input type='text' id='"+row.id+"_"+params[elem][0]+"' name='"+row.id+"_"+params[elem][0]+"'/>";
        }
        html = html + "</td></tr>";
    }
    cell.innerHTML = html + "</table>";
} 
