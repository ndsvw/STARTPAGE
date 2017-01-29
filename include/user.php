<?php
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/verbindung.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/www/include/mailsender.php");

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
						return "sucess";
					} else {
						return "notVerified";
					}
				} else {
					return "loginWrong";
				}
			} else {
				header("Location: /index.php");
			}
		}

		public static function registrieren($email, $pw1, $pw2){
			if($pw1 == $pw2){
				$query = mysql_fetch_array(mysql_query("
					SELECT
						COUNT(*)
					FROM
						user
					WHERE
						mail = '" . mysql_real_escape_string($email) . "'
				"));
				if($query[0] == 0){
					mysql_query("
						INSERT INTO user (
							mail,
							password,
							verified
						)
						VALUES (
							'" . mysql_real_escape_string($email) . "',
							'" . md5($pw1) . "',
							'0'
						)
					");
					$query = mysql_fetch_array(mysql_query("
						SELECT
							id
						FROM
							user
						WHERE
							mail = '" . mysql_real_escape_string($email) . "'
					"));
					for($i=1;$i<=5;$i++){
						mysql_query("
							INSERT INTO startpage_user_suchen (
								user_id,
								such_id
							)
							VALUES (
								'" . $query[0] . "',
								'" . $i . "'
							)
						");
					}
					User::sendVerificationMail($query[0]);
					return "regSuccess";
				} else {
					return "userExistsAlready";
				}
			} else {
				return "differentPWs";
			}
		}

		public static function sendVerificationMail($userID){
			$code = User::rand_string(32);
			mysql_query("
				UPDATE
					user
				SET
					verificationCode = '" . $code . "'
				WHERE
					id = '" . $userID . "'
			");
			$query = mysql_query("
				SELECT
					mail
				FROM
					user
				WHERE
					id = '" . $userID . "'
			");
			$message = "";
			$message .= "Mit folgendem Link kannst du deinen Account verifizieren:<br />";
			$message .= "<a href='http://" . $_SERVER['SERVER_NAME'] . "/verify.php?code=" . $code . "'>Account verifizieren</a><br /><br />";
			$message .= "Falls der Link nicht funktioniert, öffnen Sie folgende URL manuell:<br />";
			$message .= "http://" . $_SERVER['SERVER_NAME'] . "/verify.php?code=" . $code;
			$mailsender = new MailSender;
			$mailsender->sendMail(mysql_fetch_array($query)[0], "noreply@" . $_SERVER['SERVER_NAME'], "Verifiziere deinen Account", $message);
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

		private static function rand_string($lng)
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
