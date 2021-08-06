<!DOCTYPE html>
<html>
<!-- PLACEHOLDER FILTER PAGE SET UP ONLY TO INPUT CONDITIONS -->
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
<style>
.dropdown-check-list {
  display: inline-block;
}

.dropdown-check-list .anchor {
  position: relative;
  cursor: pointer;
  display: inline-block;
  padding: 5px 50px 5px 10px;
  border: 1px solid #ccc;
}

.dropdown-check-list .anchor:after {
  position: absolute;
  content: "";
  border-left: 2px solid black;
  border-top: 2px solid black;
  padding: 5px;
  right: 10px;
  top: 20%;
  -moz-transform: rotate(-135deg);
  -ms-transform: rotate(-135deg);
  -o-transform: rotate(-135deg);
  -webkit-transform: rotate(-135deg);
  transform: rotate(-135deg);
}

.dropdown-check-list .anchor:active:after {
  right: 8px;
  top: 21%;
}

.dropdown-check-list ul.items {
  padding: 2px;
  display: none;
  margin: 0;
  border: 1px solid #ccc;
  border-top: none;
}

.dropdown-check-list ul.items li {
  list-style: none;
}

.dropdown-check-list.visible .anchor {
  color: #0094ff;
}

.dropdown-check-list.visible .items {
  display: block;
}

.center {
  margin-left: auto;
  margin-right: auto;
}
</style>
</head>
<body class="bg-light">
<div class="container">
 

<div class="py-5 text-center">
<h2>Category Sales Report</h2>
</div>

<form name="frmContact" class="needs-validation" method="post" action="category_resultpage.php">
<table class="center" style = "width:50%;">
<tr>
  <td>
   <label for="store">Store:</label>
  <select name="store" id="store" required>
    <option value = "0"></option>
    <option style="font-size:10pt;" value="3">@SHOW</option>
    <option style="font-size:10pt;" value="2">HQ</option>
    <option style="font-size:10pt;" value="1">Wishlist</option>
  </select>
</td>
<td></td>
<td>
  <label for="division">Division:</label>
  <select name="division" id="division" style="max-width:90px;" required>
    <option value = "0"></option>
    <option style="font-size:10pt;" value="1">CHAS GROUP INC. -dba WISHLIST</option>
    <option style="font-size:10pt;" value="3">MADE ON EARTH</option>
    <option style="font-size:10pt;" value="2">WISHLIST</option>
  </select>
</td>
<td></td>
<td>
<label for="ordertype">Order Type:</label>
  <select name="ordertype" id="ordertype" required>
    <option value = "temp"></option>
    <option style="font-size:10pt;" value="Regular">Regular</option>
    <option style="font-size:10pt;" value="Pre-Invoice">Pre-Invoice</option>
  </select>
</td>
</tr>

<tr>
    <td>
        <br/>
    </td>
</tr>

<tr>
<td>
<label for="status">Status:</label>
  <select name="status" id="status" required>
    <option value = "6"></option>
    <option style="font-size:10pt;" value="0">Active</option>
    <option style="font-size:10pt;" value="4">Picking</option>
    <option style="font-size:10pt;" value="3">Shipped</option>
    <option style="font-size:10pt;" value="1">Completed</option>
  </select>
</td>
<td></td>
<td>
<label for="salesRep">Sales Rep:</label>
  <select name="salesRep" id="salesRep" required>
    <option value = "0"></option>
    <option style="font-size:10pt;" value="2">In House</option>
    <option style="font-size:10pt;" value="1">RICKY</option>
    <option style="font-size:10pt;" value="5">LS</option>
    <option style="font-size:10pt;" value="6">FG</option>
    <option style="font-size:10pt;" value="7">WEB</option>
    <option style="font-size:10pt;" value="8">DIEGO</option>
    <option style="font-size:10pt;" value="9">TONY</option>
    <option style="font-size:10pt;" value="10">WONJI</option>
    <option style="font-size:10pt;" value="11">SHOW</option>
    <option style="font-size:10pt;" value="14">OS</option>
    <option style="font-size:10pt;" value="15">ASD8.16</option>
    <option style="font-size:10pt;" value="16">JOJO</option>
    <option style="font-size:10pt;" value="18">MARITZA</option>
    <option style="font-size:10pt;" value="19">CHRISTINE</option>
    <option style="font-size:10pt;" value="20">BRIAN</option>
    <option style="font-size:10pt;" value="21">ASD3.17</option>
    <option style="font-size:10pt;" value="22">CHI4.17</option>
    <option style="font-size:10pt;" value="23">ATL4.17</option>
    <option style="font-size:10pt;" value="24">SANDRA</option>
    <option style="font-size:10pt;" value="25">ATL6.17</option>
  </select>
</td>
<td></td>
<td><input type="submit"></td>
</tr>

<tr>
    <td>
        <br/>
    </td>
</tr>

<tr>
  <td>
<div id="list1" class="dropdown-check-list" tabindex="100">
  <span class="anchor">Year/Season</span>
  <ul class="items">
    <li><input type="checkbox" name="year0" id="year0" />2016 ALL</li>
    <li><input type="checkbox" name="year1" id="year1" />2017 ALL</li>
    <li><input type="checkbox" name="season0" id="season0" />2016 WINTER</li>
    <li><input type="checkbox" name="season1" id="season1" />2016 SPRING</li>
    <li><input type="checkbox" name="season2" id="season2" />2016 SUMMER</li>
    <li><input type="checkbox" name="season3" id="season3" />2016 FALL</li>
    <li><input type="checkbox" name="season4" id="season4" />2017 WINTER</li>
    <li><input type="checkbox" name="season5" id="season5" />2017 SPRING</li>
    <li><input type="checkbox" name="season6" id="season6" />2017 SUMMER</li>
    <li><input type="checkbox" name="season7" id="season7" />2017 FALL</li>
  </ul>
</div>
</td>
</tr>

</table>
</form>
</div>

<script>
var checkList = document.getElementById('list1');
checkList.getElementsByClassName('anchor')[0].onclick = function(evt) {
  if (checkList.classList.contains('visible'))
    checkList.classList.remove('visible');
  else
    checkList.classList.add('visible');
}
</script>

</body>
</html>