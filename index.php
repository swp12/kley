<?php

error_reporting(NULL);

ob_start();


// Main include
include($_SERVER['DOCUMENT_ROOT']."/inc/main.php");
if (isset($_SESSION['user'])) {

	echo '<!DOCTYPE html>
<html lang="en">
<head>
  <title> WP Perekleyka</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="/css/styles.min.css">


</head>
<body>
  ';


    echo '<div class="container"><h2>WP Perekleyka</h2>';

    
    exec( "/usr/bin/sudo /usr/local/vesta/bin/v-sam-create-wp 1 2 3 2 2>&1", $outputu, $return_varu);
    
    $data = explode(' ',implode(' ', $outputu));
    //array_reverse($data);
    $current_version = file_get_contents("version");
    exec( "/usr/bin/sudo /usr/local/vesta/bin/v-sam-create-wp 1 2 3 3 2>&1", $outputv, $return_varv);
    $update = ($current_version == $outputv[3])?  "" : '<input type="button" onclick="wpupdate()" class="btn btn-default" value="Update">';
    echo "<p>Version: " . $current_version . " " . $update . "</p>"; 
	echo '<div  id="wpform">
            <div class="form-group">
            <label for="domain">Old Domain:</label>';
    echo '<select name="domain" id="olddomain" class="form-control">';
    

    foreach (array_reverse($data) as $dm) {
        echo '<option value="'.$dm.'">'.$dm.'</option>';
        
    }
    
    echo '</select></div>';

    echo "\n";
    echo ' <div class="form-group">
        <label for="newdomain">New Domain:</label>
        <input type="text" class="form-control" id="newdomain" placeholder="Enter new domain" name="newdomain" value="'.$newdomain.'"></div>  
        <input type="button" onclick="wpinstall()" class="btn btn-default" value="Full Perekleyka">
        <input type="button" onclick="wpinstallwithout()" class="btn btn-default" value="Perekleyka without nginx redirect">
        <br/>
        <div id="loading" style="display:none;"><p class="text-center"><img src="https://i.extraimage.info/pix/KLtQ0.gif" border="0"></p></div>
        <div id="output" style="word-wrap: break-word;"></div>
    ';
    
    while (@ ob_end_flush()); // end all output buffers if any

    $proc = popen($cmd, 'r');
    echo '<pre>';
    while (!feof($proc))
    {
        echo fread($proc, 4096);
        @ flush();
    }
    echo '</pre>';

?>
<script> 
function wpinstall() {
   
	var e = document.getElementById("olddomain");
	var newdomain = document.getElementById("newdomain").value;
    var domain = e.options[e.selectedIndex].value;

    var x = document.getElementById("loading");
    x.style.display = "block";

    data=samgrab('api.php?domain='+domain+'&newdomain='+newdomain+'');
    document.getElementById("output").innerHTML=data;
    x.style.display = "none";
}

function wpinstallwithout() {
   
	var e = document.getElementById("olddomain");
	var newdomain = document.getElementById("newdomain").value;
    var domain = e.options[e.selectedIndex].value;

    var x = document.getElementById("loading");
    x.style.display = "block";

    data=samgrab('api.php?domain='+domain+'&newdomain='+newdomain+'&without=1');
    document.getElementById("output").innerHTML=data;
    x.style.display = "none";
}

function wpupdate() {
    data=samgrab('api.php?action=update');
    document.getElementById("output").innerHTML=data;
    x.style.display = "none";
}

function samgrab(link){
    var result="";
    $.ajax({
        url:link,
        async:false,
        success:function(data){result=data;}
    });
    return result;
}

</script>
<?php }else{
	header("Location: /login/");
	
}
