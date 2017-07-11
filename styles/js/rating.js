$(document).ready(function () {
        $("#star-group1").hide();
        $("#star-group2").hide();
        $("#write1").click(function(){
            $("#star-group1").toggle().addClass("formstyle");
            $("#star-group2").hide();
        });
        $("#write2").click(function(){
            $("#star-group2").toggle().addClass("formstyle");
            $("#star-group1").hide();
        });


        $("#rated2 .stars").click(function () {
      
            $val = $(this).val();
            //alert($val);
            $('#rated2~#guestrating2').html($val);
            $("#rated2 .stars").attr("checked");

        });
        $("#rated1 .stars").click(function () {
          
            $("#rated1 .stars").attr("checked");
            $val = $(this).val();
            //alert($val);
            $('#rated1~#guestrating1').html($val);
            $("#rated1 .stars").attr("checked");
        }); 

        $("#post1").click(function(){
            $identifier = $("#post1").attr("name");
            $guestrating = parseInt($("#"+$identifier+" #guestrating1").html());
            $guestname = $("#"+$identifier+" #user1").val();
            $productid = $("#"+$identifier+" #product_id1").val();
            if($guestrating != 0){
                if($guestname != ""){
                    $.post('ratings.php',{name:$guestname,car_id:$productid,rate:$guestrating},function(d){
                        if(d == 1){
                            alert("Thanks for rating");
                            $("#star-group1").hide();

                            $avgrate1 = parseInt($("#avgrate1").html());
                            $favgrate1 = ($avgrate1 + $guestrating)/2;
                            $('#ratedd1 input[value='+Math.ceil($favgrate1)+']').attr("checked");
                            $review = parseInt($('#review1').html())+1;
                            $('#review1').html($review);
                            $("#avgrate1").html($favgrate1);

                            //window.opener.history.go(0);
                            //window.location.reload(true);
                            //window.location.href = "index.php";
                        }else{
                            alert("You already rated");
                        }
                    });

                }else{
                   $("#"+$identifier+" #user1").focus();
                }
            }else{
                alert("Please select the rating");
            }
           
        });  
        $("#post2").click(function(){
            $identifier = $("#post2").attr("name");
            $guestrating = parseInt($("#"+$identifier+" #guestrating2").html());
            $guestname = $("#"+$identifier+" #user2").val();
            $productid = $("#"+$identifier+" #product_id2").val();
            if($guestrating != 0){
                if($guestname != ""){
                    $.post('ratings.php',{name:$guestname,car_id:$productid,rate:$guestrating},function(d){
                        if(d == 1){
                            alert("Thanks for rating");
                            $("#star-group2").hide();


                            $avgrate2 = parseInt($("#avgrate2").html());
                            $favgrate2 = ($avgrate2 + $guestrating)/2;
                            $('#ratedd2 input[value='+Math.ceil($favgrate2)+']').attr("checked");
                            $review1 = parseInt($('#review2').html())+1;
                            $('#review2').html($review1);
                            $("#avgrate2").html($favgrate2);


                            //window.opener.history.go(0);
                            //window.location.reload(true);
                            //window.location.href = "index.php";
                        }else{
                            alert("You already rated");
                        }
                    });

                }else{
                   $("#"+$identifier+" #user2").focus();
                }
            }else{
                alert("Please select the rating");
            }
           
        });
});
