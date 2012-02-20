<?php
/**
 * Plugin BackUp
 *
 * @version	1.2
 * @date	16/01/2012
 * @Update	19/02/2012
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
** Retourne la taille d'un fichier
*/	
function get_file_size($file) {
	$size = filesize($file);
	$units = array("B","kB","MB","GB","TB","PB","EB","ZB","YB");
 
	foreach($units as $pow => $unit) {
		if($size / pow(1024, $pow) < 1024)
			return number_format($size / pow(1024, $pow)) . ' ' . $unit;	
	}
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
	echo '<h3>' .$lang['backup']. '</h3>';			
	if ($file_list == 0) 
	{
		die($lang['archive_error'].':'.$new_zip->errorInfo(true));
	}
	echo $lang['archive_success']. '
  <p>
    <a href="view.php/plugin/'.$plugin.'" class="btn btn-primary">' .$lang['view_backup']. '</a>
    <a href="#" class="btn">Fermer</a>
  </p>';     
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
	@unlink('./' .$data['backup_folder']. '/'.$backup);
	header('Location: view.php/plugin/'.$plugin.'');
	exit;
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
                 if ($data['state']=='on') 
                   { 
                     $out .= '<div style="text-align:right"><a class="btn" href="view.php/plugin/'.$plugin.'"><i class="icon-eye-open"></i> ' .$lang['view_backup']. '</a></div>';
                   }
               $out .= '<form action="config.php/plugin/'.$plugin.'" method="post">
               <p>' .text('backup_folder', isset($data)? $data['backup_folder'] : ''). '</p>
               <p>' .select('state', array('on'=> $lang['state_on'], 'off'=> $lang['state_off']), $data['state']). '</p>
               ' .submit(). '
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
    $out .= '<article class="span12">
			            <header class="page-header"><h1>' .$lang['backup']. '</h1></header>';
        $count="0";
		$save = fdir($data['backup_folder']);
		if(!empty($save))
		{
			$out .= '<table class="table table-striped table-condensed"> 
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
				<td><em>'.get_file_size('./' .$data['backup_folder'].'/'.$backup.'.zip').'</em></td>
				<td><div class="btn-group" style="float:right;">
				      <a href="./'.$data['backup_folder'] .'/' .$backup.'.zip" title="' .$lang['download']. '" class="btn"><i class="icon-download-alt"></i> ' .$lang['download']. '</a>
				      <a href="config.php/plugin/'.$plugin.'/delfile=./'.$data['backup_folder'] .'/' .$backup.'.zip" onclick="Check=confirm(\''.$lang['confirm_delete'].'\n'.$backup.'\');if(Check==false) return false;" title="' .$lang['delete']. '" class="btn btn-danger"><i class="icon-trash icon-white"></i> ' .$lang['delete']. '</a></div></td>
				</tr>';
				$count++;
			}
			$out .= '</tbody>
                       </table>';
		}
		else
		{
			$out .= '<div class="alert fade in">                  
                        <a class="close" data-dismiss="alert">&times;</a>
                        '.$lang['none']. '
                    </div>';
		}
                   	$out .= '<p style="text-align:center">
                   	            <a class="btn btn-success" href="config.php/plugin/'.$plugin.'/make" rel="facebox"><i class="icon-retweet icon-white"></i> ' .$lang['make_backup']. '</a>  
                   	            <a class="btn" href="config.php/plugin/'.$plugin.'"><i class="icon-arrow-left"></i> '.$lang['return_config'].'</a> 
                   	            - '.$lang['total_archives']. ' <strong>'.$count.'</strong>
                   	         </p>
             </article>';

    return $out; 
   } 
}
/*
** On intègre facebox pour la boite modal
*/
function backup_head(){
  $plugin = 'backup';
  return '<!-- facebox -->
    <link href="plugin/'.$plugin.'/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css" />';
}
/*
** On intègre le js facebox
*/
function backup_footer(){
  $plugin = 'backup';
  return '<!-- Facebox -->
  <script src="plugin/'.$plugin.'/facebox/facebox.js" type="text/javascript"></script>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
      $(\'a[rel*=facebox]\').facebox({
        loadingImage : \'plugin/'.$plugin.'/facebox/loading.gif\',
        closeImage   : \'plugin/'.$plugin.'/facebox/closelabel.png\'
      })
    })
   </script>';
}
?>