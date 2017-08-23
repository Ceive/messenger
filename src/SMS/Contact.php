<?php
/**
 * @created Alexey Kutuzov <lexus27.khv@gmail.com>
 * @Project: ceive.messenger
 */

namespace Ceive\Messenger\SMS;


use Ceive\Messenger\ContactInterface;
use Ceive\Messenger\ContactNamedInterface;

/**
 * @Author: Alexey Kutuzov <lexus27.khv@gmail.com>
 * Class Contact
 * @package SMS
 */
class Contact extends \Ceive\Messenger\Contact implements ContactNamedInterface{
	
	protected $name;
	
	/**
	 * @param $contact
	 * @return Contact
	 */
	public static function getContact($contact){
		if($contact instanceof ContactInterface){
			return $contact;
		}elseif(is_string($contact)){
			$o = new Contact();
			if(preg_match('@(.+)?<(.+)>@',$contact,$m)){
				$o->setAddress(trim($m[2]));
				if($m[1])$o->setName(trim($m[1]));
			}else{
				$o->setAddress($contact);
			}
			return $o;
		}elseif(is_array($contact) && isset($contact['address']) && $contact['address']){
			$contact = new Contact();
			$contact->setAddress($contact['address']);
			if(isset($contact['name']))$contact->setName($contact['name']);
			return $contact;
		}else{
			throw new \LogicException('Contact("'.$contact.'") invalid definition');
		}
	}
	
	/**
	 * @param $name
	 * @return $this
	 */
	public function setName($name){
		$this->name = $name;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getName(){
		return $this->name;
	}
}


