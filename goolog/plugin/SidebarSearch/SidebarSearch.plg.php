<?php
/**
 * Plugin Sidebar Search
 *
 * @version	1.0
 * @date	21/12/2011
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
  # Lecture des données
  $data = readEntry('plugin', 'SidebarSearch');
  if ($data['state']=='on') 
   { 
  return '<style type="text/css">
#sidebar input[type=search]	{display: inline-block; width: 130px; margin: 0; padding: 4px 10px 3px 20px;
				 border: none; -moz-border-radius: 10px; -moz-box-shadow: inset 0 1px 2px rgba(0,0,0,.7);
				 font: 11px "Lucida Grande", sans-serif;
				 /* search.png: */ background: #ddd url("data:image/png;base64,\
				 iVBORw0KGgoAAAANSUhEUgAAAAwAAAAMCAMAAABhq6zVAAAARVBMVEUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA\
				 AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADc6ur3AAAAF3RSTlMAAQIDBAUGFyI6SFFv\
				 goWQkpSfoKGio4AL660AAABaSURBVHheXY1HEoAgFEMD+GmforT7H1VYoI5vlzIJQC7kHJzGhLgtCmsBuH6ytXwVR0\
				 CYnhTEIwggNwtA2JrpK/61PdD98Uz3kQz2qU8jLiUVkVImjoiNMLHeotgFJ/aIIRcAAAAASUVORK5CYII=")
				 no-repeat 5px 5px;}
#sidebar input[type=search]:focus	{/* replicate the glow around the focused textbox */
				 -webkit-box-shadow: 0 0 3px 3px -webkit-focus-ring-color;
				    -moz-box-shadow: 0 0 3px 3px -moz-mac-focusring, inset 0 1px 2px rgba(0,0,0,.7);
				 background-color: #eee;}

/* submit button inside the search field */
#sidebar input[type=submit]	{display: none; width: 19px; height: 19px; cursor: pointer;
				 margin: 0 0 0 -23px; padding: 0; border: none; text-indent: -9999px;
				 /* go.png: */ background: url("data:image/png;base64,\
				 iVBORw0KGgoAAAANSUhEUgAAAA0AAAANCAMAAABFNRROAAAASFBMVEUAAAA9HgBBIAD9cAD/dAP/dQX/dQX/dQX/dA\
				 X/dAX/dAX/dgX/dgX/fAr/ewn/fQv+gA7ksn35jyX////03sf+/v7lsnv23sa7baTDAAAADXRSTlMAGCFdkJ/2+fr7\
				 /NLbqJDi2wAAAGlJREFUeF5VTtsWgzAIYxaqs02ousv//+nAHR8ML4QQiASKtWe3IolJZzBq1imYOnGCGmuVzmNLuh\
				 SxQfr3/UnRpIEeNFU2WTgA7K9jH6zSgeEMzcF++bbTlzeB/8213P9dWcBVH3LP+QN9IAb/TXCVCgAAAABJRU5ErkJg\
				 gg==") no-repeat 0 4px;}

/* hover / focus effects */
#sidebar input[type=submit]:hover,  #sidebar input[type=search]:focus+input,
 #sidebar input[type=submit]:focus, #sidebar input[type=search]:hover+input
				{display: inline-block;}
#sidebar input[type=submit]:hover,  #sidebar input[type=submit]:focus
				{/* gos.png: */ background: url("data:image/png;base64,\
				 iVBORw0KGgoAAAANSUhEUgAAAA0AAAANCAMAAABFNRROAAAAVFBMVEUAAAA+FQDwTgDwTgDwTgDtTgDqTwDwUADwUQ\
				 DwUQDwUQDwUQDgUQDvUgDvUwDvUwDvUgDvVQDvVADw8O/tVQDt3Mzt28rt28ngo2vv7+7v7uzt3c3pZ0kxAAAAEXRS\
				 TlMAIeTg1q6N9fv6+fZh/P3+/fUbhzIAAABkSURBVHheVY5HFoAwCERJlFjShNi9/z0FyyKzAP6jDYgCNp3FACrjPC\
				 WK5J0RcoleZSdjfiY6dsUYAL3k7VTMCE2kH3MLI8+KV5EwgH16S5Ee23dv/fbqm/U/9dIzSz2Jl9rnDfeTCF6HAK3W\
				 AAAAAElFTkSuQmCC") no-repeat 0 4px;}
  </style>';
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
               <input type="search" name="post" placeholder="'.$lang['search'].'…" />
		       <input type="submit" value="Go" />
             </form>';
    return $out;   
   } 
   else 
   { 
   // return '<!-- SidebarSearch Disabled -->'; 
   }  
}
function SidebarSearch_view()
{
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