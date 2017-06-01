<?php
namespace CoreApp;
	abstract class ViewController extends Controller {

		protected $v;
		protected $a;

		public function __construct($objectname) {
			$this->a = $this->setAuthentication();
			$this->v = new View($objectname);
			$this->v->setPageConfig(SEO::getPageConfig($objectname));
		}

		protected function viewDisplay($customheader) {
			$this->v->render($customheader);
			$this->viewRenderEnded();
		}

		public function showView($bool) {
			$this->viewDisplay($bool);
		}

    protected function viewRenderEnded() {
			//echo '<br>render ended...';
		}
	}
