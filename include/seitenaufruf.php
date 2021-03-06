<?php
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/user.php"); 

	class Seitenaufruf{
		
		public $ADMINRECHTE = 3;
		public $SPEZIALRECHTE = 2;
		public $ANMELDUNGERFORDERLICH = 1;
		
		public $rechte_erforderlich;
		
		public function need($r){
			$this->rechte_erforderlich = $r;
		}
		
		public function check(){
			$user = new User();
			if($this->rechte_erforderlich >= $this->ANMELDUNGERFORDERLICH){
				if($user->logged_in){
					if($this->rechte_erforderlich == $this->ADMINRECHTE){
						
						if($user->accounttyp == "admin"){
							return true;
						} else {
							$infotext = urlencode("Zugriff verweigert! Adminrechte erforderlich!");
							header("Location: /login.php?infotext=" . $infotext);
						}
					} else if($this->rechte_erforderlich == $this->SPEZIALRECHTE){
						if($user->accounttyp == "admin" || $user->accounttyp == "spezial"){
							return true;
						} else {
							$infotext = urlencode("Zugriff verweigert! Spezialrechte erforderlich!");
							header("Location: /login.php?infotext=" . $infotext);
						}
					} else {
						return true;
					}
				} else {
					$infotext = urlencode("Zugriff verweigert! Anmeldung erforderlich!");
					header("Location: /login.php?infotext=" . $infotext);					
				}
			}
		}
		
		public function save_view(){
			$user = new User();
			$uid = "";
			if($user->logged_in){
				$uid = $user->id;
			}
			if (!isset($_SERVER['HTTP_X_FORWARDED_FOR'])) 
			{
				$client_ip = $_SERVER['REMOTE_ADDR'];
			}
			else 
			{
				$client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}

			mysql_query("
				INSERT INTO zugriffe (
					ip, 
					browser, 
					seite, 
					user_id
				) VALUES (
					'" . $client_ip . "', 
					'" . $_SERVER['HTTP_USER_AGENT'] . "', 
					'" . $_SERVER["PHP_SELF"] . "', 
					'" . $uid . "'
				)
			");			
		}
		
	}
?>