<?php
/**
 * Import less css variable
*/
?>
<?php if(isset($_GET['@function_url'])):?>
	<?php foreach(explode(',',$_GET['@function_url']) as $file):?>
		@import <?php echo $file;?>;
	<?php endforeach;?>
<?php endif;?>
<?php if(isset($_GET['@variables_url'])):?>
	<?php foreach(explode(',',$_GET['@variables_url']) as $file):?>
		@import <?php echo $file;?>;
	<?php endforeach;?>
<?php endif;?>
<?php if(isset($_GET['additional_css_file'])):?>
	<?php foreach($_GET['additional_css_file'] as $file):?>
		@import "<?php echo $file;?>";
	<?php endforeach;?>
<?php endif;?>
<?php
    foreach($_GET as $typo => $value){
    	if($typo != 'additional_css_file')
    		echo "$typo:$value;";
    }
?>