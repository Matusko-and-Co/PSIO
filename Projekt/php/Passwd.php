﻿<?php
	/**
	*	Stará sa o zapisovanie a čítanie hesla zo súboru.
	**/
	class Passwd{
		/** 
		* @var string Tajný kľúč.
		*/
		const SERCRET_KEY = '#%(^@*()|:}{?>***:}{';
		
		
		/** 
		* @var string Súbor s uloženým heslom.
		*/
		const FILE = 'php/kaktus';
		
		/** 
		*	Vytvorí hash zadanej frázy.
		*	@param string phrase Fráza na hashovanie.
		*	@return string Zahashovaná fráza.
		*/
		private function makeHash($phrase){
			$salt = '#%(^@*#%(^()#%:}{#(:>*}{#^|:}{#%(^?>***:}{';
			$salt = substr(hash('SHA512', $salt), 10, 25);
		
			return hash('SHA512', self::SERCRET_KEY.$phrase.$salt);
		}
		
		/** 
		*	Prečíta heslo zo súboru.
		*	@return string Heslo.
		*/
		private function readFromFile(){
			$file = fopen(self::FILE, "r") or die("Nepodarilo sa otvoriť súbor!");
			$passwd = fgets($file);
			fclose($file);
			
			return $passwd;
		}
		
		/**
		*	Zapíše nové heslo do súboru.
		*	@param string Nové heslo.
		*/
		public function createNewPassword($passwd){
			$file = fopen(self::FILE, "w") or die("Nepodarilo sa otvoriť súbor!");
			
			fwrite($file, $this->makeHash($passwd));
			
			fclose($file);
		}
		
		/**
		*	Porovná heslo s uloženým.
		*	@param string Nové heslo.
		*	return bool True ak sa zhodujú.
		*/
		public function comparePasswords($passwd){
			return $this->readFromFile() == $this->makeHash($passwd);
		}
	}
?>