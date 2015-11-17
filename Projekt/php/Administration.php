<?php
	require('./php/PHPExcel/PHPExcel.php');
	/*
	*	Stará sa o zmenu hesla a upload súboru.
	*/
	class Administration{
		/* Súbor s dátami. */
		const FILE = 'data.json';
		
		/* Zmení heslo.
		*	@$oldPassword - staré heslo
		*	@$newPassword - nové heslo
		*	@$newPasswordCheck - nové heslo ešte raz pre kontrolu
		*/
		public function changePassword($oldPassword, $newPassword, $newPasswordCheck){
			$passwd = new Passwd();
			if($passwd->comparePasswords($oldPassword)){
				if(strlen($newPassword) > 0 && $newPassword == $newPasswordCheck){
					$passwd->createNewPassword($newPassword);
				}else{
					echo "<div class=\"error\">Nové heslá sa nezhodujú!</div>";
				}
			}else{
				echo "<div class=\"error\">Zadali ste zlé staré heslo!</div>";
			}
			
		}
		
		/* Zapíše dáta z excelu do súboru na serveri.
		*	@$uploadedFile - pole s informáciami o súbore
		*/
		public function uploadFile($uploadedFile){
			if(isset($uploadedFile) && $uploadedFile['type'] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' && $uploadedFile["size"] <= 1048576){
				include 'PHPExcel/PHPExcel/IOFactory.php';
				
				$objPHPExcel = PHPExcel_IOFactory::load($uploadedFile["tmp_name"]);

				$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
				
				$temp = array();
				
				for($i = 1; $i <= count($sheetData); $i++){
					if($sheetData[$i]['A'] == ''){
						break;
					}
					
					$temp[] = array(0 => trim($sheetData[$i]['A']), 1 => trim($sheetData[$i]['B']), 2 => trim($sheetData[$i]['C']));
				}
				
				$file = fopen(self::FILE, "w") or die("Nepodarilo sa otvoriť súbor!");
				
				fwrite($file, json_encode($temp));
				
				fclose($file);
			}
		}
	}
?>