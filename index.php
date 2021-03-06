<?php
	/*
		
		This file is part of OhHi
		http://github.com/brandon-lockaby/OhHi
		
		(c) Brandon Lockaby http://about.me/brandonlockaby for http://oh-hi.info
		
		OhHi is free software licensed under Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported (CC BY-NC-SA 3.0)
		http://creativecommons.org/licenses/by-nc-sa/3.0/
		
	*/
	
	$cwd = str_replace('\\', '/', getcwd());
	$swd = dirname($_SERVER["SCRIPT_NAME"]);
	if(substr($cwd, -strlen($swd)) === $swd) {
		$site_root = substr($cwd, 0, strrpos($cwd, $swd));
	}
	else {
		$site_root = $cwd;
	}
	require_once($site_root . '/oh-hi/OhHi.php');
	OhHi::run();
?>