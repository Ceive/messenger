<?php
/**
 * Created by PhpStorm.
 * User: Alexey
 * Date: 30.12.2015
 * Time: 22:27
 */
namespace Ceive\Messenger {

	/**
	 * Interface IDestination
	 * @package Ceive\Messenger\Messenger
	 */
	interface ContactInterface{

		/**
		 * @param mixed $address
		 * @return mixed
		 */
		public function setAddress($address);

		/**
		 * @return mixed
		 */
		public function getAddress();


	}
}

