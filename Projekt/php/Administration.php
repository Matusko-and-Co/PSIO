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
				$max = 0;
				
				for($i = 3; $i <= count($sheetData); $i++){
					if($sheetData[$i]['A'] == ''){
						break;
					}
					
					$valueArray[$i-3] = array(0 => floatval($sheetData[$i]['A']), 1 => floatval($sheetData[$i]['F']), 2 => floatval($sheetData[$i]['E']), 3 => floatval($sheetData[$i]['C']), 4 => floatval($sheetData[$i]['D']), 5 => floatval($sheetData[$i]['B']), 6 => floatval($sheetData[$i]['H']), 7 => floatval($sheetData[$i]['G']));
					
					$max = max($max, max($valueArray[$i-3]));
				}
				
				$max = floatval($max);
				for($i = 0; $i < count($valueArray); $i++){
					for($j = 0; $j < count($valueArray[$i]); $j++){
						$valueArray[$i][$j] = $valueArray[$i][$j] / $max * 100;
					}
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