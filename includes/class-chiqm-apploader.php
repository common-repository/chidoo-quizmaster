<?php

if(!class_exists('ChiQmAppLoader')){

class ChiQmAppLoader{

	protected $classes;

	protected $apps;

	public function __construct() {

		$this->classes = array();

		$this->apps = array();
	}


	public function scanApps($scandir = CHIQM_APPDIR){

		$applist = get_option('chiqm_applist');
//		echo "<p>".print_r($applist,1)."</p>";
		$this->classes = $applist;
	
		if(!file_exists($scandir)){
			return;
		}

		if ($handle = opendir($scandir)) {

    	while (false !== ($entry = readdir($handle))) {
				if ($entry == '.' || $entry == '..' || $entry == 'CVS') {
          continue;
        } 

				$curDirEntry = $scandir.'/'.$entry;
				if(is_dir($curDirEntry)){
  	   		//echo '<li>'.$curDirEntry."</li>\n";
					$tokens = token_get_all( file_get_contents($curDirEntry.'/'.$entry.'.php'));
					for($i = 0; $i < count($tokens); $i++){
						if(is_array($tokens[$i])){
							if(token_name($tokens[$i][0]) == 'T_CLASS'){
								//echo '<li>'.print_r($applist[$entry],1).'</li>'."\n";
								if(isset($applist[$entry])){
									//$classes[$entry] = $applist[$entry];
									// keep status but update anything else
  	   						//echo '<li>slug-if: '.$entry."</li>\n";
									$this->classes[$entry] = array('slug' => $entry, 'class' => $tokens[$i+2][1],'status' => $applist[$entry]['status'],'custom' => (($scandir == CHIQM_APPDIR) ? 0 : 1) );
								} else {
  	   						//echo '<li>slug-else: '.$entry."</li>\n";
									$this->classes[$entry] = array('slug' => $entry, 'class' => $tokens[$i+2][1],'status' => 'inactive','custom' => (($scandir == CHIQM_APPDIR) ? 0 : 1));
								}
								continue;
							}
						}
					}
				}
    	}
    }

    closedir($handle);

		//$this->classes = $classes;

		update_option('chiqm_applist',$this->classes,1);

		//echo print_r($classes,1);
	}

	public function getAvailableApps(){
		return $this->classes;
	}

	public function getActiveApps(){
	/*
		$active_apps = array_keys(get_option('chiqm_apps'));
		$applist = get_option('chiqm_applist');

		$actives = array();

			//echo '<p>'.print_r($applist,1).'</p>';

			foreach($active_apps as $app){
				$app = preg_replace('/chiqm_field_/',"",$app);
				require_once CHIQM_APPDIR.'/'.$applist[$app]['slug'].'/'.$applist[$app]['slug'].'.php';
				//echo '<div class="app-item"><div>'.$applist[$app]['class'].'</div>';
				$cl = new $applist[$app]['class'];
				//echo '<p>Class: '.$cl->getName()."</p>\n";
				$actives[$applist[$app]['slug']] = $cl;
			}
		return $actives;
	*/
	}

	public function getAllApps(){
		$this->scanApps();
		$custom_appdir = get_option('chiqm_custom_appdir');
		$this->scanApps($custom_appdir);
		$applist = get_option('chiqm_applist');
		$allApps = array();
		foreach($applist as $app){
			require_once (($app['custom'] == 0) ? CHIQM_APPDIR : $custom_appdir) .'/'.$app['slug'].'/'.$app['slug'].'.php';
			$cl = new $app['class'];
			$cl->setStatus(isset($app['status']) ? ($app['status']) : ('inactive'));
			$cl->setIsCustom($app['custom']);
			$allApps[$app['slug']] = $cl;
		}
		return $allApps;
	}



	public function getActiveAppsNew(){
		$applist = get_option('chiqm_applist');
		$custom_appdir = get_option('chiqm_custom_appdir');
		$actives = array();
		if(isset($applist) && $applist != ''){
			foreach($applist as $app){
				if($app['status'] == 'active'){
					require_once (($app['custom'] == 0) ? CHIQM_APPDIR : $custom_appdir) .'/'.$app['slug'].'/'.$app['slug'].'.php';
					$cl = new $app['class'];
					$cl->setStatus($app['status']);
					$cl->setIsCustom($app['custom']);
					//echo '<p>Class: '.$cl->getName().', isCustom: '.$cl->isCustom()."</p>\n";
					$actives[$app['slug']] = $cl;
				}
			}
		}
		return $actives;
	}


	public function getAppBySlug($slug){
		$applist = get_option('chiqm_applist');
		$custom_appdir = get_option('chiqm_custom_appdir');
		
		if( !isset($applist[$slug]) || empty ($applist[$slug]) ){
			return false;
		}

		$req_file = (($applist[$slug]['custom'] == 0) ? CHIQM_APPDIR : $custom_appdir).'/'.$slug.'/'.$slug.'.php';
		//echo '<p>'.$applist[$slug]['class'].' req_file: '.$req_file.'</p>';
		if(file_exists($req_file)){
			require_once $req_file;
			$cl = new $applist[$slug]['class'];
			$cl->setStatus($applist[$slug]['status']);
			$cl->setIsCustom($applist[$slug]['custom']);
			return $cl;
		}
		return false;
	}
	
}

} // ChiQmAppLoader

?>
