<?php 
error_reporting(E_ALL);
 ini_set('display_errors', 1);
?>
<form action="http://localhost/exporm/client?method=upload_survey_data" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="file" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>