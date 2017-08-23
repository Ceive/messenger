<?php
/**
 * Created by PhpStorm.
 * User: Alexey
 * Date: 30.12.2015
 * Time: 22:49
 */
namespace Ceive\Messenger\Mail {

	/**
	 * Class Message
	 * @package Ceive\Messenger\Messenger\Mail
	 */
	class Message extends \Ceive\Messenger\Message implements MessageInterface{

		/** @var string */
		protected $type;

		/** @var string */
		protected $subject;

		/** @var \Ceive\Messenger\ContactInterface|null */
		protected $author;

		/** @var AttachmentInterface[] */
		protected $attachments = [];



		/**
		 * @param string $type
		 * @return $this
		 */
		public function setType($type){
			$this->type = $type;
			return $this;
		}

		/**
		 * @return string
		 */
		public function getType(){
			return $this->type;
		}


		/**
		 * @param Contact|string|array|null $contact
		 * @return $this
		 */
		public function setAuthor($contact = null){
			$this->author = $contact?Contact::getContact($contact):null;
			return $this;
		}

		/**
		 * @return \Ceive\Messenger\ContactInterface|null
		 */
		public function getAuthor(){
			return $this->author;
		}


		/**
		 * @param $subject
		 * @return $this
		 */
		public function setSubject($subject){
			$this->subject = $subject;
			return $this;
		}

		/**
		 * @return string
		 */
		public function getSubject(){
			return $this->subject;
		}

		/**
		 * @param AttachmentInterface $attachment
		 * @return $this
		 */
		public function addAttachment(AttachmentInterface $attachment){
			if($this->searchAttachment($attachment)===false){
				$this->attachments[] = $attachment;
			}
			return $this;
		}

		/**
		 * @param AttachmentInterface $attachment
		 * @return bool|int
		 */
		public function searchAttachment(AttachmentInterface $attachment){
			return array_search($attachment,$this->attachments,true);
		}

		/**
		 * @param AttachmentInterface $attachment
		 * @return $this
		 */
		public function removeAttachment(AttachmentInterface $attachment){
			if(($i = $this->searchAttachment($attachment))!==false){
				array_splice($this->attachments,$i,1);
			}
			return $this;
		}

		/**
		 * @return bool
		 */
		public function hasAttachments(){
			return (bool)$this->attachments;
		}

		/**
		 * @return AttachmentInterface[]
		 */
		public function getAttachments(){
			return $this->attachments;
		}

	}
}

