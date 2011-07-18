<?php
/**
 * Default Controller
 *
 * @category  project_name
 * @package   package_name
 * @author    Marcel Beerta <marcel@etcpasswd.de>
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   1.0
 * @link      http://www.etcpasswd.de
 */

namespace Etcpasswd\SymfonyBundlerBundle\Cache;

/**
 * Interface for cache implementations
 *
 * @category  project_name
 * @package   package_name
 * @author    Marcel Beerta <marcel@etcpasswd.de>
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   1.0
 * @link      http://www.etcpasswd.de
 */
interface Cache
{
    /**
     * Checks wether the given cache item exists
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key);
    
    /**
     * Returns the contens for the given key
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key);
    
    /**
     * Sets the contents of the given key to the provided value
     *
     * @param string        $key   Cache Key
     * @param \Serializable $value Value
     *
     * @return void
     */
    public function set($key, \Serializable $value);
    
    /**
     * Purges the cache for either the specified key or all contents
     *
     * @param string|null $key Key to be purged. If none is specified the whole cache
     *                         is emptied
     *
     * @return void
     */
    public function purge($key=null);
    
}