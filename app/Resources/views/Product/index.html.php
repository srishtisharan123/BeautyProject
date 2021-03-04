<?php
/**
 * @var \Pimcore\Templating\PhpEngine $this
 * @var \Pimcore\Templating\PhpEngine $view
 * @var \Pimcore\Templating\GlobalVariables $app
 */

 $this->extend('layout.html.php');
?>

<head>
<style>
* {
  box-sizing: border-box;
}
table {
  border-collapse: collapse;
  width: 100%;
} 

th,td {
  border: 1px solid white;
  text-align: left;
  padding: 8px;
}

th {
  background-color: #000000;
  color: white;
}


table.d {
  table-layout: fixed;
  width: 100%;  
}
tr:nth-child(even) {background-color: #f2f2f2;}

#myInput {
  background-image: url('/css/searchicon.png'); /* Add a search icon to input */
  background-position: 10px 12px; /* Position the search icon */
  background-repeat: no-repeat; /* Do not repeat the icon image */
  width: 200px; /* width */
  font-size: 16px; /* Increase font-size */
  padding: 12px 20px 12px 20px; /* Add some padding */
  border: 1px solid #ddd; /* Add a grey border */
  margin-bottom: 12px; /* Add some space below the input */
}

.pagination {
  display: inline-block;
}

.pagination a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
  transition: background-color .3s;
}

.pagination a.active {
  background-color: #4CAF50;
  color: white;
}

.pagination a:hover:not(.active) {background-color: #ddd;}

</style>
</head>
<input type="text" id="myInput" onkeyup="searchProductByName()" placeholder="Search for names..">
<!-- <button id="myBtn" onclick="myFunction()">Search</button> -->

<p><button onclick="sortTable()">Sort</button></p>
<h1><?= $this->input("headline", ["width" => 540]); ?></h1>

    <?php
    $brands = new \Pimcore\Model\DataObject\Brand\Listing();
    ?>
          <label for="brand">Choose a Brand:</label>
        <select name="brand" id="brand">
        <?php
        foreach($brands as $brand) 
        {
        ?>
            <option><?= $this->escape($brand->getName()); ?></option>
        <?php } ?>
        </select>
            <br><br>
            <button id="myBtn" onclick="filterFunction()">Filter</button>


<div class="product-info">
    <?php if($this->editmode):
        //echo $this->relation('product');
    else: ?>
    <div id="product">
    
        <?php
        
        $prod = new \Pimcore\Model\DataObject\Product\Listing();
        ?>
        <table class="d">
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Product Rating</th>
                <th>Brand Name</th>
            </tr>            
        </table>
        
       <table id="myTable" class="d">
       <?php
        foreach($prod as $product) 
        {
            ?>
            <tr>
            <td><?= $this->escape($product->getName()); ?></td>
            <td><?= $product->getDescription(); ?></td>
            <td><?= $product->getPrice(); ?></td>
            <td><?= $product->getRating(); ?></td>
            <td><?= $product->getBrand(); ?></td>
            </tr>
        <?php } ?>            
        </table>
        <!-- /** @var \Pimcore\Model\DataObject\Product $product */ -->
    </div>
    <?php endif; ?>
</div>
<br><br><br>
<!--		Start Pagination -->
<div class="pagination">
  <a href="#">&laquo;</a>
  <a href="#">1</a>
  <a href="#" class="active">2</a>
  <a href="#">3</a>
  <a href="#">4</a>
  <a href="#">5</a>
  <a href="#">6</a>
  <a href="#">&raquo;</a>
</div>
 <!-- 		End of Container -->

<script>
function searchProductByName() {
  // Declare variables
  var input, searchKey, table, rows, tableData, i, txtValue;
  input = document.getElementById("myInput");
  searchKey = input.value.trim().toUpperCase();
  table = document.getElementById("myTable");
  rows = table.getElementsByTagName("tr");
  
  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < rows.length; i++) {
    tableData = rows[i].getElementsByTagName("td")[0];
    if (tableData) {
      txtValue = tableData.textContent || tableData.innerText;
      if (txtValue.toUpperCase().indexOf(searchKey) > -1) {
        rows[i].style.display = "";
      } else {
        rows[i].style.display = "none";
      }
    }
  }
}


function filterFunction() {
  var input, searchKey, table, rows, tableData, i, txtValue;
  input = document.getElementById("brand");
  searchKey = input.value.trim().toUpperCase();
  table = document.getElementById("myTable");
  rows = table.getElementsByTagName("tr");
  
  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < rows.length; i++) {
    tableData = rows[i].getElementsByTagName("td")[4];
    if (tableData) {
      txtValue = tableData.textContent || tableData.innerText;
      if (txtValue.toUpperCase().indexOf(searchKey) > -1) {
        rows[i].style.display = "";
      } else {
        rows[i].style.display = "none";
      }
    }
  }
}


function sortTable() {
  var table, rows, switching, i, x, y, shouldSwitch;
  table = document.getElementById("myTable");
  switching = true;
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /*Loop through all table rows */
    for (i = 0; i < rows.length; i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("td")[0];
      y = rows[i + 1].getElementsByTagName("td")[0];
      //check if the two rows should switch place:
      if (x.innerHTML.toUpperCase() > y.innerHTML.toUpperCase()) {
        //if so, mark as a switch and break the loop:
        shouldSwitch = true;
        break;
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
    }
  }
}
</script>

    <?php while ($this->block("contentblock")->loop()) { ?>
        <h2><?= $this->input("subline"); ?></h2>
        <?= $this->wysiwyg("content"); ?>
    <?php } ?>