<?php

namespace CoreApp;
use \CoreApp\Model;

	abstract class Controller {
		public $model;

		public function ClassName($class) {
			return substr(strrchr($class, "\\"), 1);
		}

		protected function loadModel($objectname) {
			$modelName = $objectname.'_Model';
			$modelFileName = "App/_models/$modelName.php";
			$coreAppModelFileName = "CoreApp/_models/$modelName.php";

			if(file_exists($modelFileName)) {
				require $modelFileName;
				$this->modelDidLoad();
				return new $modelName();;
			}
			else if(file_exists($coreAppModelFileName)){
				require($coreAppModelFileName);
				$m = "CoreApp\Model\\".$modelName;
				//$this->modelDidLoad();
				return new $m();
			}
			else {
				$this->model = null;
			}

		}

    protected function setAuthentication() {
      $this->authentication = Appconfig::getData("authentication");
      if($this->authentication) {
          //autchentication on
          $a = new \CoreApp\Controller\Authentication();
					return $a;
      }
			return null;
    }

		public function PageModulesPHP($sitekey, $pagemodules) {
			$c_p = count($pagemodules);
			for($i=0; $i < $c_p; $i++) {
				$path = "_cms/$sitekey/modules/php/".$pagemodules[$i]["viewid"]."/".$pagemodules[$i]["module"].".php";
				$this->includePagemodulPHP($path);
			}
		}

		private function includePagemodulPHP($path) {
			include($path);
			return;
		}

		protected function modelDidLoad() {

		}
	}
