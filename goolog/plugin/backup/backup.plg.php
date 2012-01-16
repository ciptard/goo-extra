<?php
/**
 * Plugin BackUp
 *
 * @version	1.0
 * @date	16/01/2012
 * @author	Frédéric K.
 **/
 
 // On pré-installe les paramètres par défauts. (optionnel)
if(!isValidEntry('plugin', $plugin))
{
      $data['state']           ='off';
      $data['backup_folder']   = 'backup_data';            
      saveEntry('plugin', $plugin, $data);
      # Création du dossier qui accueillera nos archives      
      mkdir($data['backup_folder']);
} 
/*
** On charge notre fichier langue
*/
require 'plugin/' .$plugin. '/lang/' .$config['lang']. '.lng.php';
/*
** Retourne la taille d'un fichier (backup.php)
*/					   	
function fSize($s) {
	$size = '<span>'. ceil(round(($s / 1024), 1)) .'</span> KB'; // in kb
	if ($s >= "1000000") {
		$size = '<span>'. round(($s / 1048576), 1) .'</span> MB'; // in mb
	}
	if ($s <= "999") {
		$size = '<span>&lt; 1</span> KB'; // in kb
	}
	
	return $size;
}
/*
** Creation de sauvegarde
*/	 
if(isset($_GET['make']) && isAdmin())
{
    $plugin = 'backup';
    global $lang;
    # Lecture des données
    $data = readEntry('plugin', $plugin);
	# Lib
	include('plugin/' .$plugin. '/pclzip.inc.php');
	$date = date('d-m-Y-His');
	# Nom + path de la sauvegarde 
	$new_zip= new PclZip($data['backup_folder']. '/' .$date.'-backup.zip');				
	# Dossier que l'on veut sauvegarder
	$file_list = $new_zip->create('data/');	

	# Affichage
	echo '<h3>&raquo; ' .$lang['backup']. '</h3>';			
	if ($file_list == 0) 
	{
		die('<div class="msg warning"><span>'.$lang['archive_error'].':'.$new_zip->errorInfo(true)).'</span></div>';
	}
	echo '<div class="msg success"><span>'.$lang['archive_success'].'</span><a href="#" class="close_msg">x</a></div>
	      <p style="text-align:center"><a href="view.php/plugin/'.$plugin.'">' .$lang['redirect']. ' ' .$lang['view_backup']. '</a></p>';     
}
/*
** Supression d'un fichier
*/
if(isset($_GET['delfile']) && isAdmin())
{
    $plugin = 'backup';
    global $lang;
    # Lecture des données
    $data = readEntry('plugin', $plugin);
    
	$backup = htmlspecialchars($_GET['delfile']);
	@unlink($data['backup_folder'] .'/' .$backup. '.zip');
	header('Location: view.php/plugin/'.$plugin.'');
	exit;
}
/*
** Ajoute le pluriel sur un mot
*/
function pluriel($num, $plural = 's', $single = '') 
{
    if ($num == 1) return $single; 
     else 
    return $plural;
}
/*
** Page de configuration du plugin
*/
function backup_config()
{   
       $plugin = 'backup';
       $out ='';
       global $lang;
       if(checkBot())
       {
               $data['backup_folder'] = clean($_POST['backup_folder']);
               $data['state'] = clean($_POST['state']); 
               saveEntry('plugin', $plugin, $data);
               $out .= '<p><span style="color:green">' .$lang['data_save']. '</span><br /><a href="config.php/plugin/'.$plugin.'">← ' .$lang['redirect'] . $plugin.'</a></p>';
       }
       else
       {
               if (isValidEntry('plugin', $plugin))
               $data = readEntry('plugin', $plugin);
               $out .= '<div style="text-align:right"><a href="view.php/plugin/'.$plugin.'">' .$lang['view_backup']. '</a></div>
               <form action="config.php/plugin/'.$plugin.'" method="post">
               <p>' .text('backup_folder', isset($data)? $data['backup_folder'] : ''). '</p>
               <p>' .select('state', array('on'=> $lang['state_on'], 'off'=> $lang['state_off']), $data['state']). '</p>
               <p>' .submit(). '</p>
               </form>';
       }
       return $out;		       
}
/*
** Liste de nos sauvegardes	(page)
*/
function backup_view()
{
  $plugin = 'backup';
  $out ='';
  global $lang;
  # Lecture des données
  $data = readEntry('plugin', $plugin);
  if ($data['state']=='on') 
   { 
    $out .= '<div id="backup"">';
        $count="0";
		$save = fdir($data['backup_folder']);
		if(!empty($save))
		{
			$out .= '<table id="table" class="table sorter" cellspacing="0"> 
	 <thead> 
	     <tr>
			<th>' .$lang['name']. '</th>
			<th>Taille</th>
			<th style="text-align:center;">' .$lang['action']. '</th>
		</tr>
	</thead>
	<tbody>';	
													
			foreach($save as $backup)
			{
				$out .= '<tr>
				<td>' .$backup. '</td>
				<td><em>('.fSize($backup).')</em></td>
				<td style="text-align:right;"><a href="./'.$data['backup_folder'] .'/' .$backup.'.zip" title="' .$lang['download']. '" class="left primary pill button"><span class="downarrow icon"></span>' .$lang['download']. '</a><a href="config.php/plugin/'.$plugin.'/delfile='.$backup.'.zip" onclick="Check=confirm(\''.$lang['confirm_delete'].'\n'.$backup.'\');if(Check==false) return false;" title="' .$lang['delete']. '" class="right negative pill button"><span class="cross icon"></span>' .$lang['delete']. '</a></td>
				</tr>';
				$count++;
			}
			$out .= '</tbody>
                       </table>';
		}
		else
		{
			$out .= '<div class="msg warning"><span>'.$lang['none'].'</span><a href="#" class="close_msg">x</a></div>';
		}
                   	$out .= '<p style="text-align:center"><a href="config.php/plugin/'.$plugin.'/make" rel="facebox" class="positive button"><span class="loop icon"></span>' .$lang['make_backup']. '</a>  <a href="config.php/plugin/'.$plugin.'" class="button"><span class="cog icon"></span>'.$lang['return_config'].'</a> - '.$lang['total_archives']. pluriel(count($count)). ' <strong>'.$count.'</strong></p>
	</div>';

    return $out; 
   } 
   else 
   { 
   // return '<!-- backup Disabled -->'; 
   }  
}
?>