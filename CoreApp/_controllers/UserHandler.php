<?php

namespace CoreApp\Controller;
use \CoreApp;

	class UserHandler extends CoreApp\InnerController {

		/* - Constructor -- main CoreApp InnerController --  - */

		public function __construct() {
			parent::__construct(ClassName(__CLASS__));
			$this->model = $this->loadModel(ClassName(__CLASS__));
		}

		public function userAllowedToModule($view, $back) {
			if($this->model->userAllowedToModule($view)) {
				return true;
			}
			else {
				header("location:../$back");
			}
		}

		public function userAllowedToAPIFunction($api, $function, $back) {
			if($this->model->userAllowedToAPIFunction($api, $function)) {
				return true;
			}
			else {
				header("location:../$back");
			}
		}

		/* - User MODULES block - */

    public function Modules() {
      return $this->getModules();
    }

		private function getModules() {
			return $this->model->getModules();
		}

		public function getPageModules($viewid) {
			return $this->model->getPageModules($viewid);
		}

		/* - User DATA block - */

    public function UserData() {
      return $this->model->getUserData();
    }

		public function logout() {
			$this->model->logout();
		}

		/*
		public function modelDidLoad()
		{
			echo "model loaded <br>";
		}
		*/
	}
