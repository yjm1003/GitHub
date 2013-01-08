<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Strava</title>

</head>

<body>

<?php 
	echo form_open('strava/get_information');
	
	$club_id = array("id" => "club_id", "name" => "club_id", "value" => set_value("club_id"));
?>

	<label>Club ID: </label>
	<?php echo form_input($club_id); ?>
	
	<?php echo validation_errors(); ?>
	<?php echo form_submit("submit", "Submit"); ?>
	<?php echo form_close(); ?>
	
	<span>
		<?php if(!empty($info)) {
			echo $info;
		} else {
			if(!empty($error)) {
				echo $error;
			}	
		}
		?>	
	</span>

</body>
</html>