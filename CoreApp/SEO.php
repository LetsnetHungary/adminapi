<?php

namespace CoreApp;

	class SEO {

		public static function getPageConfig($objectname) {
			$filename = "_resources/jsons/viewconfig/$objectname.json";
			$data = file_get_contents($filename);
			$data = json_decode($data);
			return($data);
		}

		/*
		public static function getSEOData($objectname)
		{
			$filename = "Resources/JSONS/SEO.json";
			$data = file_get_contents($filename);
			$data = json_decode($data);
			return($data->$objectname);
		}

		public static function getJSData($objectname)
		{
			$filename = "Resources/JSONS/Javascript.json";
			$data = file_get_contents($filename);
			$data = json_decode($data);
			return($data->$objectname);
		}

		public static function getCSSData($objectname)
		{
			$filename = "Resources/JSONS/CSS.json";
			$data = file_get_contents($filename);
			$data = json_decode($data);
			return($data->$objectname);
		}
		*/
	}
