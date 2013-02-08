<!DOCTYPE html>
<html lang="<?=Rum::app()->lang?>" >
<head>
<meta charset="<?=Rum::app()->charset?>" />
<title><?=$title?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
</head>
<body>

<div id="doc">
<?php echo \Rum::messages()?>
<?php $this->content() ?>
</div>

</body>
</html>