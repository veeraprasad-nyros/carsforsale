<?php
    ini_set('display_errors', 1);
	require_once("perpage.php");	
	require_once("dbcontroller.php");
	$db_handle = new DBController();
	
	$name = "";
	
	$queryCondition = "";
	if(!empty($_POST["search"])) {
		foreach($_POST["search"] as $k=>$v){
			if(!empty($v)) {
				$queryCondition .= "AND (c_name LIKE '%" . $v . "%' OR "."c_price LIKE '%" . $v . "%')";
				$name = $v;
			}
			
		}
		
	}
	$orderby = " ORDER BY id desc"; 
	$sql = "SELECT id, c_src, c_name, c_price, c_price, c_cond, c_mile, c_desc, review, avg, ceil(avg) rated FROM cars_tbl,(select car_id,count(car_id) review,round(sum(rate)/count(car_id),1) avg from review_tbl group by car_id) reviewtbl where cars_tbl.id = reviewtbl.car_id " . $queryCondition;
	// $sql = "SELECT id, c_src, c_name, c_price, c_price, c_cond, c_mile, c_desc, review, avg, ceil(avg) rated FROM cars,(select car_id,count(car_id) review,round(sum(rate)/count(car_id),1) avg from review_tbl group by car_id) reviewtbl where cars.id = reviewtbl.car_id " . $queryCondition;
	$href = 'index.php';					
		
	$perPage = 2; 
	$page = 1;
	if(isset($_POST['page'])){
		$page = $_POST['page'];
	}
	$start = ($page-1)*$perPage;
	if($start < 0) $start = 0;
		
	$query =  $sql . $orderby .  " limit " . $start . "," . $perPage; 
	$result = $db_handle->runQuery($query);
	
	if(!empty($result)) {
		$result["perpage"] = showperpage($sql, $perPage, $href);
	}
?>
<html>
	<head>
	<title>Php scraping</title>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<link href="styles/css/main.css" type="text/css" rel="stylesheet" />
	<link href="styles/css/ratings.css" type="text/css" rel="stylesheet" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="styles/js/rating.js" ></script>
	</head>
	<body>
				
    <div id="toys-grid" class= 'container'>      
			<form name="frmSearch" method="post" action="index.php" autocomplete='off'>
			<div class="input-group">
			  <input type="text" class="form-control" placeholder="Name" name="search[name]" class="demoInputBox" value="<?php echo $name; ?>" aria-describedby="basic-addon2">
			  <span class="input-group-btn">
		        <button class="btn btn-default"  type="submit" name="sumbit" value="Search">Search</button>
		      </span>

			</div>
						
			
				<div class = "content">
					<?php
						foreach($result as $k=>$v) {
						if(is_numeric($k)) {
					?>
					<div class = 'row'>
	          			<div class="col-lg-6 col-sm-6 txtalign">
						 <?php echo '<img  src="data:;base64,'.base64_encode($result[$k]["c_src"]).'" />'; ?> 
						<!-- <img  src="<?php echo $result[$k]["c_src"]?>" />;  -->

						</div>
						<div class="col-lg-6 col-sm-6">

	          			<h5><a href="#"><?php echo $result[$k]["c_name"]; ?></a></h5>
						<a class='btn btn-danger btn-custom'>$ <?php if(intval($result[$k]["c_price"]) == 0){ echo "Price for email";}else{ echo number_format($result[$k]["c_price"], 2);} ?></a>
						<div class = 'detail'><span class= 'condition'>Condition: </span><span class = 'condstly'><?php echo $result[$k]["c_cond"]; ?></span></div>
						<div class = 'detail'><span class= 'mileage'>mileage: </span><span class = 'condstly'><?php echo $result[$k]["c_mile"]; ?></span></div> 
						<div class = 'des'><span class= 'description'>description:</span><p><?php echo $result[$k]["c_desc"]; ?></p></div> 
						<!--rated block started-->
						<div class='star-group' id="sg<?php echo ($k+1); ?>">
								<fieldset id='ratedd<?php echo ($k+1); ?>' class="rating">
							    <input class="stars" type="radio" id="stard<?php if($k==0){echo 5;}if($k==1){echo 10;} ?>" name="ratingd<?php echo ($k+1); ?>" value="5" />
							    <label class = "full" for="stard<?php if($k==0){echo 5;}if($k==1){echo 10;} ?>" title="Awesome - 5 stars"></label>
							    <input class="stars" type="radio" id="stard<?php if($k==0){echo 4;}if($k==1){echo 9;} ?>" name="ratingd<?php echo ($k+1); ?>" value="4" />
							    <label class = "full" for="stard<?php if($k==0){echo 4;}if($k==1){echo 9;} ?>" title="Pretty good - 4 stars"></label>
							    <input class="stars" type="radio" id="stard<?php if($k==0){echo 3;}if($k==1){echo 8;} ?>" name="ratingd<?php echo ($k+1); ?>" value="3" />
							    <label class = "full" for="stard<?php if($k==0){echo 3;}if($k==1){echo 8;} ?>" title="Meh - 3 stars"></label>
							    <input class="stars" type="radio" id="stard<?php if($k==0){echo 2;}if($k==1){echo 7;} ?>" name="ratingd<?php echo ($k+1); ?>" value="2" />
							    <label class = "full" for="stard<?php if($k==0){echo 2;}if($k==1){echo 7;} ?>" title="Kinda bad - 2 stars"></label>
							    <input class="stars" type="radio" id="stard<?php if($k==0){echo 1;}if($k==1){echo 6;} ?>" name="ratingd<?php echo ($k+1); ?>" value="1" />
							    <label class = "full" for="stard<?php if($k==0){echo 1;}if($k==1){echo 6;} ?>" title="Sucks big time - 1 star"></label>
								<script>
									$(document).ready(function () {
										$('#ratedd<?php echo ($k+1); ?> input[value="<?php echo $result[$k]["rated"]; ?>"]').attr("checked","checked");
   										$('#ratedd<?php echo ($k+1); ?>').attr("disabled","disabled");
									});
								</script>
							</fieldset>&nbsp;&nbsp;
							<span id="guestrated<?php echo ($k+1); ?>"><?php echo $result[$k]["rated"]." star rated"; ?></span> 
							<div class="userrate"></div>
							<div class="row">
								<div class ="col-lg-6">
									<table>
										<tr>
											<td>
												<label>Reviewed :</label>
											</td>
											<td>
												<span id="review<?php echo ($k+1); ?>"><?php echo $result[$k]["review"] ?></span>
											</td>
										</tr>
										<tr>
											<td>
												<label>Average rate :</label>
											</td>
											<td>
												<span id="avgrate<?php echo ($k+1); ?>"><?php echo $result[$k]["avg"] ?></span>
											</td>
										</tr>
									</table>
								</div>
								<div class="col-lg-6 ratedblock">
									<input type="button" value="Write Review/Rate" class='custom-btn1' id="write<?php echo ($k+1); ?>">
								</div>
							</div>
						</div>

						<!--rated block ended-->
						<!--rating block started-->
						<div id="star-group<?php echo ($k+1); ?>">
						<span>Writing rate of review :</span>
						<div class='star-group' >
							<fieldset id='rated<?php echo ($k+1); ?>' class="rating">
							    <input class="stars" type="radio" id="star<?php if($k==0){echo 5;}if($k==1){echo 10;} ?>" name="rating<?php echo ($k+1); ?>" value="5" />
							    <label class = "full" for="star<?php if($k==0){echo 5;}if($k==1){echo 10;} ?>" title="Awesome - 5 stars"></label>
							    <input class="stars" type="radio" id="star<?php if($k==0){echo 4;}if($k==1){echo 9;} ?>" name="rating<?php echo ($k+1); ?>" value="4" />
							    <label class = "full" for="star<?php if($k==0){echo 4;}if($k==1){echo 9;} ?>" title="Pretty good - 4 stars"></label>
							    <input class="stars" type="radio" id="star<?php if($k==0){echo 3;}if($k==1){echo 8;} ?>" name="rating<?php echo ($k+1); ?>" value="3" />
							    <label class = "full" for="star<?php if($k==0){echo 3;}if($k==1){echo 8;} ?>" title="Meh - 3 stars"></label>
							    <input class="stars" type="radio" id="star<?php if($k==0){echo 2;}if($k==1){echo 7;} ?>" name="rating<?php echo ($k+1); ?>" value="2" />
							    <label class = "full" for="star<?php if($k==0){echo 2;}if($k==1){echo 7;} ?>" title="Kinda bad - 2 stars"></label>
							    <input class="stars" type="radio" id="star<?php if($k==0){echo 1;}if($k==1){echo 6;} ?>" name="rating<?php echo ($k+1); ?>" value="1" />
							    <label class = "full" for="star<?php if($k==0){echo 1;}if($k==1){echo 6;} ?>" title="Sucks big time - 1 star"></label>
							</fieldset>&nbsp;&nbsp;
							<span id="guestrating<?php echo ($k+1); ?>">0</span><span>&nbsp; star</span>
							<div class="userrate">
								<input type="text" placeholder='Enter your name' id="user<?php echo ($k+1); ?>">
								<input type="button" value="Add Review/Rate" class='custom-btn' name='star-group<?php echo ($k+1); ?>' id = "post<?php echo ($k+1); ?>">
								<input type="hidden" id = "hidden<?php echo ($k+1); ?>" value="0">
								<input type="hidden" id="product_id<?php echo ($k+1); ?>" value="<?php echo $result[$k]["id"]; ?>"> 
							</div>
						</div>
						</div>
						<!--rating block ended-->

						</div>
					</div>
					<?php
						}
					}
					?>
				
				</div>
					<?php
					
					if(isset($result["perpage"])) {
					?>
					
					<div style="text-align:center;"> <?php echo $result["perpage"]; ?></div>
					
					<?php }
					else{ ?>
					<div><h4 style="color:gray;"> No information found!!</h4></div>
					<?php
					}?>
				
			</form>	
		</div><!-- container ended-->

	</body>
</html>