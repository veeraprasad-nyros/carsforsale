<?php
		ini_set("diplay_errors","On");
		require("simple_html_dom.php");
		require("scrap.php");
		//html loading
		$html = file_get_html('https://www.carsforsale.com/austin-healey-for-sale-C184707');
		//dom creation
		$i=0;

		$servername = "localhost";
		$username = "root";
		$password = "root";
		$dbname = "carsforsale";
		
		$con = mysql_connect($servername,$username,$password);
		if(!$con){
			echo 'Error while connecting'.mysql_error();
		}
		mysql_select_db($dbname,$con);
		mysql_query('TRUNCATE TABLE cars_tbl');
		 mysql_close($con);

		foreach($html->find('ul[id="vehiclepage"]') as $ul){
			foreach($ul->find('li[class="vehicle-thumb col-xs-12 vehicle-list"]') as $li){
				#image url placed in src variable
				$imagecon = $li->find('div[class="vehicle-img"]');
				$src = $imagecon[0]->children[0]->src;
				#car name placed in cname variable
				$htag     = $li->find('a[class="vehicle-name ellipsis"] span[itemprop="name"]');
				$cname = $htag[0]->innertext;
				#price placed in pr vaiable 
				$price = $li->find('span[itemprop="price"]');
				$pr = str_replace($price[0]->children[0],"",$price[0]->innertext);
				$pr = str_replace(",","",$pr);
				#condition
				$condition = $li->find('span[itemprop="itemcondition"]');
				$cond = $condition[0]->innertext;
				#mileage				
				$mileage = $li->find('div[class="specs-miles"]');
				$mile = $mileage[1]->innertext;
				
				if($mile == ""){
					$mile = 'Mileage for email';	
				}
				#description
				$desc = $li->find('span[itemprop="description"]');
				$description = $desc[0]->innertext;
				$description = preg_replace("/&#?[a-z0-9]{2,8};/i","",$description);
				$description = preg_replace("/[^a-zA-Z0-9_.-\s]/", "", $description); 
				$description = filter_var($description, FILTER_SANITIZE_STRING);
				$description = str_replace("nbsp","",$description);
				$description = str_replace("-","",$description);
				if(count(trim(strval($description))) == 0){
					$description = 'No description available';	
				}
				if($src!=null){
					//$mimetype = mime_content_type($src);
					$obj = new BlobDemo();
					//$mime = mime_content_type($src);
					//echo 'ddd';
					$obj->insertBlob($cname,$pr,$cond,$mile,$description,$src);
				
				}

			}
		}
		
		/*testing
		$obj = new BlobDemo();
		for($i=1;$i<=24;$i++){
			$img = $obj->selectBlob($i);
			echo  '<img src="data:;base64,'.base64_encode($img['data']).'" />';
		}	
	   */
			
	?>