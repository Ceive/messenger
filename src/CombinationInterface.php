<?php
/**
 * Created by PhpStorm.
 * User: Alexey
 * Date: 30.12.2015
 * Time: 22:28
 */
namespace Ceive\Messenger {

	/**
	 * Interface IComplex
	 * @package Ceive\Messenger\Messenger
	 */
	interface CombinationInterface{

		/**
		 * @param ContactInterface $destination
		 * @return $this
		 */
		public function addDestination(ContactInterface $destination);

		/**
		 * @param ContactInterface $destination
		 * @return int|bool
		 */
		public function searchDestination(ContactInterface $destination);

		/**
		 * @param ContactInterface $destination
		 * @return $this
		 */
		public function removeDestination(ContactInterface $destination);

		/**
		 * @return ContactInterface[]
		 */
		public function getDestinations();

		/**
		 * @param MessageInterface $message
		 * @return $this
		 */
		public function setMessage(MessageInterface $message);

		/**
		 * @return MessageInterface
		 */
		public function getMessage();

	}
}

