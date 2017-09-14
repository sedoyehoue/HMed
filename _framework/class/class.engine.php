<?php
/*
*@name: Engine()
*@desc: this class holds all the framework's system functions
*@author Anchora Technologies
*/

class Engine
{

	private $x;
	private $y;
	private $z;
	private $to;
	private $from;
	private $fromName;
	private $subject;
	private $body;
	private $data;

	public function __construct(){
		# hi hi hi hi Nun Here !!!
		global $sql,$engine,$pCrypt,$ago,$alert,$session,$geoloc,$td,$genCode,$__,$i;
		$this->sql       = $sql;
		$this->engine    = $engine;
		$this->ago       = $ago;
		$this->alert     = $alert;
		$this->session   = $session;
		$this->geoloc    = $geoloc;
		$this->now       = $td;
		$this->day  	 = date('Y-m-d');
		$this->incr      = $i;
		$this->pCrypt    = $pCrypt;
		$this->genCode   = $genCode;
		$this->lang      = $__;
	}

   	#@desc header redirection function
    public static function redirect($x=''){
        @header('Location: '.$x.'', true, 301);
        @exit;
	}#end


	#@desc header redirection function with javascript
    public static function JSredirect($x){
        echo '<script language="javascript" type="text/javascript">
					window.location = "'.$x.'";
			  </script>' ;
        exit;
	}#end


	/*
	*@name: srtip_symb()
	*@desc: removes unwanted characters from a text
	*@param: $x => text
	*/
	public static function strip_symb($x){
		return str_replace('~[\!*\'"();.:@&=+$, /?%#\]-]+~', '', $x);
	}#end



	/*
	*@name: notBot()
	*@desc: checks if the USER_AGENT is a bot or not
	*/
	public static function notBot(){
		$cmd = preg_match('/(bot|spider)/i',$_SERVER['HTTP_USER_AGENT']);
		if($cmd == true){
			return false;
		}else{
			return true;
		}
	}#end


	/*
	*@name: seourl()
	*@desc: clean url / slug creation
	*@param: $x => text
	*/
	public static function seourl($x, $y='-', $z='utf-8'){
		$x = self::strip_symb($x);
		$x = htmlentities($x, ENT_NOQUOTES, $z, false);
		$x = strtolower($x);
		$x = preg_replace('~&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);~', '\1', $x);
		$x = preg_replace('~&([A-za-z]{2})(?:lig);~', '\1', $x);
		$x = preg_replace('~&[^;]+;.~', '', $x);
		$x = preg_replace('~[\s!*\'();:@&=+$,/?.%#[\]]+~', $y, $x);
		$x = preg_replace('/-$/', '',$x);
		return strip_tags($x);
	}#end


	/*
	*@name: alert()
	*@desc: alert message session
	*/
	public static function alert($status, $msg){
		Session::set('status', $status);
		Session::set('msg', $msg);
	}#end


	/*
	*@name: shorten()
	*@desc: reduces outputed text to specified number of letters
	*@param: $x => text to be shortened
	*@param: $y => number of charachters (int)
	*/
	public static function shorten($x,$y='150',$z='1'){
		$x  = html_entity_decode($x);
		$x  = strip_tags($x);
		$x  = substr( $x, 0, $y );
		$x  = substr( $x, 0, strrpos($x, ' '));
		if($z == '1'){
			return strip_tags($x);
		}else
		if($z == '0'):
			return $x;
		endif;
	}#end


	/*
	*@name: decimal()
	*@desc: convert numbers to two decimal numbers (e.g: 2.00)
	*@param: $x => number to format
	*@param: $y => number after seperator (int)
	*@param: $z => seperator
	*e.g : decimal($var,2,'.')
	*/
	public static function decimal($x,$y='2',$z='.'){
		return number_format((float)$x, $y, $z, '');
	}#end


	/*
	*@name: wordWrap()
	*@desc: convert numbers to two decimal numbers (e.g: 2.00)
	*@param: $x => to be wrapped
	*@param: $y =>
	*@param: $z => seperator
	*e.g : wordWrap($var,3,'-')
	*/
	public static function wordWrap($x,$y='3',$z='-'){
		return wordwrap($x , $y , $z , true );
	}#end


	/*
	*@name: calcPercent()
	*@desc: calculated the percentage off a number
	*@param: $x => number to be calculated
	*@param: $y => percentage value
	*/
	public static function calcPercent($x,$y){
		$a = $x * $y;
		$a = $a / 100;
		$a = $x - $a;
		return $a;
	}#end


	/*
	*@name: percentLeft()
	*@desc: *@param: $x => target
	*@param: $y => got
	*/
	public static function percentLeft($x,$y){
		$a = $x - $y ;
		$a = $a / $x ;
		$a = $a * 100 ;
		$a = 100 - $a ;
		return $a;
	}#end


	/*
	*@name: onlyImages()
	*@desc: cheks if a file is an image
	*/
	public static function onlyImages(){
		return array('image/pjpeg','image/jpeg','image/jpg','image/png','image/x-png','image/pmb','image/ico');
	}#end


	/*
	*@name: couponCode()
	*@desc: returns a randon code used for coupon OR vouchers
	*@param: $x => number of characters
	*@param: $y => character used to replace symbol
	*@usage Engine::couponCode('4', 'A');
	*/
	public static function couponCode($x, $y=''){
		$y = empty($y)?self::randCode(1):$y;
		return preg_replace('/[\/=\&%#\$+]/', $y, base64_encode(mcrypt_create_iv($x, MCRYPT_DEV_URANDOM)));
    }#end


	/*
	*@name: randCode()
	*@desc: returns a randon code
	*@param: $x => number of characters
	*@param: $y => choose to add symbols or not
	*@usage Engine::randCode('6','');
	*/
	public static function randCode($length='8', $symb=''){
		$chars = empty($symb)?'qwertyuplkjhgfdsazxcvbnm123456789MNBVCXZASDFGHJKLPIUYTREWQ':'qwertyuplkjhgfdsazxcvbnm123456789@,!%#[{]}=():?MNBVCXZASDFGHJKLPIUYTREWQ';
	  	$code  = '';
	  	for($i = 0; $i < $length; ++$i) {
			$random = str_shuffle($chars);
			$code  .= $random[5];
	  	}
		return $code;
	}#end


	/*
	*@name: randNumb()
	*@desc: returns a randon code
	*@param: $x => number of characters
	*@param: $y => choose to add symbols or not
	*@usage Engine::randCode('6','');
	*/
	public static function randNumb($length='8'){
		$chars = '0123456789';
	  	$numb  = '';
	  	for($i = 0; $i < $length; ++$i) {
			$random = str_shuffle($chars);
			$numb  .= $random[5];
	  	}
		return $numb;
	}#end


	/*
	*@name: hashTag()
	*@desc: Looks for any word stating with # and converts it
	*/
	public static function hashTag($str,$href=''){
		$regex = '/#+([a-zA-Z0-9_]+)/';
		$strng = preg_replace($regex, '<a title="$0" href="'.$href.'$1">$0</a>', $str);
		return($strng);
	}#end



	/*
	*@name: sendMail()
	*@desc: send mails using phpmailer class
	*/
	public static function sendMail($to, $from, $fromName, $subject, $body){
		$foo = new PHPMailer();
		$foo->CharSet	= 'UTF-8';
		$foo->Encoding	= '8bit';
		$foo->ContentType = 'text/html; charset=utf-8\r\n';
		$foo->Priority = 1;
		$foo->Subject  = $subject;
		$foo->From     = $from;
		$foo->FromName = $fromName;
		$foo->AddAddress($to);
		$foo->MsgHTML($body);
		return $foo->send();
	}#end


	public static function sendMailSmtp($to,$from,$fromName,$subject,$body){
		$foo = new PHPMailer();
		$foo->CharSet     = 'UTF-8';
		$foo->Encoding    = '8bit';
		$foo->ContentType = 'text/html; charset=utf-8\r\n';

		$foo->IsSMTP();
		$foo->Host        = 'smtp.yahoomail.com'; // Sets SMTP server
		$foo->Username    = 'maysdelmundo@ymail.com'; // SMTP account username
		$foo->Password    = '7454820@'; // SMTP account password
		$foo->SMTPDebug   = 2; // 2 to enable SMTP debug information
		$foo->SMTPAuth    = TRUE; // enable SMTP authentication
		$foo->SMTPSecure  = 'tls'; //Secure conection
		$foo->Port        = 587; // set the SMTP port
		$foo->Priority    = 1; // Highest priority - Email priority (1 = High, 3 = Normal, 5 = low)


		$foo->Subject     = $subject;
		$foo->From        = $from;
		$foo->FromName    = $fromName;
		$foo->WordWrap    = 900; // RFC 2822 Compliant for Max 998 characters per line

		$foo->AddAddress($to);
		$foo->isHTML( TRUE );
		$foo->Body    = $body;
		$foo->Send();
		$foo->SmtpClose();

		if($foo->IsError()){
			return $foo->IsError();
		}else{
			return false;
		}
	}#end


	/*
	*@name: phpMail()
	*@desc: send mails using raw php mail() function
	*/
	public static function phpMail($to, $from, $fromName, $subject, $body){
		$headers  = 'From: '.$fromName.' <'.$from.'>' . "\r\n";
		$headers .= 'Reply-To: '.$from."\r\n";
		//$headers .= 'CC: susan@example.com'. "\r\n";
    	$headers .= 'Content-type: text/html' . "\r\n";
		$headers .= 'X-Mailer: PHP/'.phpversion()."\n";
		$headers .= 'MIME-Version: 1.0'."\n";
		return @mail($to, $subject, $body, $headers);
	}#end


	/*
	*@name: DWtrigger()
	*@desc: triggers download of file
	*/
	public static function DWtrigger($file, $name){
		if(file_exists($file) == true) {
            header('Content-Description: File Transfer');
            header('Content-Type: '.mime_content_type($file));
            header('Content-Disposition: attachment; filename='.basename($name));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: '.filesize($file));
            ob_clean(); flush();
            readfile($file);
            exit;
        }else{
			die($file.' no exist');
		}
	}#end


	/*
	*@name: validPWD()
	*@desc: checks if an inputed password is valid
	*@param: $x => inputed password
	*@param: $y => length of the password
	*@param: $z => include symbols or not
	*/
	public static function validPWD($x, $y='6', $z=''){
		$preg = empty($z)?'/^.*(?=.{'.$y.',10})(?=.*\d)(?=.*[a-zA-Z])(?=.*[0-9]).*$/' : '/^(?=.*\d)(?=.*[A-Za-z])(?=.*[!@#$%])[0-9A-Za-z!@#$%]{'.$y.',15}$/';
		if(preg_match($preg, $x) == true){
			return true;
		}else{
			return false;
		}
	}#end


	/*
	*@name: data()
	*@desc: *@param: $x => $_POST or $_GET data
	*/
	public static function data($x) {
		if(is_array($x)){
			foreach ($x as $key => $value) {
				unset($x[$key]);
				$x[self::data($key)] = self::data($value);
			}
		}else{
			$x = htmlspecialchars($x, ENT_COMPAT, 'UTF-8');
		}
		return $x;
	}#end


	/*
	*@name: sanitize()
	*@desc: cleans and takes off a variable unwanted characters
	*@param: $x => variable to clean
	*/
	public static function sanitize($x){
		$prohibited = array("'",'%22',':',',',':','%5B','%5D','(',')','%7B','%7D','=','%60','~','+','!','*');
		$x = str_ireplace($prohibited,'',$x);
		return @trim($x);
	}


	public static function transfer($from='' , $to=''){
		$from = empty($from)?ROOT.'/config.php':'';
		$to = empty($to)?BACKUP_FILE:'';
		$config = file_get_contents($from);
		$config = str_replace('<?php','',$config);
		$backup = @fopen($to, 'w');
		@fwrite($backup, $config);
		@fclose($backup);
	}


	/*
	*@name: addRemoveDay()
	*@desc: add or remove a number of days from a date
	*@param: $x => number of days to add or remove
	*@param: $y => parse in a date or use current date
	*@usage Engine::addRemoveDay('+2');
	*/
	public static function addRemoveDay($x, $y=''){
		$y	  = empty($y)?date('Y-m-d'):$y;
		$date = strtotime($x.' days' , strtotime($y));
		$date = date('Y-m-d' , $date);
		return $date;
	}#end


	/*
	*@name: addRemoveMonth()
	*@desc: add or remove a number of months from a date
	*@param: $x => number of months to add or remove
	*@param: $y => parse in a date or use current date
	*@usage Engine::addRemoveMonth('+2');
	*/
	public static function addRemoveMonth($x, $y=''){
		$y = empty($y)?date('Y-m-d'):$y;
		$date = strtotime($x.' months' , strtotime($y));
		$date = date('Y-m-d' , $date);
		return $date;
	}#end


	/*
	*@name: addRemoveYear()
	*@desc: add or remove a number of years from a date
	*@param: $x => number of years to add or remove
	*@param: $y => parse in a date or use current date
	*@usage Engine::addRemoveYear('+2');
	*/
	public static function addRemoveYear($x,$y=''){
		$y = empty($y)?date('Y-m-d'):$y;
		$z = explode('-',$y);
		$year = $z[0] + $x;
		$date = $year.'-'.$z[1].'-'.$z[2];
		return $date;
	}#end


	/*
	*@name: daysOfWeek()
	*@desc: returns passed days of week based on current day
	*/
	public static function daysOfWeek(){
		$today = date('D');
		$today = strtolower($today);
		switch($today):
			case 'mon':
				return array( date('Y-m-d') );
			break;
			case 'tu':
				return array( self::addRemoveDay('-1'), date('Y-m-d') );
			break;
			case 'wed':
				return array( self::addRemoveDay('-2'),self::addRemoveDay('-1'), date('Y-m-d') );
			break;
			case 'thu':
				return array( self::addRemoveDay('-3'),self::addRemoveDay('-2'),self::addRemoveDay('-1'), date('Y-m-d') );
			break;
			case 'fri':
				return array( self::addRemoveDay('-4'),self::addRemoveDay('-3'),self::addRemoveDay('-2'),self::addRemoveDay('-1'), date('Y-m-d') );
			break;
			case 'sat':
				return array( self::addRemoveDay('-5'),self::addRemoveDay('-4'),self::addRemoveDay('-3'),self::addRemoveDay('-2'),self::addRemoveDay('-1'), date('Y-m-d') );
			break;
			case 'sun':
				return array( self::addRemoveDay('-6'),self::addRemoveDay('-5'),self::addRemoveDay('-4'),self::addRemoveDay('-3'),self::addRemoveDay('-2'),self::addRemoveDay('-1'), date('Y-m-d') );
			break;
			default:
				return array();
			break;
		endswitch;
	}#end


	/*
	*@name: monthsOfYear()
	*@desc: returns passed months of the current year
	*/
	public static function monthsOfYear(){
		$today = date('m');
		$today = strtolower($today);
		switch($today):
			case '1':
				return array( date('Y-m') );
			break;
			case '2':
				return array( self::addRemoveMonth('-1'), date('Y-m') );
			break;
			case '3':
				return array( self::addRemoveMonth('-2'),self::addRemoveMonth('-1'), date('Y-m') );
			break;
			case '4':
				return array( self::addRemoveMonth('-3'),self::addRemoveMonth('-2'),self::addRemoveMonth('-1'), date('Y-m') );
			break;
			case '5':
				return array( self::addRemoveMonth('-4'),self::addRemoveMonth('-3'),self::addRemoveMonth('-2'),self::addRemoveMonth('-1'), date('Y-m') );
			break;
			case '6':
				return array( self::addRemoveMonth('-5'),self::addRemoveMonth('-4'),self::addRemoveMonth('-3'),self::addRemoveMonth('-2'),self::addRemoveMonth('-1'), date('Y-m') );
			break;
			case '7':
				return array( self::addRemoveMonth('-6'),self::addRemoveMonth('-5'),self::addRemoveMonth('-4'),self::addRemoveMonth('-3'),self::addRemoveMonth('-2'),self::addRemoveMonth('-1'), date('Y-m') );
			break;
			case '8':
				return array( self::addRemoveMonth('-7'),self::addRemoveMonth('-6'),self::addRemoveMonth('-5'),self::addRemoveMonth('-4'),self::addRemoveMonth('-3'),self::addRemoveMonth('-2'),self::addRemoveMonth('-1'), date('Y-m') );
			break;
			case '9':
				return array( self::addRemoveMonth('-8'),self::addRemoveMonth('-7'),self::addRemoveMonth('-6'),self::addRemoveMonth('-5'),self::addRemoveMonth('-4'),self::addRemoveMonth('-3'),self::addRemoveMonth('-2'),self::addRemoveMonth('-1'), date('Y-m') );
			break;
			case '10':
				return array( self::addRemoveMonth('-9'),self::addRemoveMonth('-8'),self::addRemoveMonth('-7'),self::addRemoveMonth('-6'),self::addRemoveMonth('-5'),self::addRemoveMonth('-4'),self::addRemoveMonth('-3'),self::addRemoveMonth('-2'),self::addRemoveMonth('-1'), date('Y-m') );
			break;
			case '11':
				return array( self::addRemoveMonth('-10'),self::addRemoveMonth('-9'),self::addRemoveMonth('-8'),self::addRemoveMonth('-7'),self::addRemoveMonth('-6'),self::addRemoveMonth('-5'),self::addRemoveMonth('-4'),self::addRemoveMonth('-3'),self::addRemoveMonth('-2'),self::addRemoveMonth('-1'), date('Y-m') );
			break;
			case '12':
				return array( self::addRemoveMonth('-11'),self::addRemoveMonth('-10'),self::addRemoveMonth('-9'),self::addRemoveMonth('-8'),self::addRemoveMonth('-7'),self::addRemoveMonth('-6'),self::addRemoveMonth('-5'),self::addRemoveMonth('-4'),self::addRemoveMonth('-3'),self::addRemoveMonth('-2'),self::addRemoveMonth('-1'), date('Y-m') );
			break;
			default:
				return array();
			break;
		endswitch;
	}#end
	
	
	/*
	*@name: daysBetween()
	*@desc: Returns all dates beetween two dates
	*@param: $x => startdate
	*@param: $y => enddate
	*/
	public static function daysBetween($x, $y){
		$sTime = strtotime($x);
		$eTime = strtotime($y);
		$numDays = round(($eTime - $sTime) / 86400) + 1;
		$days = array();
		for($d=0; $d < $numDays; $d++){
			$days[] = date('Y-m-d', ($sTime + ($d * 86400)));
		}
		return $days;
	}


	/*
	*@name: taxrate()
	*@desc: calculate tax rate
	*@param: $x => percentage tax
	*@param: $y => selling price
	*/
	public static function taxrate($x,$y){
		$a = ($x / 100) + 1;
		$b = $y / $a;
		$c = $y - $b;
		return $c;
	}#end


	/*
	*@name: profitaftertax()
	*@desc: to calculate profit after tax
	*@param: $x => percentage tax
	*@param: $y => selling price
	*@param: $z => cost price
	*/
	public static function profitaftertax($x,$y,$z){
	 $a = $y - self::taxrate($x,$y);
	 $b = $a - $z;
	 return $b;
	}#end


	/*
	*@name: taxFraction()
	*@desc: returns tax fraction
	*@param: $x => tax rate
	*/
	public static function taxFraction($x){
  		$a = $x / 100;
  		$b = 1 + $a;
		$c = $a / $b;
		return $c;
  	}#end


	public static function get_contents($url){
		if(function_exists('curl_exec')){
			$conn = curl_init($url);
			curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($conn, CURLOPT_FRESH_CONNECT,  true);
			curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
			$url_get_contents_data = curl_exec($conn);
			curl_close($conn);
	  }else
		if(function_exists('file_get_contents')){
			$url_get_contents_data = file_get_contents($url);
	  }else
		if(function_exists('fopen') && function_exists('stream_get_contents')){
			$handle = fopen ($url, "r");
			$url_get_contents_data = stream_get_contents($handle);
		}else{
			$url_get_contents_data = false;
	  }
		return $url_get_contents_data;
	}#end


	public static function curlPost($url='/', $data=array(), $decode='json'){
		$ch = curl_init();
		$headers = array('Content-Type:multipart/form-data');
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		switch($decode){
			case 'json':
				return json_decode($result, true);
			break;
			default:
				return $result;
			break;
		}
	}


	public static function get_user_ip_address(){
        foreach(array('HTTHM_CF_CONNECTING_IP', 'HTTHM_CLIENT_IP', 'HTTHM_X_FORWARDED_FOR', 'HTTHM_X_FORWARDED', 'HTTHM_X_CLUSTER_CLIENT_IP', 'HTTHM_FORWARDED_FOR', 'HTTHM_FORWARDED', 'REMOTE_ADDR') as $key){
            if(array_key_exists($key, $_SERVER) == true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $userIP = trim($ip);
                    if (filter_var($userIP, FILTER_VALIDATE_IP) !== false){
                        return $userIP;
                    }
                }
            }
        }
        return '';
    }#end


	public static function get_filesize($bytes, $decimals = 2) {
		$sz = 'BKMGTP';
		$factor = floor((strlen($bytes) - 1) / 3);
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
	}


	public static function geticon($link , $append='') {
		$icon = 'globe';
		if(preg_match('#facebook#i' , $link) == true){
    		$icon = 'facebook-square';
		}else
		if(preg_match('#twitter#i' , $link) == true){
			$icon = 'twitter-square';
		}else
		if(preg_match('#instagram#i' , $link) == true){
			$icon = 'instagram';
		}else
		if(preg_match('#pinterest#i' , $link) == true){
			$icon = 'pinterest';
		}else
		if(preg_match('#soundcloud#i' , $link) == true){
			$icon = 'soundcloud';
		}else
		if(preg_match('#linkedin#i' , $link) == true){
			$icon = 'linkedin-square';
		}else
		if(preg_match('#vine#i' , $link) == true){
			$icon = 'vine';
		}else
		if(preg_match('#youtube#i' , $link) == true){
			$icon = 'youtube-square';
		}
		return '<i class="fa fa-'.$icon.' '.$append.'"></i>';
	}


}#endClass
