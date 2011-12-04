<?php
/**
 * Plugin contact
 *
 * @version	0.2
 * @date	01/12/2011
 * @update	04/12/2011
 * @author	Fr�d�ric K.
 **/ 
 /*
** On pr�-installe les param�tres par d�fauts.
*/ 
if(!isValidEntry('plugin', $plugin))
{
      $data['state']        ='off';
      $data['statesidebar'] ='off';
      $data['contact_menu'] = 'Contact';
      $data['email']        = 'webmaster@site.com'; // Features            
      saveEntry('plugin', $plugin, $data);
} 
 /*
** Lecture du fichier langue
*/ 
require 'plugin/' .$plugin. '/lang/' .$config['lang']. '.lng.php';
 /*
** Js & Css
*/
function contact_head()
{
  # Lecture des donn�es
  $plugin = 'contact';
  $data   = readEntry('plugin', $plugin);
  $config = readEntry('config', 'config');
  if ($data['state']=='on') 
   { 
  return '<!-- '.$plugin.' plugin -->
  <link rel="stylesheet" href="plugin/'.$plugin.'/assets/contact.css" type="text/css" />
  <script src="plugin/'.$plugin.'/lang/validation.'.$config['lang'].'.js"></script>';   
   } 
   else 
   { 
   // return '<!-- '.$plugin.' Disabled -->'; 
   }  
}
 /*
** Menu
*/
function contact_menu()
{
  $plugin = 'contact';
  $out ='';
  # Lecture des donn�es
  $data = readEntry('plugin', $plugin);
  if ($data['state']=='on') 
   { 
    $out .= '<li><a href="view.php?plugin=contact">' .$data['contact_menu']. '</a></li>';
    return $out;
   } 
   else 
   { 
   // return '<!-- '.$plugin.' Disabled -->'; 
   }     
}
 /*
** Admin
*/
function contact_config()
{   
       $plugin = 'contact';
       $out ='<script>$(document).ready(function(){$(".togglec").hide();$(".togglet").click(function(){$(this).toggleClass("toggleta").next(".togglec").slideToggle("normal");return true})})</script>';
       global $lang;
       if(checkBot())
       {
               $data['contact_menu'] = clean($_POST['contact_menu']); 
               $data['email']        = clean($_POST['email']); 
               $data['state']        = clean($_POST['state']); 
               $data['statesidebar'] = clean($_POST['statesidebar']);            
               saveEntry('plugin', $plugin, $data);
               $out .= '<p><span style="color:green">' .$lang['data_save']. '</span><br /><a href="config.php?plugin='.$plugin.'">' .$lang['redirect']. '&nbsp;' .$plugin.'</a></p>';
       }
        else
       {
               if (isValidEntry('plugin', $plugin))
               $data = readEntry('plugin', $plugin);
               $out .= '<style type="text/css">.toggle{display:block;position:relative;margin:0 0 20px;width:60%}.toggle .togglet,.toggle .toggleta{display:block;position:relative;height:26px;background:#F5F5F5;border:1px solid #DDD;cursor:pointer;margin:0;padding:0 12px}.toggle .togglet span,.toggle .toggleta span{display:block;height:26px;line-height:26px;font-size:13px;font-weight:700;color:#888;text-shadow:1px 1px 1px #FFF;padding-left:18px;background:url(plugin/'.$plugin.'/assets/plus.png) no-repeat left}.toggle .toggleta span{background:url(plugin/'.$plugin.'/assets/minus.png) no-repeat left}.toggle .togglec{display:block;position:relative;background:#FFF;border:1px solid #DDD;border-top:none;padding:15px}</style>
               <div class="block" style="float:right">' .$lang[$plugin.'infos']. '</div>
               
                <div class="toggle clear fix">                    
                    <div class="togglet"><span>' .$lang['install']. '</span></div>                   
                    <div class="togglec clearfix"><p>' .$lang['install_help']. '</p></div>                   
                </div>   
                            
               <div id="divToToggle" style="display:none">' .$lang['install_help']. '</div>
               <form action="config.php?plugin='.$plugin.'" method="post">
               <p>' .text('contact_menu', isset($data)? $data['contact_menu'] : ''). '</p>
               <p>' .text('email', isset($data)? $data['email'] : ''). '</p>
               <p>' .select('state', array('on'=> $lang['state_on'], 'off'=> $lang['state_off']), $data['state']). '</p>   
               <p>' .select('statesidebar', array('on'=> $lang['state_on'], 'off'=> $lang['state_off']), $data['statesidebar']). '</p>               
               <p>' .submit(). '</p>
               </form>';
       }
       return $out;
} 
 /*
** Page
*/
function contact_view()
{
  $out ='';
  global $lang;
  # Lecture des donn�es
  $plugin = 'contact';
  $data = readEntry('plugin', $plugin);
  if ($data['state']=='on') 
   { 
    $out .= '<!-- '.$plugin.' plugin -->
<div id="contact_form_holder">
    <form action="#contact_form" method="post" id="contact_form">

			<p>
				<label for="sujet">' .$lang['subject']. ' :</label>
            <select name="subject" id="subject">
                <option value="">' .$lang['subject0']. '</option>
                <option value="' .$lang['subject1']. '">' .$lang['subject1']. '</option>
                <option value="' .$lang['subject2']. '">' .$lang['subject2']. '</option>
                <option value="' .$lang['subject3']. '">' .$lang['subject3']. '</option>
                <option value="' .$lang['subject4']. '">' .$lang['subject4']. '</option>
            </select>
                <div id="subject_error" class="error"><img src="./plugin/' .$plugin. '/assets/error.png" alt="erreur" /> ' .$lang['blank_field'].$lang['subject']. '</div>
			</p>
			<p>
				<label for="name">' .$lang['name']. ':</label>
				<input id="name" name="name" type="text" size="30" />
				<div id="name_error" class="error"><img src="./plugin/' .$plugin. '/assets/error.png" alt="erreur" /> ' .$lang['blank_field'].$lang['name']. '</div>
			</p>
			<p>
				<label for="email">' .$lang['contact_email']. ' :</label>
				<input id="email" name="email" type="text" size="30" />
				<div id="email_error" class="error"><img src="./plugin/' .$plugin. '/assets/error.png" alt="erreur" /> ' .$lang['blank_field'].$lang['contact_email']. '</div>
			</p>		
			<p>
				<label for="message">'.$lang['content'].' :</label>
				<textarea id="message" name="message" cols="40" rows="5"></textarea>
				<div id="message_error" class="error"><img src="./plugin/' .$plugin. '/assets/error.png" alt="erreur" /> ' .$lang['blank_field'].$lang['content']. '</div>
			</p>
			<p id="mail_success" class="success"><img src="./plugin/' .$plugin. '/assets/success.png" alt="ok" />' .$lang['mail_success']. '</p>
            <p id="mail_fail" class="success"><img src="./plugin/' .$plugin. '/assets/success.png" alt="ok" />' .$lang['mail_success']. '</p>
			<p id="cf_submit_p">
				<input id="send_message" type="submit" value="' .$lang['submit']. '" />
			</p>
    </form>
</div>';   
    return $out; 
   } 
   else 
   { 
   // return '<!-- '.$plugin.' Disabled -->'; 
   }  
}
 /*
** Page
*/
function contact_sidebar()
{
  $out ='';
  global $lang;
  # Lecture des donn�es
  $plugin = 'contact';
  $data = readEntry('plugin', $plugin);
  if ($data['state']=='on' && $data['statesidebar']=='on') 
   {
   $script = basename($_SERVER['SCRIPT_NAME'], '.php');
   if($script === 'index' || $script === 'view' || $script === 'search')
    { 
    $out .= '<!-- '.$plugin.' plugin -->
<div id="contact_form_holder">
    <h1>'.$lang['contact'].'</h1>
    <form action="#contact_form" method="post" id="contact_form">

			<p>
				<label for="sujet">' .$lang['subject']. ' :</label>
            <select name="subject" id="subject">
                <option value="">' .$lang['subject0']. '</option>
                <option value="' .$lang['subject1']. '">' .$lang['subject1']. '</option>
                <option value="' .$lang['subject2']. '">' .$lang['subject2']. '</option>
                <option value="' .$lang['subject3']. '">' .$lang['subject3']. '</option>
                <option value="' .$lang['subject4']. '">' .$lang['subject4']. '</option>
            </select>
                <div id="subject_error" class="error"><img src="./plugin/' .$plugin. '/assets/error.png" alt="erreur" /> ' .$lang['blank_field'].$lang['subject']. '</div>
			</p>
			<p>
				<label for="name">' .$lang['name']. ':</label>
				<input id="name" name="name" type="text" size="30" />
				<div id="name_error" class="error"><img src="./plugin/' .$plugin. '/assets/error.png" alt="erreur" /> ' .$lang['blank_field'].$lang['name']. '</div>
			</p>
			<p>
				<label for="email">' .$lang['contact_email']. ' :</label>
				<input id="email" name="email" type="text" size="30" />
				<div id="email_error" class="error"><img src="./plugin/' .$plugin. '/assets/error.png" alt="erreur" /> ' .$lang['blank_field'].$lang['contact_email']. '</div>
			</p>		
			<p>
				<label for="message">'.$lang['content'].' :</label>
				<textarea id="message" name="message" cols="20" rows="5"></textarea>
				<div id="message_error" class="error"><img src="./plugin/' .$plugin. '/assets/error.png" alt="erreur" /> ' .$lang['blank_field'].$lang['content']. '</div>
			</p>
			<p id="mail_success" class="success"><img src="./plugin/' .$plugin. '/assets/success.png" alt="ok" />' .$lang['mail_success']. '</p>
            <p id="mail_fail" class="success"><img src="./plugin/' .$plugin. '/assets/success.png" alt="ok" />' .$lang['mail_success']. '</p>
			<p id="cf_submit_p">
				<input id="send_message" type="submit" value="' .$lang['submit']. '" />
			</p>
    </form>
</div>';   
    return $out; 
    } 
   }
   else 
   { 
    // return '<!-- '.$plugin.' Disabled -->'; 
   }  
}
?>