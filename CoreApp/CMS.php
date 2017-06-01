<?php

  namespace CoreApp;

  class CMS {

    private static $mapB = "_cms/_maps/";
    private static $jsonB = "_cms/_jsons/";

    public static function getSection($pageName, $viewName, $sectionName) {
      $cmsMapFile = "_cms/$pageName/_maps/map.$viewName.json";
      $content = json_decode(file_get_contents($cmsMapFile));

      $sectionFile = "_cms/$pageName/_jsons/$viewName/$sectionName.json";
      return(self::GS($sectionFile));
    }

    private static function GS($sectionFile) {
     $content = file_get_contents($sectionFile);
     return(json_decode($content));
   }

   public static function getCMSContent($pageName, $viewName, $sectionName) {
     return(file_get_contents("_cms/$pageName/_jsons/$viewName/$sectionName.json"));
   }
 }
