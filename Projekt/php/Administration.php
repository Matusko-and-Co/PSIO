﻿<?php
	require('./php/PHPExcel/PHPExcel.php');
	/**
	*	Stará sa o zmenu hesla a upload súboru.
	**/
	class Administration{
		/** 
		* @var string Súbor s dátami.
		*/
		const FILE = 'data.json';
		
		/** 
		*	Vytvorí hash zadanej frázy.
		*	@param string oldPassword Staré heslo.
		*	@param string newPassword Nové heslo.
		*	@param string newPasswordCheck Nové heslo ešte raz pre kontrolu.
		*/
		public function changePassword($oldPassword, $newPassword, $newPasswordCheck){
			$passwd = new Passwd();
			if($passwd->comparePasswords($oldPassword)){
				if(strlen($newPassword) > 0 && $newPassword == $newPasswordCheck){
					$passwd->createNewPassword($newPassword);
					echo "<div>Heslo úspešne zmenené!</div>";
				}else{
					echo "<div class=\"error\">Nové heslá sa nezhodujú!</div>";
				}
			}else{
				echo "<div class=\"error\">Zadali ste zlé staré heslo!</div>";
			}
			
		}
		
		/** 
		*	Zapíše dáta z excelu do súboru na serveri.
		*	@param file uploadedFile Pole s informáciami o súbore.
		*	@param string newPassword Nové heslo.
		*	@param string newPasswordCheck Nové heslo ešte raz pre kontrolu.
		*/
		public function uploadFile($uploadedFile){
			if(isset($uploadedFile) && $uploadedFile['type'] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' && $uploadedFile["size"] <= 1048576){
				include 'PHPExcel/PHPExcel/IOFactory.php';
				
				$objPHPExcel = PHPExcel_IOFactory::load($uploadedFile["tmp_name"]);

				$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
				
				$temp = array();
				
				for($i = 3; $i <= count($sheetData); $i++){
					if($sheetData[$i]['A'] == ''){
						break;
					}
					
					$temp[] = array(0 => trim($sheetData[$i]['A']), 1 => trim($sheetData[$i]['F']), 2 => trim($sheetData[$i]['E']), 3 => trim($sheetData[$i]['C']),
					4 => trim($sheetData[$i]['D']), 5 => trim($sheetData[$i]['B']), 6 => trim($sheetData[$i]['H']), 7 => trim($sheetData[$i]['G']));
				}
				
				$file = fopen(self::FILE, "w") or die("Nepodarilo sa otvoriť súbor!");
				
				fwrite($file, json_encode($temp));
				
				fclose($file);
				
				echo "<div>Dáta úspešne zmenené!</div>";
			}else{
				echo "<div class=\"error\">Súbor nemá správny formát alebo je príliš veľký!</div>";
			}
		}
	}
?>