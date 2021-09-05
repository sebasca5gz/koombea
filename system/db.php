<?php
class db {
	private static $instance = null;

	public static function DBH() {
		if (self::$instance == null) {
			try {
				self::$instance = new PDO(DB_PDO_DNS, DB_PDO_USER, DB_PDO_PASS);
			} catch (PDOException $e){
				die($e->getMessage());
			}
		}
		return self::$instance;
	}
}
