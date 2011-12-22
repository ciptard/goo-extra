<?php
/**
 * Plugin Sidebar Search
 *
 * @version	0.2
 * @date	22/12/2011
 * @author	Frédéric K.
 **/
 // On pré-installe les paramètres par défauts.
if(!isValidEntry('plugin', $plugin))
{
      $data['state']      ='off';          
      saveEntry('plugin', $plugin, $data);
} 

# Lecture du fichier langue
require 'plugin/' .$plugin. '/lang/' .$config['lang']. '.lng.php';

// Css
function SidebarSearch_head()
{
  $plugin = 'SidebarSearch';
  # Lecture des données
  $data = readEntry('plugin', 'SidebarSearch');
  if ($data['state']=='on') 
   { 
  return '<link href="plugin/'.$plugin.'/assets/style.css" rel="stylesheet" type="text/css" />';
   } 
   else 
   { 
   // return '<!-- SidebarSearch Disabled -->'; 
   }  
}

// Admin
function SidebarSearch_config()
{   
       $plugin = 'SidebarSearch';
       $out ='';
       global $lang;
       if(checkBot())
       {
               $data['state'] = clean($_POST['state']); 
               saveEntry('plugin', $plugin, $data);
               $out .= '<p><span style="color:green">' .$lang['data_save']. '</span><br /><a href="config.php/plugin/'.$plugin.'">← ' .$lang['redirect'] . $plugin.'</a></p>';
       }
       else
       {
               if (isValidEntry('plugin', $plugin))
               $data = readEntry('plugin', $plugin);
               $out .= '<form action="config.php/plugin/'.$plugin.'" method="post">
               <p>' .select('state', array('on'=> $lang['state_on'], 'off'=> $lang['state_off']), $data['state']). '</p>
               <p>' .submit(). '</p>
               </form>';
       }
       return $out;
}

// Sidebar View
function SidebarSearch_sidebar()
{
  $plugin = 'SidebarSearch';
  $out ='';
  global $lang;
  # Lecture des données
  $data = readEntry('plugin', 'SidebarSearch');
  if ($data['state']=='on') 
   { 
    $out .= '<h1>'.$lang['search'].'</h1>
             <form action="view.php/plugin/'.$plugin.'" method="post">
               ' .text('post', $lang['search']). '
               <!--<input type="search" name="post" placeholder="'.$lang['search'].'…" />-->
		       <input type="submit" value="Go" />
             </form>';
    return $out;   
   } 
   else 
   { 
   // return '<!-- SidebarSearch Disabled -->'; 
   }  
}

// Post Search and return results
function SidebarSearch_view()
{
global $lang;
if(check('post'))
{
	$_POST['post'] = clean($_POST['post']);
	$foundPosts = array();
	foreach(listEntry('post') as $post)
	{
		$postEntry = readEntry('post', $post);
		if(stripos($postEntry['title'], $_POST['post']) !== false || stripos($postEntry['content'], $_POST['post']) !== false)
		{
			$foundPosts[$post] = $postEntry['title'];
		}
	}
	$out .= '<ul>';
	if($foundPosts)
	{		
		foreach($foundPosts as $post => $title)
		{
			$out .= '<li>' .managePost($post). '<a href="view.php/post/' .$post. '">' .$title. '</a></li>';
		}
	}
	else
	{
		$out .= '<li>' .$lang['none']. '</li>';
	}
	$out .= '</ul>';
	return $out; 
}
}
?>