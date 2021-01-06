<?php
//  @copyright	Copyright (C) 2013 IceTheme. All Rights Reserved
//  @license	Copyrighted Commercial Software 
//  @author     IceTheme (icetheme.com)

defined('_JEXEC') or die;

include_once(JPATH_ROOT . "/templates/" . $this->template . '/php/template.php'); ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>

	<jdoc:include type="head" />
    
    <?php if ($it_params_responsive ==1) { ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php } ?>

    <?php
    // Include CSS and JS variables 
    include_once(JPATH_ROOT . "/templates/" . $this->template . '/php/stylesheet.php');
    ?>
    
</head>

<body class="offline_page">

	<?php if ($it_params_logo != "") { ?>
    <div id="logo_page">	
        <a href="<?php echo $this->baseurl ?>"><?php echo $it_params_logo_img; ?></a>	
    </div>
    <?php } ?>   
    

    <div id="content_page">
    
 		<jdoc:include type="message" />
        
        <h2><?php echo $it_params_offline_h2; ?></h2>
        
        <?php if ($it_params_offline_count == 1) { ?> 
        <?php  $document->addScript(IT_THEME .'/assets/js/countdown.min.js'); ?> 
		<script type="text/javascript">
        jQuery(document).ready(function(){
            jQuery("#countdown").countdown({
                date: "<?php echo $it_params_offline_count_time; ?>",
                format: "on"
            },
            
            function() {
                // callback function
            });
        });
        
        </script>
        
         <ul id="countdown">
            <li>
                <span class="days">00</span>
                <p class="timeRefDays">days</p>
            </li>
            <li>
                <span class="hours">00</span>
                <p class="timeRefHours">hours</p>
            </li>
            <li>
                <span class="minutes">00</span>
                <p class="timeRefMinutes">minutes</p>
            </li>
            <li>
                <span class="seconds">00</span>
                <p class="timeRefSeconds">seconds</p>
            </li>
        </ul>
        <?php } ?> 
         
         
         <?php if ($it_params_offline_p !="") { ?>
         <div class="alert alert-info">
            <?php echo $it_params_offline_p; ?>
        </div>
   		<?php } ?> 
        
		<?php echo $it_params_offline_newsletter; ?>
        
        <?php if ($it_params_offline_social !="") { ?>
        <hr>
        <div id="offline_social">
        	<?php echo $it_params_offline_social; ?>
        </div>
        <?php } ?> 
        
    	
        <?php if ($it_params_offline_form == 1) { ?> 
        <div id="offline_form_msg" class="well">
		<h5><?php echo JText::_('Are you an Administrator of this website?'); ?></h5>
        <a class="btn icebtn" href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>#offline_login" onclick="jQuery('#offline_login').toggle();">Admin Login!</a>
		</div>
        
        <div id="offline_login" class="well">
        
            <form action="<?php echo JRoute::_('index.php', true); ?>" method="post" id="form-login" class="form-horizontal">
                
                <fieldset class="input">
                
                 <div class="control-group">
                  <label class="control-label" for="inputEmail"><?php echo JText::_('JGLOBAL_USERNAME') ?></label>
                  <div class="controls">
                    <input name="username" id="username" type="text" class="inputbox" alt="<?php echo JText::_('JGLOBAL_USERNAME') ?>" size="18" style="position: relative; font-size: 14px; line-height: 20px; " />
                     </div>  
                  </div>
                  
                  <div class="control-group">
                  <label class="control-label" for="inputEmail"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
                  <div class="controls">
                    <input name="password" id="passwd" type="password" class="inputbox" alt="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" size="18" style="position: relative; font-size: 14px; line-height: 20px; " />
                      </div>
                  </div>
              
              <div class="control-group">
                  <div class="controls">
                    <label for="remember" class="checkbox"> <input type="checkbox" name="remember" value="yes" id="remember" /> <?php echo JText::_('JGLOBAL_REMEMBER_ME') ?>  </label>
                    
                  </div>
                </div>
                
                 <div class="control-group">
                  <div class="controls">
                                      <input type="submit" name="Submit" class="btn"  value="<?php echo JText::_('JLOGIN') ?>" />
    
                  </div>
                </div>
                
            
                <input type="hidden" name="option" value="com_users" />
                <input type="hidden" name="task" value="user.login" />
                <input type="hidden" name="return" value="<?php echo base64_encode(JURI::base()) ?>" />
                <?php echo JHtml::_('form.token'); ?>
            </fieldset>
            </form>
        
        </div>    
        <?php } ?> 
      
    </div>
      
     
</body>

</html>
