<?php
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php");

	class User{
		public $id;
		public $email;
		public $accounttyp;
		public $boxsize;
		public $backcolor;
		public $style;
		public $titletext;
		public $verified;
		public $logged_in;

    	public function __construct(){
			if($this->cookie_code_is_valid()){
				$this->logged_in = true;
				$this->id = $this->get_user_id_by_cookie($_COOKIE['code']);
				$this->load_user_data_by_id($this->id);
			} else {
				$this->logged_in = false;
			}
		}

		public function load_user_data_by_id($id){
			$query = mysql_query("
				SELECT
					mail,
					accounttyp,
					boxsize,
					backcolor,
					style,
					titletext
				FROM
					user
				WHERE
					id = $id
			");
			$a = mysql_fetch_array($query);

			$this->email = $a[0];
			$this->accounttyp = $a[1];
			$this->boxsize = $a[2];
			$this->backcolor = $a[3];
			$this->style = $a[4];
			$this->titletext = $a[5];
		}

		public function login($email, $password){
			$login_successfull = false;
			$email = mysql_real_escape_string($email);
			$this->email = mysql_real_escape_string($email);
			$password = md5($password);

			if(!$this->logged_in){
				$query = mysql_fetch_array(mysql_query("
					SELECT
						COUNT(*),
						id,
						verified
					FROM
						user
					WHERE
						mail = '" . $email . "'
					AND
						password = '" . $password . "'
				"));
				if($query[0] == 1)
				{
					if($query[2] == 1){
						$code = $this->rand_string(64);
						setcookie("code", $code, time() + (60 * 60 * 24 * 365));
						mysql_query("
							INSERT INTO sessions (
								user_id,
								session_code
							) VALUES (
								" . $query[1] . ",
								'" . $code . "'
							);
						");
						$this->id = $this->get_user_id_by_email($email);
						$this->load_user_data_by_id($this->id);
						$this->logged_in = true;
						$login_successfull = true;
					} else {
						$infotext = urlencode("Dein Account ist nicht verifiziert. Bitte bestätige den Link in der Bestätigungs-Email!");
						header("Location: /login.php?infotext=" . $infotext);
						exit();
					}
				} else {
					$infotext = urlencode("Falsche Email-Adresse oder falches Passwort!");
					header("Location: /login.php?infotext=" . $infotext);
					exit();
				}
			}
			return $login_successfull;
		}

		public function logout(){
			if($this->logged_in){
				mysql_query("
					DELETE FROM
						sessions
					WHERE
						sessions.session_code = '" . $_COOKIE['code'] . "'
				");
				setcookie ("code", "", time() - 3600);
				$this->logged_in = false;
			}
		}

		public function is_verified(){
			return $this->verified;
		}


		//private

		private function cookie_code_is_valid(){
			if(isset($_COOKIE['code'])){
				$query = mysql_query("
					SELECT
						COUNT(user.id)
					FROM
						user,
						sessions
					WHERE
						sessions.user_id = user.id
					AND
						sessions.session_code = '" . $_COOKIE['code'] . "'
				");
				if(mysql_fetch_array($query)[0] == 1){
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}

		private function get_user_id_by_cookie($str){
			$query = mysql_query("
				SELECT
					user.id
				FROM
					user,
					sessions
				WHERE
					sessions.user_id = user.id
				AND
					sessions.session_code = '" . $str . "'
			");
			return mysql_fetch_array($query)[0];
		}

		private function get_user_id_by_email($str){
			$query = mysql_query("
				SELECT
					user.id
				FROM
					user
				WHERE
					user.mail = '" . $str . "'
			");
			return mysql_fetch_array($query)[0];
		}

		private function rand_string($lng)
		{
			mt_srand(crc32(microtime()));
			$buchstaben = "abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
			$str_lng = strlen($buchstaben)-1;
			$rand= "";
			for($i=0;$i<$lng;$i++){
				$rand.= $buchstaben{mt_rand(0, $str_lng)};
			}
			return $rand;
		}
	}
?>
