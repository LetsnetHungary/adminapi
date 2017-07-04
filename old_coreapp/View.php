<?php

namespace CoreApp;

	class View {
		public $viewname;

		protected $mainHead = "_views/_includes/_heads/_main.php";
		protected $mainFooter = "_views/_includes/_footers/_main.php";
		protected $viewName;
		protected $seodata;
		protected $jsdata;
		protected $cssdata;
		protected $title = "...Letsnet...";
		protected $charset = "UTF-8";


		/* FUTURE */

		/*

			protected $cmsHandler = new CoreApp\Controller\CMSHandler();
			protected $seoHandler = new CoreApp\Controller\SEOHandler();

			protected $sections;

		*/

		public function __construct($viewname) {
			$this->viewName = "_views/$viewname/index.php";
			$this->custom_head = "_views/$viewname/header.php";
      $this->custom_footer = "_views/$viewname/footer.php";
		}

		public function render() {
			$this->rH();
			require $this->viewName;
			$this->rF();
		}

		private function rH() {
			if(file_exists($this->custom_head)) {
				require $custom_head;
				return;
			}
			require $this->mainHead;
			return;
		}

		private function rF() {
			if(file_exists($this->custom_footer)) {
	        require $custom_footer;
	    }
	    else {
	        require $this->mainFooter;
	    }
		}

		public function addPart($data) {
			echo $data;
		}

		public function setPageConfig($pageconfig) {
			$this->pageconfig = $pageconfig;

			$this->seo = $pageconfig->seo;
			$this->title = $this->seo->title;
			$this->charset = $this->seo->charset;

			$this->jsdata = $pageconfig->js;
			$this->cssdata = $pageconfig->css;
		}

		public function loadPageModules($site, $pagemodules) {

			$c_p = count($pagemodules);

			for ($i = 0; $i < $c_p; $i++) {
				$path = "_cms/$site/modules/views/".$pagemodules[$i]["viewid"]."/".$pagemodules[$i]["module"].".php";
				$this->addModule($path);
			}

		}

		public function addModule($path) {
			include($path);
			return;
		}
	}
