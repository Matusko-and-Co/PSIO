<?php
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
				
				$valueArray = array();
				
				for($i = 3; $i <= count($sheetData); $i++){
					if($sheetData[$i]['A'] == ''){
						break;
					}
					
					$valueArray[$i-3] = array(0 => round(floatval($sheetData[$i]['A'])*6.25, 2), 1 => round(floatval($sheetData[$i]['F'])*6.55, 2), 2 => round(floatval($sheetData[$i]['E'])*6.9, 2), 3 => round(floatval($sheetData[$i]['C'])*6.849, 2), 4 => round(floatval($sheetData[$i]['D'])*5.91, 2), 5 => round(floatval($sheetData[$i]['B'])*5.586, 2), 6 => round(floatval($sheetData[$i]['H'])*7.955, 2), 7 => round(floatval($sheetData[$i]['G'])*8.199, 2));
				}
		
				$file = fopen(self::FILE, "w") or die("Nepodarilo sa otvoriť súbor!");
				
				fwrite($file, json_encode($valueArray));
				
				fclose($file);
				
				echo "<div>Dáta úspešne zmenené!</div>";
			}else{
				echo "<div class=\"error\">Súbor nemá správny formát alebo je príliš veľký!</div>";
			}
		}
	}
?>