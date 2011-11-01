<?php

class Cache {
	const CACHE_EXT = ".cache";
	private static $cache_dir;
		
	public function __construct() {
		self::$cache_dir = $_SERVER["DOCUMENT_ROOT"]."/app/cache/";
	}

	public function update($path, $content) {
		$path = trim_slashes($path);
		$path_segs = explode("/", $path);		
		$dir = self::$cache_dir;
		
		//create cache folder if doesn't exist
		if(!file_exists($dir))
			mkdir($dir);
		
		for($i = 0; $i < count($path_segs)-1; $i++) {
			$dir .= $path_segs[$i]."/";			
			if(!file_exists($dir))
				mkdir($dir);
		}
	
		$path = $dir.end($path_segs).self::CACHE_EXT;
		return file_put_contents($path, htmlspecialchars(serialize($content), ENT_QUOTES));
	}
	
	public function delete($path) {
		$path = self::$cache_dir.trim_slashes($path);
		
		if(is_dir($path)) {
			rrmdir($path);
			return true;
		}
		else
			$path .= self::CACHE_EXT;
		
		return @unlink($path);
	}
	
	public function touch($path) {
		$path = self::$cache_dir.trim_slashes($path);
		
		if(!file_exists($path))
			return false;
		
		return touch($path);
	}
	
	public function age($path) {
		$path = self::$cache_dir.trim_slashes($path);
		
		if(!is_dir($path))
			$path .= self::CACHE_EXT;
	
		//if file doesn't exist return current time as age
		return time()-(int)@filemtime($path);
	}
	
	public function dir_contents($dir, $dirs_only=false) {
		$path = self::$cache_dir.trim_slashes($dir);
		
		if(!is_dir($path))
			return array();
			
		$pattern = $path."/*";
		$paths = $dirs_only ? glob($pattern, GLOB_ONLYDIR) : glob($pattern.self::CACHE_EXT);
		$names = array();
		
		foreach($paths as $i => $file) {
			if(is_dir($file) == $dirs_only)
				$names[] = preg_replace('~'.self::CACHE_EXT.'$~', "", basename($file));
		}
		
		return $names;
	}
	
	public function get_single($path) {
		$path = self::$cache_dir.trim_slashes($path).self::CACHE_EXT;
		$contents = @file_get_contents($path);
		
		if(!$contents)
			return false;
		
		return unserialize(htmlspecialchars_decode($contents, ENT_QUOTES));
	}
}