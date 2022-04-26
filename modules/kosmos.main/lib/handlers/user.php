<?php

namespace Kosmos\Main\Handlers;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

class User {

	/**
	 * @var array
	 */
	public $arFields = [];

	/**
	 * @var array
	 */
	protected $access = array(
		'template' => false
	);

	/**
	 * @var array
	 */
	protected $errors = array();


	/**
	 * @var self
	 */
	private static $instance;

	private function __construct()
	{
	}

	private function __clone()
	{
	}

	private function __wakeup()
	{
	}

	public static function getInstance()
	{
		if (empty(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public static function onAfterUserAdd(&$arFields)
	{
		$handler = self::getInstance();
		$handler->arFields = $arFields;
		$handler->onAddnUpdate();
	}

	public static function onAfterUserUpdate(&$arFields)
	{
		$handler = self::getInstance();
		$handler->arFields = $arFields;
		$handler->onAddnUpdate();
	}

	private function onAddnUpdate()
	{
		$groups = \Bitrix\Main\UserTable::getUserGroupIds($this->arFields['ID']);

		if(in_array(\travelsoft\booking\Utils::getOpt('guide_group'), $groups)){

		    \Kosmos\Main\Helpers\Guide::set();

        }

	}
}