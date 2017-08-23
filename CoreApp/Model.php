<?

    namespace CoreApp;
    
        class Model {

            public function __construct() {
                
            }

            protected function CURLWPOST($url, $postarray) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postarray));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $api_output = curl_exec ($ch);
                curl_close($ch);
                return $api_output;
            }

        }