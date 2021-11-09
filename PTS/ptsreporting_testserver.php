<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script language="javascript" type="text/javascript" src="js/jquery-3.5.1.min.js"></script>
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700&display=swap" rel="stylesheet"> 
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

<script>
jQuery(function() {
	jQuery(".btn-block .btn-sec").on('click',function() {
		jQuery(".response-success").html('');
	});
});

</script>
<script>

</script>
<!--<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700&display=swap');
</style> -->
<style>
body {
  font-family: Roboto, Helvetica, sans-serif;
  background-color: black;
}

* {
  box-sizing: border-box;
}

/* Add padding to containers */
.container {
  padding: 16px;
}
.container .form-block {
  background-color: white;
  width:1000px;
  padding:50px;
  display:block;
  margin:0 auto;
  }

/* Full-width input fields */
input[type=text], input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}
h1 {font-size:26px;line-height:36px;text-align:center;}

input[type=text]:focus, input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

/* Overwrite default styles of hr */
hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}

/* Set a style for the submit button */
.registerbtn {
  background-color: #015c89;
  color: white;
  padding: 16px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
}

.registerbtn:hover {
  opacity: 1;
}

.exportbtn {
  background-color: #015c89;
  color: white;
  padding: 16px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  opacity: 0.9;
  margin-left:800px;
}

.exportbtn:hover {
  opacity: 1;
}

/* Add a blue text color to links */
a {
  color: dodgerblue;
}

/* Set a grey background color and center the text of the "sign in" section */
.signin {
  background-color: #f1f1f1;
  text-align: center;
}
.form-cont {text-align:center;}
.btn-block {text-align:center;padding:30px 0;}
.btn-block input {width:200px;margin-left:20px;text-transform:uppercase;font-weight:bold;}
.btn-block input:first-child {margin-left:0px;}
.btn-block .btn-sec {background:#ccc;color:#000;}


.response-success table {width:100%;padding:0px;margin:0px}
.response-success table th {color:#fff; background:#333;padding:10px 15px;font-size:13px;}
.response-success table td {text-align:center;padding:10px 15px;font-size:13px;}
.response-success table tr:nth-child(2n+1) {background:#f1f1f1}
.response-success table tr:nth-child(2n+2) {background:#f9f9f9}
</style>
</head>
<body>

  <div class="container">
	<div class="form-block">
<form name="name1" method="post" action="" enctype="multipart/form-data">
		<h1>Load the PTS Generation Data</h1>
		<hr>
		<div class="form-cont">
			<label for="email"><b>Please Upload the Csv file</b></label>
			<input type="file" name="uploadfiles"  id="uploadfiles" accept=".csv,text/csv" class="required">
		</div>
		<div class="btn-block">
			<input type="submit" name="submit"  class="registerbtn" value="UPLOAD">
			<input type="button" name="cleardata"  class="registerbtn btn-sec" value="Clear Data">
		</div>
	</form>
	<p align="right">
 <button onclick="ExportToExcel('xlsx')">Export</button>
 
 </p>
<div  class="response-success" >
		<table id="datatable">
			<tr>
				<th>System Id</th>
				<th>Response Code</th>
			</tr>	
	<?php
		error_reporting(0);
		ini_set('display_errors',1);
		// Reading the csv file
$param = array();
$f = fopen($_FILES['uploadfiles']['tmp_name'], 'r');
while(!feof($f)) {
    $row = fgetcsv($f);
	
    if (!empty($row)) {		
		$param[]=array('fk_System'=>$row[0],'metervalue'=>$row[1],'meterDate'=>$row[2]);
	} 
   }
fclose($f);
		
$count =  count($param);
for($i=1;$i<=$count-1;$i++){	
				$site = "https://automatedreporting.masscec-pts.com/ptsstaging/ARPostTest";	
				$inputData = array('APIKey'=>'45da725bb1c748cfbc2ecf148426c1b4','systems'=>array($param[$i]));
				$inputparam = json_encode($inputData);
				$request_headers = array(
						"Ocp-Apim-Subscription-Key:45da725bb1c748cfbc2ecf148426c1b4" 
									  );
				
				$ch = curl_init($site);
				curl_setopt( $ch, CURLOPT_POSTFIELDS, $inputparam);
				curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
				$response = curl_exec( $ch );
				curl_close($ch);
				?>
				<tr>
				<td><?php echo $systemId = $param[$i]['fk_System'];?></td>
				<td><?php echo $response;?></td>
				</tr>
				
  <?php   }
?>
			</table>
		</div>
	
 </div>
  </div>
</body>

<script>
function ExportToExcel(type, fn, dl) {
       var elt = document.getElementById('datatable');
	   
       var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
       return dl ?
         XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
         XLSX.writeFile(wb, fn || ('PTS_response.' + (type || 'xlsx')));
    }
</script>
</html>