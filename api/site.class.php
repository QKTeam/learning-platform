<?php
class Site {
	public function getSessionUid() {
		if(!isset($_SESSION['user']['uid'])) return 0;
		if(!isset($_SESSION['user']['lastActiveTime'])) return 0;
		$exp = strtotime('now') - $_SESSION['user']['lastActiveTime'];
		if($exp > 60 * 60) return 0; // one hour
		$_SESSION['user']['lastActiveTime'] = strtotime('now');
		return $_SESSION['user']['uid'];
	}
	public function writeInSession($uid) {
		$_SESSION['user']['lastActiveTime'] = strtotime('now');
		return $_SESSION['user']['uid'] = (int)$uid;
	}
	public function clearSession() {
		$_SESSION['user']['lastActiveTime'] = 0;
	}
}
