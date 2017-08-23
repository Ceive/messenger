<?php
/**
 * Created by PhpStorm.
 * User: Alexey
 * Date: 30.12.2015
 * Time: 22:38
 */
namespace Ceive\Messenger\Mail {

	use Ceive\Messenger\ContactNamedInterface;

	/**
	 * Interface IMailDestination
	 * @package Ceive\Messenger\Messenger\Mail
	 */
	interface ContactInterface extends ContactNamedInterface{

		const TYPE_MAIN     = 0;
		const TYPE_CC       = 1;
		const TYPE_BCC      = 2;

		/**
		 * TODO: убрать типы из самих персональных контактов
		 * @param int $type
		 * @return $this
		 */
		public function setType($type);

		/**
		 * @return int
		 */
		public function getType();

	}
}

