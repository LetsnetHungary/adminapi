<?php

  namespace CoreApp;

    class Log {

      public static function log($module, $function, $data) {
        $path = "_logs/$module";
        createDir($path);

        $log_file = $path."/$function.txt";
        $file = fopen($log_file, "a");
        fwrite($file, "\n ----- \n"."This log has been created at ".date("F j, Y, g:i a")." by $module -> $function; \n \n");
        fwrite($file, $data);
        fwrite($file, "; \n \n END LOG;");
        fclose($file);
      }

    }
