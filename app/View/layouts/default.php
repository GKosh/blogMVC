<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>My home page</title>
</head>
<body style="">
<div style="float:left;margin:50px 10px 10px 10px; dyspaly: inline; width:15%; id="menu" >
<?php
echo $this->model->data['Menu'];
?>
</div >

<div style="float:left; margin:10px 10px 10px 10px; dyspaly: inline; width:80%">
<?php
echo $this->model->data['article'];

?>
</div>

