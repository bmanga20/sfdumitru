<?php
//  @copyright	Copyright (C) 2008 - 2014 IceTheme. All Rights Reserved
//  @license	Copyrighted Commercial Software 
//  @author     IceTheme (icetheme.com)

// No direct access.
defined('_JEXEC') or die;
?>

<?php 

// load complimentary css files
if ($it_mod_testimonial > 1 ) { 
	$document->addStyleSheet(IT_THEME. '/assets/css/owl.carousel.css');
}
if ($it_params_advanced_animations == 1) { 
	$document->addStyleSheet(IT_THEME. '/assets/css/animations.css');
}

// Load main Template CSS
$document->addStyleSheet(IT_THEME. '/assets/less/template.css');


if($it_params_responsive == 1) {
	$document->addStyleSheet(IT_THEME. '/assets/less/template_responsive.css');
}

?>



<link id="stylesheet" rel="stylesheet" type="text/css" href="<?php echo IT_THEME; ?>/assets/less/styles/<?php echo $templatestyle; ?>.css" />

<link rel="stylesheet" type="text/css" href="<?php echo IT_THEME; ?>/assets/css/custom.css" />


</style>

<style type="text/css" media="screen">


<?php if ($it_params_showcase_image !=""){ ?>
#showcase{
	background-image: url("<?php echo  JURI::base(true) ."/". $it_params_showcase_image; ?>");
}
@media screen and (-ms-high-contrast: active), (-ms-high-contrast: none) {
	#showcase {
		background-attachment:fixed!important;
		background-position:center!important;
	}
}
<?php } ?>	

/* Custom CSS code through template paramters */
<?php echo $it_params_custom_css; ?>

</style>


<!-- Google Fonts   -->
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300|Coming+Soon' rel='stylesheet' type='text/css' />


<!--[if lte IE 9]>
	<link rel="stylesheet" type="text/css" href="<?php echo IT_THEME; ?>/assets/css/ie9.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo IT_THEME; ?>/assets/css/animations-ie-fix.css" />
<![endif]-->


<style type="text/css">

</style>




