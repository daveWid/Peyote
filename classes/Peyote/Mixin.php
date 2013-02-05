<?php

namespace Peyote;

/**
 * An interface for the classes that can be used as a trait.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
interface Mixin
{
	/**
	 * Gets all of the methods that should be passed as "trait" methods.
	 *
	 * @return array
	 */
	public function getMethods();
}
