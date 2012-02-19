<?php
/**
 * Plugin Search
 *
 * @version	0.2
 * @date	22/12/2011
 * @date	19/02/2012
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
/*
** Css
*/
function Search_head()
{
  $plugin = 'Search';
  # Lecture des données
  $data = readEntry('plugin', $plugin);
  if ($data['state']=='on') 
   { 
  return '<link href="plugin/'.$plugin.'/assets/style.css" rel="stylesheet" type="text/css" />';
   } 
   else 
   { 
   // return '<!-- Search Disabled -->'; 
   }  
}
/*
** Config
*/
function Search_config()
{   
       $plugin = 'Search';
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
               $out .= $lang[$plugin.'place'].'
               <form action="config.php/plugin/'.$plugin.'" method="post">
               <p>' .select('state', array('on'=> $lang['state_on'], 'off'=> $lang['state_off']), $data['state']). '</p>
               ' .submit(). '
               </form>';
       }
       return $out;
}
/*
** Personalized hook
*/
function Search_search()
{
  $plugin = 'Search';
  $out ='';
  global $lang;
  # Lecture des données
  $data = readEntry('plugin', 'Search');
  if ($data['state']=='on') 
   { 
    $out .= '<form id="search" action="view.php/plugin/'.$plugin.'" method="post">' .text('post',$lang['search']). '</form> ';
    return $out;   
   } 
}
/*
** Post Search and return results
*/
function Search_view()
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
	$out .= '<h3>'.$lang['search'].'</h3><ul>';
	if($foundPosts)
	{	
		foreach($foundPosts as $post => $title)
		{	
			$out .= '<li>' .managePost($post). '<a href="view.php/post/' .$post. '">' .$title. '</a> - ' .toDate($post);
			if($postEntry['category'] !== '')
			{
				$categoryEntry = readEntry('category', $postEntry['category']);
				$out .= ' - <a href="view.php/category/' .$postEntry['category']. '">' .$categoryEntry['name']. '</a>';
			}
			$out .= '</li>';				
		}
	}
	else
	{
		$out .= '<li>' .$lang['none']. '</li>';
	}
	$out .= '</ul>
	             <hr />
	             <form action="search.php" method="post">
                 <p>' .text('post'). '</p>
                 ' .submit(). '
                 </form>';	             
	return $out;
}
}
?>