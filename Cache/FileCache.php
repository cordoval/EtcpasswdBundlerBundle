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

class FileCache implements Cache
{
    
    protected $path = "";
    public function __construct($path)
    {
        $this->path = $path;
    }
    
    public function has($key)
    {
        
    }
    
    public function get($key)
    {
        
    }
    
    public function set($key, \Serializable $data)
    {
        
    }
    
    public function purge($key=null)
    {
        
    }
}