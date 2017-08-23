<?

    namespace CoreApp;

        class View {

            private $vName;
            private $files;
            private $SEO;
            public $parameters;

            public function __construct($vName) {
                $this->vName = $vName;
                $this->files = [];
                $this->SEO = [];
            }

            private function files() {
              $fhead_local = "Views/$this->vName/head.php";
              $fheader_local = "Views/$this->vName/header.php";
              $ffooter_local = "Views/$this->vName/footer.php";

              $this->files["head"] = file_exists($fhead_local) ? $fhead_local : "Views/includes/main/head.php";
              $this->files["header"] = file_exists($fheader_local) ? $fheader_local : "Views/includes/main/header.php";
              $this->files["view"] = "Views/$this->vName/main.php";
              $this->files["footer"] = file_exists($ffooter_local) ? $ffooter_local : "Views/includes/main/footer.php";
            }

            private function viewJSON() {
                $json = "Views/$this->vName/$this->vName.json";
                $json = $json = file_exists($json) ? json_decode(file_get_contents($json)) : 0;
                $this->localjson = $json ? $json : 0;

                $json = "Views/includes/main.json";
                $json = $json = file_exists($json) ? json_decode(file_get_contents($json)) : 0;
                $this->mainjson = $json ? $json : 0;
            }

            public function render() {
                $this->viewJSON();
                $this->files();
                foreach($this->files as $file) {
                    include($file);
                }
            }

        }
