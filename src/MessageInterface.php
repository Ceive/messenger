<?php
/**
 * Created by PhpStorm.
 * User: Alexey
 * Date: 30.12.2015
 * Time: 22:26
 */
namespace Ceive\Messenger {

	/**
	 * Interface MessageInterface
	 * @package Ceive\Messenger\Messenger
	 */
	interface MessageInterface{

		/**
		 * @return string
		 */
		public function getContent();

		/**
		 * @param string $content
		 * @return $this
		 */
		public function setContent($content);

	}
}

