<?php

/**
 * @copyright Metaways Infosystems GmbH, 2014
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 * @package MW
 * @subpackage Media
 */


namespace Aimeos\MW\Media;


/**
 * Common methods for media classes.
 *
 * @package MW
 * @subpackage Media
 */
class Base
{
	private $mimetype;


	/**
	 * Initializes the mime type.
	 *
	 * @param string $mimetype Mime type of the media data
	 */
	public function __construct( $mimetype )
	{
		$this->mimetype = $mimetype;
	}


	/**
	 * Returns the mime type of a media object.
	 *
	 * @return string Mime type like "image/png"
	 */
	public function getMimetype()
	{
		return $this->mimetype;
	}
}
