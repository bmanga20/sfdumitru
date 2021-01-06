<?php
//  @copyright	Copyright (C) 2013 IceTheme. All Rights Reserved
//  @license	Copyrighted Commercial Software 
//  @author     IceTheme (icetheme.com)

defined('_JEXEC') or die;

// Include main PHP template file
include_once(JPATH_ROOT . "/templates/" . $this->template . '/php/template.php'); 

if ((JRequest::getCmd("tmpl", "index") != "offline")) { ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>

<?php if ($it_params_responsive == 1) { ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php } ?>

<jdoc:include type="head" />

	<?php
    // Include CSS and JS variables 
    include_once(IT_THEME_DIR.'/php/stylesheet.php');
    ?>
    
</head>

<body class="<?php echo htmlspecialchars($pageclass)?>"> 

	<div id="main_wrapper">
    	
         <?php if ( (($it_params_welcome == 1) && ($user->guest !=1)) || ($it_mod_language != 0) || ($it_mod_topmenu != 0) )  { ?>   
         <section id="topbar">
         
             <div class="container">
            
                <?php if (($it_params_welcome == 1) && ($user->guest !=1)) { ?>       
                <p class="welcome_site floatleft">
                    <?php  echo JText::_('TPL_WELCOME') .' '. $sitename .', '. $user->name; ?>
                </p>
                <?php } ?>
                        
                <?php if ($it_mod_language != 0) { ?>
                <div id="language" class="floatright visible-desktop">
                    <jdoc:include type="modules" name="language" /> 
                </div>
                <?php } ?>
                
                <?php if (($it_mod_topmenu != 0)) { ?>
                <div id="topmenu" class="floatright">
                    <jdoc:include type="modules" name="topmenu" />
                </div>
                <?php } ?>   
                
            </div> 
            
        </section>
        <?php } ?>
        	
        <header id="header" class="container <?php if ($it_mod_slideshow == 0) { ?>no_slideshow<?php } ?>">
            
            <?php if ($it_mod_responsivebar != 0 && $it_params_responsive != 0) { ?>
            <div id="mobile-btn" class="floatleft hidden-desktop">
                    
                <a id="responsive-menu-button" class="btn btn-navbar" href="#responsivebar-inner">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
            </div>
            <script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery(".btn-navbar").pageslide({
					  direction: "right"
				});
			});
			</script>
            <?php } ?>   
            
            <?php if ($it_params_logo != "") { ?>
                <div id="logo" class="floatnone clearboth">	
                    <a href="<?php echo $this->baseurl ?>"><?php echo $it_params_logo_img; ?></a>        	
                </div>
            <?php } ?>
 
            <nav id="mainmenu" class="floatnone visible-desktop"> 
                    
                <div class="navbar">
                	
                    <div class="navbar-inner">
                        
                    	<jdoc:include type="modules" name="mainmenu" />
                        
                        <?php if ($it_mod_search != 0 ) { ?> 
                        <a href="#searchModal" class="search_icon" role="button" data-toggle="modal"><span>Search</span></a>
					    <?php } ?>
                        
                    </div>
                     
                </div>
                
            </nav>

        </header>
	

		<?php if ($it_mod_slideshow != 0) { ?>
        <div id="slideshow" class="clearboth">
            <jdoc:include type="modules" name="slideshow" />
        </div>
        <?php } ?> 


        <section id="content">
        
            <div class="container">
                
                <jdoc:include type="message" />
                
                <?php if ($it_mod_breadcrumbs != 0) { ?>        
                <div id="breadcrumbs">
                    <jdoc:include type="modules" name="breadcrumbs" />
                </div>
                <hr class="sep">
                <?php } ?> 
                    
                <?php if ($it_mod_promo != 0 ) { ?> 
                <div id="promo" class="animatedParent animateOnce" data-sequence="250" data-appear-top-offset="-150">
                    <div class="row-fluid">
                        <jdoc:include type="modules" name="promo" style="promo" />
                    </div>
                    <hr class="sep">
                </div>
                <?php } ?> 
                
                <?php if (!isset($it_hide_frontpage)) { ?>
                <div id="content_inner">
                
                    <div class="row-fluid">
                    
                        <div id="middlecol" class="floatleft <?php echo $it_content_span;?> <?php echo $it_sidebar_pos_class; ?>">
                        
                            <div class="inside"> 
                                <jdoc:include type="component" />
                            </div>
                            
                        </div>

                        <?php if ($it_mod_sidebar != 0) { ?> 
                            <div id="sidebar" class="<?php echo $it_sidebar_span;?> <?php echo $it_sidebar_pos_class; ?>" >
                                <div class="inside">  
                                    <jdoc:include type="modules" name="sidebar" style="sidebar" />
                                </div>
                            </div>
                        <?php } ?>

                    </div>   
                   
                </div>
                <?php } ?>
                
            </div>
                   
        </section><!-- / Content  -->
        
        <?php if (($it_mod_showcase || $it_mod_tagline) != 0) { ?>
        <section id="showcase" class="<?php if ($it_mod_tagline == 0 ) { ?>no-tagline<?php } ?> " data-stellar-background-ratio="0.5" data-stellar-vertical-offset="0">
                
            <div class="animatedParent animateOnce" data-appear-top-offset="-200" >
                    
                <div id="showcase_inner" class="container animated bounceInUp">
                    
                    <?php if ($it_mod_tagline != 0 ) { ?>
                    <div id="it_tagline" > 
                        <jdoc:include type="modules" name="tagline" style="xhtml" />
                    </div>    
                    <?php } ?> 
                
                    <?php if ($it_mod_showcase != 0 ) { ?>
                    <div id="showcase-modules"> 
                        <div class="row-fluid">
                            <jdoc:include type="modules" name="showcase" style="showcase" />
                        </div>
                    </div>    
                    <?php } ?> 
            
                </div>
                
            </div>
                         
        </section>
        <?php } ?>
        
        <footer id="footer">
        
            <div class="container">
            
                <?php if ($it_mod_testimonial != 0 ) { ?> 
                <section id="testimonials_wrapper">
               
                    <div class="container">
                        <div id="testimonials">
                            <jdoc:include type="modules" name="testimonial" />
                        </div> 
                    </div>   
                </section>
                
                <hr class="sep"/>
				   
					<?php if ($it_mod_testimonial > 1 ) { ?> 
					<script type="text/javascript">
					jQuery(document).ready(function() {
						jQuery("#testimonials").owlCarousel({
							nav : false,
							dots : true,
							dotsSpeed : 500,
							autoplay : true,
							autoplayTimeout: 5000,
							autoplaySpeed: 750,
							autoplayHoverPause: true,
							items:1,
							loop: true,
							mouseDrag: true,
							touchDrag: true,
							<?php if ($this->direction == 'rtl'){ echo ("rtl:true,"); }
								else { echo("rtl:false,");}
							?>
							
						})
					});
					</script>
					<?php } ?>
				
                <?php } ?>
        
                <?php if ($it_mod_footer != 0) { ?>
                <div class="row-fluid animatedParent animateOnce" data-sequence="250" data-appear-top-offset="-150">
                    <jdoc:include type="modules" name="footer" style="footer" />
                </div>
                <hr class="sep"/>
                <?php } ?>
                
            
                <div id="copyright">
                
                    <p class="copytext">
                         &copy; <?php echo date('Y');?> <?php echo $sitename. ""; ?> 
                         <?php // echo JText::_('TPL_RESERVED'); ?> 
                    </p> 
                    
                    <jdoc:include type="modules" name="footermenu" />
                    
                    <?php if ($it_params_icelogo != 0) { ?>
                    <div > 
                        <p id="icelogo" >
                            <a id"quicktest" href="http://www.icetheme.com" target="_blank" title="<?php echo $it_params_icelogo_title; ?>">IceTheme</a>
                        </p>
                    </div>    
                    <?php } ?>
                    
                </div>
    
            </div>
            
        </footer> 

       <?php if ($it_mod_responsivebar != 0 && $it_params_responsive != 0) { ?>
        <div id="responsivebar">
            <div id="responsivebar-inner">
                
                <?php if ($it_params_responsivebarlogo != "") { ?>
                <div id="responsivebarlogo">	
                   <a href="<?php echo $this->baseurl ?>"><?php echo $it_params_responsivebarlogo_img; ?></a>        	
                </div>
                <?php } ?>
                    
                <jdoc:include type="modules" name="responsivebar" style="xhtml" />
                
            </div>
        </div>    
        <?php } ?> 
        
        <?php if ($it_params_gotop != 0) { ?>
		<div id="gotop" class="">
			<a href="#" class="scrollup"><?php echo JText::_('TPL_SCROLL'); ?></a>
		</div>
		<?php } ?> 
        
        <?php if ($it_mod_search != 0 ) { ?> 
        <div id="searchModal" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
                        
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel"><?php echo $modal_search_title; ?></h3>
          </div>
         
         <div class="modal-body">
            <jdoc:include type="modules" name="search" />
          </div>
          
        </div>
    	<?php } ?> 
        
        <?php 
            // if ($it_params_styleswitcher != 0) { 
                // Include style switcher JS 
                include_once(IT_THEME_DIR.'/php/style_switcher.php');
            // }
        ?>

        <?php if (($it_mod_showcase || $it_mod_tagline) != 0) { ?>
        <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery.stellar({
                horizontalScrolling: false,
                responsive: true
            })
        });	
        </script>
        <?php } ?>
        
       <?php if ($it_mod_responsivebar != 0 && $it_params_responsive != 0) { ?>
		<script src="<?php echo IT_THEME; ?>/assets/js/jquery.pageslide.min.js" type="text/javascript"></script> 
		<?php } ?>
    
        <?php if ($it_params_advanced_animations == 1) { ?>  
        <script src="<?php echo IT_THEME; ?>/assets/js/css3-animate-it.min.js" type="text/javascript"></script>
        <?php } ?>
    
    </div><!-- /MainWrapper -->
    
    <jdoc:include type="modules" name="debug" />
	
</body>
</html>
<?php } ?>