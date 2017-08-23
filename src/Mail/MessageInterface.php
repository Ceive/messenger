<?php
/**
 * Created by PhpStorm.
 * User: Alexey
 * Date: 30.12.2015
 * Time: 22:37
 */
namespace Ceive\Messenger\Mail {

	/**
	 * Interface IMailMessage
	 * @package Ceive\Messenger\Messenger\Mail
	 */
	interface MessageInterface extends \Ceive\Messenger\MessageInterface{

		/**
		 * @param Contact|string|array|null $contact
		 * @return $this
		 */
		public function setAuthor($contact=null);

		/**
		 * @return Contact|null
		 */
		public function getAuthor();



		/**
		 * @param string $type
		 * @return $this
		 */
		public function setType($type);

		/**
		 * @return string
		 */
		public function getType();


		/**
		 * @param $subject
		 * @return $this
		 */
		public function setSubject($subject);

		/**
		 * @return string
		 */
		public function getSubject();


		/**
		 * @param AttachmentInterface $attachment
		 * @return $this
		 */
		public function addAttachment(AttachmentInterface $attachment);

		/**
		 * @param AttachmentInterface $attachment
		 * @return bool|int
		 */
		public function searchAttachment(AttachmentInterface $attachment);

		/**
		 * @param AttachmentInterface $attachment
		 * @return $this
		 */
		public function removeAttachment(AttachmentInterface $attachment);

		/**
		 * @return bool
		 */
		public function hasAttachments();

		/**
		 * @return AttachmentInterface[]
		 */
		public function getAttachments();

	}
}

