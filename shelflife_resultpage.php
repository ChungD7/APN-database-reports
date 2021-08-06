<!-- RESULTS PAGE -->
<?php 
include 'shelflife_header1.php';
include 'shelflife_header2.php';

$array222 = array_fill(0,8,0);
$season1Arr = ["winterArr", "springArr", "summerArr", "fallArr"];
$season2Arr = ["Winter", "Spring", "Summer", "Fall"];
$store_id = $_POST['store'];
$division_id = $_POST['division'];
$orderType_id = $_POST['ordertype'];
$status_id = $_POST['status'];
$salesRep_id = $_POST['salesRep'];
$shelf_date = $_POST['date'];
$light1 = false;
$light2 = false;

foreach($array222 as $idx => $num)
{
	$str = "season" . $idx;
	if(isset($_POST[$str]))
	{
		if($idx<4)
		{
			$light1 = true;
		}
		else
		{
			$light2 = true;
		}
		$array222[$idx] = 1;
	}
}
if ($light1)
{
	$var2016 = routineSeasonly(2016, $store_id, $division_id, $orderType_id, $status_id, $salesRep_id, $shelf_date);
}
if ($light2)
{
	$var2017 = routineSeasonly(2017, $store_id, $division_id, $orderType_id, $status_id, $salesRep_id, $shelf_date);
}

?>
<html lang="en">
  <head>
  	<title>Shelf-Life Report</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="/style.css">
	</head>
	<body>
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-4">
					<h2 class="heading-section">Shelf-Life Report</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="table-wrap">
						<table class="table">
						  <thead class="thead-primary">
						    <tr>
						    	<th>Season</th>
						    	<th>Top Grossing Style</th>
								<th>Category</th>
						    	<th>Shelf-Life</th>
						    </tr>
						  </thead>
						  <tbody>
						
						<?php 
						for($f = 0; $f < 2; $f++)
						{
							$str = "year" . $f;
							if(isset($_POST[$str]))
							{
								$year = 2016 + $f;
								$temp = routineYearly($year, $store_id, $division_id, $orderType_id, $status_id, $salesRep_id, $shelf_date);
								?>
								<tr class="alert" role="alert">
									<td style="font-size:14pt;"><?=$year . " ALL"?></td>
									<td style="font-size:12pt;"><?=$temp[0]?></td>
									<td>
										<div class="email">
											<span><?=$temp[2]['category']?></span>
											<span><?=$temp[2]['subCategory']?></span>
										</div>
									</td>
									<td style="font-size:12pt;"><?=$temp[1] . " Days"?></td>
								</tr>
							<?php
							}
						}
						
						for ($l=0;$l<8;$l++)
						{
							if (!$array222[$l]) 
							{
								continue;
							}
							else if ($l < 4)
							{
								$year = 2016;
								$returnArr = $var2016;
							}
							else 
							{
								$year = 2017;
								$returnArr = $var2017;
							}
							$j  = $l % 4;
							?>
						    <tr class="alert" role="alert">
								<td style="font-size:14pt;"><?=$season2Arr[$j] . " " . $year?></td>
								<td style="font-size:12pt;"><?=$returnArr[$season1Arr[$j]][0]?></td>
								<td>
									<div class="email">
										<span><?=$returnArr[$season1Arr[$j]][2]['category']?></span>
										<span><?=$returnArr[$season1Arr[$j]][2]['subCategory']?></span>
									</div>
								</td>
								<td style="font-size:12pt;"><?=$returnArr[$season1Arr[$j]][1] . " Days"?></td>
						    </tr>
						<?php
						}
						?>			
						</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
	</body>
</html>

