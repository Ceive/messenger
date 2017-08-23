<?php
/**
 * Created by PhpStorm.
 * User: Alexey
 * Date: 13.02.2016
 * Time: 20:01
 */
namespace Ceive\Messenger {

	/**
	 * Interface ContactNamedInterface
	 * @package Ceive\Messenger\Messenger
	 */
	interface ContactNamedInterface extends ContactInterface{
		
		public function getName();
		
	}
}

