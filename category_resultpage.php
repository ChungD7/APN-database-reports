<!-- RESULTS PAGE -->
<?php 
include 'category_header1.php';
include 'category_header2.php';

$array222 = array_fill(0,8,0);
$season1Arr = ["winterArr", "springArr", "summerArr", "fallArr"];
$season2Arr = ["Winter", "Spring", "Summer", "Fall"];
$store_id = $_POST['store'];
$division_id = $_POST['division'];
$orderType_id = $_POST['ordertype'];
$status_id = $_POST['status'];
$salesRep_id = $_POST['salesRep'];
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
	$var2016 = routineSeasonly(2016, $store_id, $division_id, $orderType_id, $status_id, $salesRep_id);
}
if ($light2)
{
	$var2017 = routineSeasonly(2017, $store_id, $division_id, $orderType_id, $status_id, $salesRep_id);
}

?>
<html lang="en">
  <head>
  	<title>Category Report</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="/style.css">
	</head>
	<body>
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-4">
					<h2 class="heading-section">Category Report</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="table-wrap">
						<table class="table">
						  <thead class="thead-primary">
						    <tr>
						    	<th>Season</th>
								<th></th>
						    	<th>Best Category</th>
						    	<th>Best Sub-Categories</th>
								<th>More Best Categories </th>
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
								$temp = routineYearly($year, $store_id, $division_id, $orderType_id, $status_id, $salesRep_id);
								$returnArr = $temp[0];
								$holderArr = $temp[1];
								?>
								<tr class="alert" role="alert">
									<td style="font-size:14pt;"><?=$year . " ALL"?></td>
									<td></td>
									<td style="font-size:12pt;"><?=$holderArr[0]?></td>
									<td>
									<table cellspacing="0" cellpadding="0">
										<?php
										for ($i = 0; $i<5; $i++)
										{
											$ret = $returnArr[$holderArr[0]];
											if(!array_key_exists($i,$ret))
											{
												break;
											}?>
											<tr>
											<td style="font-size:10pt;"><?=$i+1 . ": " . $ret[$i]?></td></tr>
											<?php
										}?>
									</table>	
									</td>
									<td>
									<table cellspacing="0" cellpadding="0">
											<?php
											for ($i = 1; $i<6; $i++)
											{?>
												<tr>
												<td style="font-size:10pt;"><?=$i+1 . ": " . $holderArr[$i]?></td></tr>
												<?php
											}?>
									</table>	
									</td>
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
								$returnArr = $var2016[0];
								$holderArr = $var2016[1];
							}
							else 
							{
								$year = 2017;
								$returnArr = $var2017[0];
								$holderArr = $var2017[1];
							}
							
							$j  = $l % 4;
							?>
						    <tr class="alert" role="alert">
								<td style="font-size:14pt;"><?=$season2Arr[$j] . " " . $year?></td>
								<td></td>
								<td style="font-size:12pt;"><?=$holderArr[$season1Arr[$j]][0]?></td>
								<td>
								<table cellspacing="0" cellpadding="0">
										<?php
										for ($i = 0; $i<5; $i++)
										{
											$ret = $returnArr[$season1Arr[$j]][$holderArr[$season1Arr[$j]][0]];
											if(!array_key_exists($i,$ret))
											{
												break;
											}?>
											<tr>
											<td style="font-size:10pt;"><?=$i+1 . ": " . $ret[$i]?></td></tr>
											<?php
										}?>
								</table>	
								</td>
								<td>
								<table cellspacing="0" cellpadding="0">
										<?php
										for ($i = 1; $i<6; $i++)
										{?>
											<tr>
											<td style="font-size:10pt;"><?=$i+1 . ": " . $holderArr[$season1Arr[$j]][$i]?></td></tr>
											<?php
										}?>
								</table>	
								</td>
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

