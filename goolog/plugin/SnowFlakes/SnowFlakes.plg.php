<?php
/**
 * Plugin SnowFlakes
 *
 * @version	1.1
 * @date	11/11/2011
 * @update	12/11/2011
 * @author	Frédéric K.
 **/
 // On pré-installe les paramètres par défauts.
if(!isValidEntry('plugin', $plugin))
{
      $data['state']       ='on';
      $data['snowmax']     = '35';
      $data['snowcolor']   = '#aaaacc';
      $data['snowtype']    = 'Times';
      $data['snowletter']  = '*';
      $data['sinkspeed']   = '0.7';
      $data['snowmaxsize'] = '30'; 
      $data['snowminsize'] = '8';
      $data['snowingzone'] = '1';              
      saveEntry('plugin', $plugin, $data);
} 
# Lecture du fichier langue
require 'plugin/' .$plugin. '/lang/' .$config['lang']. '.lng.php';
// Js
function SnowFlakes_head()
{
  # Lecture des données
  $data = readEntry('plugin', 'SnowFlakes');
  if ($data['state']=='on') 
   { 
  return '
<script>
// Set the number of snowflakes (more than 30 - 40 not recommended)
var snowmax='.$data['snowmax'].'
// Set the colors for the snow. Add as many colors as you like ("#aaaacc","#ddddff","#ccccdd","#f3f3f3","#f0ffff")
var snowcolor=new Array("'.$data['snowcolor'].'")
// Set the fonts, that create the snowflakes. Add as many fonts as you like("Times","Arial","Times","Verdana")
var snowtype=new Array("'.$data['snowtype'].'")
// Set the letter that creates your snowflake (recommended: * )
var snowletter="'.$data['snowletter'].'"
// Set the speed of sinking (recommended values range from 0.3 to 2)
var sinkspeed='.$data['sinkspeed'].'
// Set the maximum-size of your snowflakes
var snowmaxsize='.$data['snowmaxsize'].'
// Set the minimal-size of your snowflakes
var snowminsize='.$data['snowminsize'].'
// Set the snowing-zone
// Set 1 for all-over-snowing, set 2 for left-side-snowing
// Set 3 for center-snowing, set 4 for right-side-snowing
var snowingzone='.$data['snowingzone'].'
</script>
<script src="plugin/SnowFlakes/js/snowflakes.js"></script>';   
   } 
   else 
   { 
   // return '<!-- SnowFlakes Disabled -->'; 
   }  
}
// Admin
function SnowFlakes_config()
{   
       $plugin = 'SnowFlakes';
       $out ='';
       global $lang;
       if(checkBot())
       {
               $data['snowmax'] = clean($_POST['snowmax']);
               $data['snowcolor'] = clean($_POST['snowcolor']);
               $data['snowtype'] = clean($_POST['snowtype']);
               $data['snowletter'] = clean($_POST['snowletter']);
               $data['sinkspeed'] = clean($_POST['sinkspeed']);
               $data['snowmaxsize'] = clean($_POST['snowmaxsize']); 
               $data['snowminsize'] = clean($_POST['snowminsize']);
               $data['snowingzone'] = clean($_POST['snowingzone']); 
               $data['state'] = clean($_POST['state']);             
               saveEntry('plugin', $plugin, $data);
               $out .= '<p><span style="color:green">' .$lang['data_save']. '</span><br /><a href="config.php?plugin='.$plugin.'">' .$lang['redirect']. '&nbsp;' .$plugin.'</a></p>';
       }
        else
       {
               if (isValidEntry('plugin', $plugin))
               $data = readEntry('plugin', $plugin);
               $out .= '<div class="block" style="float:right">' .$lang['infos']. '</div>
               <form action="config.php?plugin='.$plugin.'" method="post">
               <p>' .text('snowmax', isset($data)? $data['snowmax'] : ''). '</p>
               <p>' .text('snowcolor', isset($data)? $data['snowcolor'] : ''). '</p>
               <p>' .text('snowtype', isset($data)? $data['snowtype'] : ''). '</p>
               <p>' .text('snowletter', isset($data)? $data['snowletter'] : ''). '</p>
               <p>' .text('sinkspeed', isset($data)? $data['sinkspeed'] : ''). '</p>
               <p>' .text('snowmaxsize', isset($data)? $data['snowmaxsize'] : ''). '</p> 
               <p>' .text('snowminsize', isset($data)? $data['snowminsize'] : ''). '</p> 
               <p>' .text('snowingzone', isset($data)? $data['snowingzone'] : ''). '</p>
               <p>' .select('state', array('on'=> $lang['state_on'], 'off'=> $lang['state_off']), $data['state']). '</p>               
               <p>' .submit(). '</p>
               </form>';
       }
       return $out;
} 
?>