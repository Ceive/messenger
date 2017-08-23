<?php
/**
 * Created by PhpStorm.
 * User: Alexey
 * Date: 30.12.2015
 * Time: 23:42
 */
namespace Ceive\Messenger {
	
	/**
	 * // todo simplify factory
	 * Class Messenger
	 * @package Ceive
	 */
	abstract class Messenger{

		/** @var array */
		protected $options = [];

		/**
		 * @param array $options
		 */
		public function __construct(array $options = []){
			$this->options = $options;
		}

		/**
		 * @param Combination $combination
		 */
		public function send(Combination $combination){
			$this->begin($combination);
			foreach($combination->getDestinations() as $destination){
				$this->registerDestination($destination);
			}
			$this->complete($combination);
		}

		/**
		 * @param CombinationInterface $combination
		 * @return void
		 */
		abstract protected function begin(CombinationInterface $combination);

		/**
		 * @param ContactInterface $destination
		 * @return void
		 */
		abstract protected function registerDestination(ContactInterface $destination);

		/**
		 * @param CombinationInterface $combination
		 * @return void
		 */
		abstract protected function complete(CombinationInterface $combination);

	}
}

