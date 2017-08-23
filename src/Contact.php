<?php
/**
 * Created by PhpStorm.
 * User: Alexey
 * Date: 30.12.2015
 * Time: 22:30
 */
namespace Ceive\Messenger {


	/**
	 * Class Destination
	 * @package Ceive\Messenger\Messenger
	 */
	abstract class Contact implements ContactInterface{

		/** @var  string */
		protected $address;

		/**
		 * @param mixed $address
		 * @return mixed
		 */
		public function setAddress($address){
			$this->address = $address;
			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getAddress(){
			return $this->address;
		}
	}
}

