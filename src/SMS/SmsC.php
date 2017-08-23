<?php
/**
 * @created Alexey Kutuzov <lexus27.khv@gmail.com>
 * @Project: ceive.messenger
 */

namespace Ceive\Messenger\SMS;


use Ceive\Messenger\CombinationInterface;
use Ceive\Messenger\ContactInterface;
use Ceive\Messenger\Messenger;
use Ceive\Net\URL;
use Ceive\User\AccessAuth\Auth;

/**
 * @Author: Alexey Kutuzov <lexus27.khv@gmail.com>
 * Class SmsC
 * @package Ceive\Messenger\SMS
 */
class SmsC extends Messenger{
	
	/** @var CombinationInterface  */
	public $combination;
	public $added_destinations=0;
	
	//TODO: destination-type(as in SMTP: bcc cc main)
	/** @var ContactInterface[]  */
	public $destinations = [];
	
	/**
	 * @param array $options
	 */
	public function __construct(array $options = []){
		$this->options = array_merge([
			
			'host'              => null,
			
			'timeout'           => null,
			
			'auth'              => null,
			
			'secure'            => false,
			
			'sender_from_login' => true,
			'sender'            => null,
			
			'charset'           => ini_get('default_charset'),
			
			'interval'          => 10,
			'max_destinations'  => 30,
			
			'agent'             => 'CeiveFramework Messenger',
			
			'url'               => 'smsc.ru',
			'alternate_host'    => "212.24.33.196",
			
		],$options);
		$this->options['auth'] = $auth = Auth::getAccessAuth($this->options['auth']);
	}
	
	/**
	 * @param CombinationInterface $combination
	 * @return void
	 */
	protected function begin(CombinationInterface $combination){
		$this->combination = $combination;
	}
	
	/**
	 * @param ContactInterface $destination
	 * @return void
	 */
	protected function registerDestination(ContactInterface $destination){
		$this->added_destinations++;
		$this->destinations[] = $destination;
		if($this->added_destinations >= $this->options['max_destinations']){
			$this->flushSend();
		}
	}
	
	/**
	 * @param CombinationInterface $combination
	 * @return void
	 */
	protected function complete(CombinationInterface $combination){
		$this->flushSend();
	}
	
	/**
	 *
	 */
	protected function flushSend(){
		$phones = [];
		foreach($this->destinations as $destination){
			$phones[] = $destination->getAddress();
		}
		$message = $this->combination->getMessage();
		list($id,$count,$price,$balance) = $this->sendSms($phones,$message->getContent(),0,0,0,0);
		if(!$count){
			throw new \Exception('Not be send');
		}
	}
	
	/**
	 * @var array
	 */
	public $formats = [
		1 => "flash=1",
		2 => "push=1",
		3 => "hlr=1",
		4 => "bin=1",
		5 => "bin=2",
		6 => "ping=1",
		7 => "mms=1",
		8 => "mail=1"
	];
	
	/**
	 * @param $phones
	 * @param $message
	 * @param int $translit
	 * @param int $time
	 * @param int $id
	 * @param int $format
	 * @param null $sender
	 * @param array $params
	 * @param array $files
	 * @return array|bool
	 * @internal param string $query
	 */
	public function sendSms($phones, $message, $translit = 0, $time = 0, $id = 0, $format = 0, $sender = null,$params = [], array $files = []){
		if(is_array($phones)){
			$phones = implode(',',$phones);
		}
		
		$p = [
			'phones'    => $phones,
			'cost'      => 3,
			'mes'       => $message,
			'translit'  => $translit,
			'sender'    => $sender?:$this->options['sender'],
			'time'      => $time
		];
		
		if($format > 0){
			list($k, $v) = explode('=', $this->formats[$format]);
			$p[$k] = $v;
		}
		
		if($params){
			if(is_string($params)){
				parse_str($params, $params);
			}
			$p = array_replace($p, $params);
		}
		
		$result = $this->_exec('send',$p,$files);
		
		if($result[1] > 0){
			$smsID      = $result[0];
			$totalCount = $result[1];
			$totalPrice = $result[2];
			$balance    = $result[3];
		}else{
			//error
			$errorCode = -$result[1];
			$smsID = $result[0];
			return false;
		}
		return $result;
	}
	
	/**
	 * @param $phones
	 * @param $message
	 * @param int $translit
	 * @param int $format
	 * @param string $sender
	 * @param array $params
	 * @return array|bool
	 */
	public function getSmsCost($phones, $message, $translit = 0, $format = 0, $sender = null, $params = []){
		
		if(is_array($phones)){
			$phones = implode(',',$phones);
		}
		
		$p = [
			'phones' => $phones,
			'cost' => 1,
			'mes' => $message,
			'translit' => $translit,
			'sender' => $sender?:$this->options['sender']
		];
		
		
		if($format > 0){
			list($k, $v) = explode('=', $this->formats[$format]);
			$p[$k] = $v;
		}
		
		if($params){
			if(is_string($params)){
				parse_str($params, $params);
			}
			$p = array_replace($p, $params);
		}
		
		$result = $this->_exec('cost',$p);
		
		if($result[1] > 0){
			$totalCost = $result[0];
			$totalCount = $result[1];
		}else{
			//error
			$errorCode = -$result[1];
			return false;
		}
		
		return $result;
	}
	
	/**
	 * @param $id
	 * @param $phone
	 * @param int $all
	 * @return array
	 */
	public function getStatus($id, $phone, $all = 0){
		$result = $this->_exec('status',[
			'phone' => $phone,
			'id' => $id,
			'all' => (int)$all
		]);
		
		if(!strpos($id,',')){
			if(
				$all && count($result) > 9
			   && (
			   	    !isset($result[$idx = $all == 1 ? 14 : 17]) ||
			        $result[$idx] != "HLR"
				)
			){// ',' в сообщении
				$result = explode(",", implode(",", $result), $all == 1 ? 9 : 12);
			}
		}else{
			if (count($result) == 1 && strpos($result[0], "-") == 2){
				return explode(",", $result[0]);
			}
			foreach ($result as $k => $v){
				$result[$k] = explode(",", $v);
			}
		}
		return $result;
	}
	
	/**
	 * @return bool|mixed
	 */
	public function getBalance(){
		$result = $this->_exec('balance');
		if(isset($result[1])){
			
			$errorCode = -$result[1];
			
			return false;
		}else{
			return $result[0];
		}
	}
	
	/**
	 * @param $command
	 * @param array $parameters
	 * @param array $files
	 * @return array
	 */
	protected function _exec($command,array $parameters = [], array $files = []){
		
		/** @var Auth $auth */
		$auth = $this->options['auth'];
		
		$url = new URL([
			URL::V_SCHEME   => $this->options['secure']?'https':'http',
			URL::V_HOST     => $this->options['url'],
			URL::V_PATH     => "/sys/{$command}.php",
			URL::V_QUERY    => array_replace([
				'login'         => $auth->getLogin(),
				'psw'           => $auth->getPassword(),
				'fmt'           => 1,
				'charset'       => $this->options['charset']
			],$parameters)
		]);
		$i = 0;
		do{
			if($i){
				sleep(2);
				if($i == 2){
					$url->setHost('www2.smsc.ru');
				}
			}
			$result = $this->_fetch($url, $files);
		}while (!$result && ++$i < 3);
		
		
		if(!$result){
			//echo "Ошибка чтения адреса: $url\n";
			$result = ","; // фиктивный ответ
		}
		
		$delimiter = ",";
		
		if($command == "status"){
			
			if(strpos($parameters['id'], ",")){
				$delimiter = "\n";
			}
				
		}
		return explode($delimiter, $result);
	}
	
	/**
	 * @param URL $url
	 * @param array $files
	 * @return string
	 */
	protected function _fetch(URL $url, array $files = []){
		$result = "";
		
		$isPostRequest = $this->options['post'] || strlen("$url") > 2000;
		
		if(function_exists("curl_init")){
			$c = 0;
			
			if(!$c){
				$c = curl_init();
				curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 10);
				curl_setopt($c, CURLOPT_TIMEOUT, 60);
				curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
			}
			
			if ($isPostRequest || $files){
				$parameters = $url->getQueryParams();
				
				curl_setopt($c, CURLOPT_POST, true);
				
				if($files){
					
					foreach($parameters as $k => $v){
						$parameters[$k] = isset($v[0]) && $v[0] == "@" ? sprintf("\0%s", $v) : $v;
					}
					
					foreach($files as $i => $path){
						if(file_exists($path)){
							$parameters["file".$i] = function_exists("curl_file_create") ? curl_file_create($path) : "@".$path;
						}
					}
				}
				curl_setopt($c, CURLOPT_POSTFIELDS, $parameters);
			}
			
			curl_setopt($c, CURLOPT_URL, "$url");
			
			$result = curl_exec($c);
		}elseif($files){
			//echo "Не установлен модуль curl для передачи файлов\n";
		}else {
			if (!$this->options['secure'] && function_exists("fsockopen")){
				
				if (!$fp = fsockopen($url->getHost(), 80, $errno, $errstr, 10)){
					$fp = fsockopen($this->options['alternate_host'], 80, $errno, $errstr, 10);
				}
				if($fp){
					
					$args_str = http_build_query($url->getQueryParams());
					if($isPostRequest){
						$r = [
							"POST {$url->getPath()} HTTP/1.1",
							"Host: {$url->getHost()}",
							"User-Agent: {$this->options['user-agent']}",
							"Content-Type: application/x-www-form-urlencoded",
							"Content-Length: ".strlen($args_str),
							"Connection: Close",
							"",
							$args_str,
						];
						fwrite($fp, implode("\r\n",$r));
						
					}else{
						$r = [
							"GET {$url->getPath()}?{$args_str} HTTP/1.1",
							"Host: {$url->getHost()}",
							"User-Agent: {$this->options['user-agent']}",
							"Connection: Close",
							""
						];
						fwrite($fp, implode("\r\n",$r));
					}
					
					
					while (!feof($fp)){
						$result .= fgets($fp, 1024);
					}
						
					list(, $result) = explode("\r\n\r\n", $result, 2);
					
					fclose($fp);
				}
			}else{
				$result = file_get_contents($url);
			}
		}
		
		return $result;
	}
}


