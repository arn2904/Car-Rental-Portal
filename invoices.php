<?php session_start(); 
include_once('includes/config.php');
// Code for login 
$ret2 = mysqli_query($con,'select kpa_trip.*,kpa_invoice.inv_date,kpa_invoice.inv_amount,kpa_invoice.inv_id from kpa_invoice left join kpa_trip ON kpa_trip.trip_id= kpa_invoice.trip_id where kpa_invoice.inv_id='.$_GET['invoice_id']); 
$row=mysqli_fetch_array($ret2);
$query=mysqli_query($con,"select * from kpa_vehicle where vin=".$row['vin']);
$query1=mysqli_query($con,"select * from kpa_vehicle where vin=".$row['vin']);
$row8=mysqli_fetch_array($query);
$sel = '<select name="corp" class="form-control">';
while($row1=mysqli_fetch_array($query1)){    
    $sel .='<option value="'.$row1['vin'].'">'.$row1['make'].' '.$row1['model'].'</option>';    
}
$sel .= '</select>';

$total_miles = $row['end_odo']-$row['st_odo'];
$query=mysqli_query($con,"select * from kpa_type where type_id=".$row8['type_id']);
$row5=mysqli_fetch_array($query);
$mile_rate_amount1 = $total_miles/$row5['ovr_mlg_rt'];
$mile_rate_amount = $mile_rate_amount1*$row5['rent_rate'];

$query1=mysqli_query($con,"SELECT * FROM `kpa_coupon` where cp_id=1");
$row9=mysqli_fetch_array($query1);

$percent = $row9['percent'];
$discount_value = ($mile_rate_amount / 100) * $percent;

$discount_price = $mile_rate_amount - $discount_value; 
if(isset($_POST['add']))
{
	/*$qr = 'Update kpa_invoice set inv_amount='.$_POST['amount'].' where inv_id='.$_POST['inv_id'];
	$ret=mysqli_query($con, $qr);

if($ret){
    header('Location:manageinvoices.php');
}*/
/*$ret = mysqli_query($con,'select max(cid) as cnt from kpa_customer '); 
$row = mysqli_fetch_array($ret);
$cid = $row['cnt'] + 1;*/
/*$ret2 = mysqli_query($con,'select max(trip_id) as cnt from kpa_trip '); 
$row2 = mysqli_fetch_array($ret2);
$tid = $row2['trip_id'] + 1;
$res = mysqli_query($con, 'SET GLOBAL FOREIGN_KEY_CHECKS=0;');
$qr = 'INSERT INTO `kpa_trip` (`trip_id`,`st_odo`, `end_odo`, `daily_limit`, `pic_date`, `drop_date`, `cid`, `vin`, `pick_loc`, `drop_loc`) VALUES ("'.$tid.'","'.$_POST['sodo'].'", "'.$_POST['endodo'].'", "20", "'.$_POST['pickdate'].'", "'.$_POST['dropdate'].'", "'.$_COOKIE['userid'].'","'.$_POST['corp'].'", "'.$_POST['pickloc'].'", "'.$_POST['droploc'].'" )';

$ret=mysqli_query($con, $qr);

if($ret){
    header('Location:dashboard.php');
}
else
{
    echo $qr;
echo "<script>alert('Error in query ');</script>";
//$extra="index.php";
//echo "<script>window.location.href='".$extra."'</script>";
exit();
}*/
}


$query2=mysqli_query($con,"select * from kpa_office where office_id=".$row['pick_loc']);
$sel2 = '<select name="pickloc" class="form-control" required>';
while($row2=mysqli_fetch_array($query2)){    
    $sel2 .='<option value="'.$row2['office_id'].'">'.$row2['street'].' '.$row2['city'].' '.$row2['state'].' '.$row2['country'].' '.$row2['zip'].'</option>';    
}
$query3=mysqli_query($con,"select * from kpa_office where office_id=".$row['drop_loc']);
$sel3 = '<select name="droploc" class="form-control" required>';
while($row3=mysqli_fetch_array($query3)){    
    $sel3 .='<option value="'.$row3['office_id'].'">'.$row3['street'].' '.$row3['city'].' '.$row3['state'].' '.$row3['country'].' '.$row3['zip'].'</option>';    
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>WOW carz</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>

    </head>
    <body class="sb-nav-fixed">
      <?php include_once('includes/navbar.php');?>
        <div id="layoutSidenav">
         <?php //include_once('includes/sidebar.php');?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">My invoice</h1>
                        <div class="card mb-4">
						<form method="post">
                            <div class="card-body">
                                <table class="table table-bordered">
                                   
                                   <tr>
                                       <th>Vehicle</th>
                                       <td><?php echo $sel; ?>

                                       </td>
                                   </tr>

                                   <tr>
                                    <th>Start Odometer </th>
                                       <td><input class="form-control" id="sodo" name="sodo" type="text" value="<?php echo $row['st_odo']; ?>" required /></td>
                                   </tr>
									<th>End Odometer </th>
                                       <td><input class="form-control" id="endodo" name="endodo" type="text" value="<?php echo $row['end_odo']; ?>" required /></td>
                                   </tr>
                                   <tr>
                                    <th>Pick Date </th>
                                       <td><input class="form-control" id="pickdate" name="pickdate" type="text" value="<?=date('Y-m-d H:i:s')?>" required /></td>
                                   </tr>
                                    <th>Drop Date </th>
                                       <td><input class="form-control" id="dropdate" name="dropdate" type="text" value="<?=date('Y-m-d H:i:s')?>" required /></td>
                                   </tr>

                                   <tr>
                                    <th>Pick Location </th>
                                       <td><?php echo $sel2; ?></td>
                                   </tr>
								   <input type="hidden" name="amount" value="<?php echo $discount_price; ?>">
								   <input type="hidden" name="inv_id" value="<?php echo $_GET['invoice_id']; ?>"> 
									<tr>
                                    <th>Drop Location </th>
                                       <td><?php echo $sel3; ?></td>
                                   </tr>
								   <th>Billing Details </th>
                                       <td width="auto">
									   <table class="table table-bordered">
										   <tr><td>Miles hours basis on rate</td><td><input readonly class="form-control" id="dropdate" name="dropdate" type="text" value="<?=$row5['ovr_mlg_rt']?>" required /><span style="color:red;"> Miles hours</span></td></tr>
										   <tr><td>Hours per miles rate</td><td><input readonly class="form-control" id="dropdate" name="dropdate" type="text" value="<?=$row5['rent_rate']?>" required /><span style="color:red;">Miles hours rate</span></td></tr>
										   <tr><td>Total miles in hours</td><td><input readonly class="form-control" id="dropdate" name="dropdate" type="text" value="<?=$mile_rate_amount1?>" required /></td></tr>
										   <tr><td>Total amount per miles</td><td><input readonly class="form-control" id="dropdate" name="dropdate" type="text" value="<?=$mile_rate_amount?>" required /></td></tr>
										   <tr><td>Discount</td><td><input readonly class="form-control" id="dropdate" name="dropdate" type="text" value="<?=$percent?>" required /> <span style="color:red;">in percentage</span></td></tr>
										   <tr><td>Discount total Amount</td><td><input readonly class="form-control" id="dropdate" name="dropdate" type="text" value="<?=$discount_value?>" required /></td></tr>
										   <tr><td>Total Amount</td><td><input readonly class="form-control" id="dropdate" name="dropdate" type="text" value="<?=$discount_price?>" required /></td></tr>
									   </table>
									   </td>
                                   </tr>
                                  
                                    </tbody>
                                </table>
                            </div>
                            </form>
                            
                        </div> 
                    </div>
                </main>
  <?php include('includes/footer.php');?>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>