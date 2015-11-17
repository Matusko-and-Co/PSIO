<?php
	/*
	*	Stara sa o zapisovanie a citanie hesla zo suboru.
	*/
	class Passwd{
		/* tajny kluc */
		const SERCRET_KEY = '#%(^@*()|:}{?>***:}{';
		
		
		/* subor s ulozenym heslom */
		const FILE = 'php/kaktus';
		
		/* Vytvori hash zadanej frazy.
		*	@$phrase - fraza na hesovanie 
		*	return zahesovanu frazu
		*/
		private function makeHash($phrase){
			$salt = '#%(^@*#%(^()#%:}{#(:>*}{#^|:}{#%(^?>***:}{';
			$salt = substr(hash('SHA512', $salt), 10, 25);
		
			return hash('SHA512', self::SERCRET_KEY.$phrase.$salt);
		}
		
		/* Precita heslo zo suboru.
		*	return heslo
		*/
		private function readFromFile(){
			$file = fopen(self::FILE, "r") or die("Nepodarilo sa otvoriť súbor!");
			$passwd = fgets($file);
			fclose($file);
			
			return $passwd;
		}
		
		/* Zapise nove heslo do suboru.
		*	@$passwd - nove heslo
		*/
		public function createNewPassword($passwd){
			$file = fopen(self::FILE, "w") or die("Nepodarilo sa otvoriť súbor!");
			
			fwrite($file, $this->makeHash($passwd));
			
			fclose($file);
		}
		
		/* Porovna heslo s ulozenym.
		*	return true ak sa zhoduju
		*/
		public function comparePasswords($passwd){
			return $this->readFromFile() == $this->makeHash($passwd);
		}
	}
?>