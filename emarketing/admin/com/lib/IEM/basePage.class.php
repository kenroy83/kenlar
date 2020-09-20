<?php
/**
 * This file contains a "page" base class.
 * This class needs to be extended by every "page" type classes.
 *
 * @package interspire.iem.lib.iem
 */

/**
 *
 * @todo all
 *
 */
class IEM_basePage
{
	/**
	 * CONSTRUCTOR
	 * @todo better PHPDOC
	 */
	public function __construct()
	{

	}

	/**
	 * page_index
	 * Default method that will be called
	 *
	 * @todo better PHPDOC
	 */
	public function page_index()
	{
		return '';
	}
}
