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

use \InvalidArgumentException;

/**
 * Implementation of a file based caching system
 *
 * @category  project_name
 * @package   package_name
 * @author    Marcel Beerta <marcel@etcpasswd.de>
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   1.0
 * @link      http://www.etcpasswd.de
 */
class FileCache implements Cache
{
    /**
     * Cache folder
     *
     * @var string
     */
    protected $path = "";
    
    /**
     * Constructor
     *
     * @param string $path Cache folder
     *
     * @return void
     */
    public function __construct($path)
    {
        $this->path = $path;
    }
    
    /**
     * Returns the full path to the given cache file
     *
     * @param string $key Cache key
     *
     * @return string
     */
    protected function resolveFilename($key)
    {
        return $this->path.'/'.$key;
    }
    
    
    /* {@inheritdoc} */
    public function has($key)
    {
        return file_exists($this->resolveFilename($key));
        
    }
    /* {@inheritdoc} */
    public function get($key)
    {
        if(!$this->has($key))
        {
            throw new InvalidArgumentException('Invalid key provided: "'.$key.'"');
        }
        return unserialize(file_get_contents($this->resolveFilename($key)));
    }
    /* {@inheritdoc} */
    public function set($key, \Serializable $data)
    {
        $fp = fopen($this->resolveFilename($key), 'w+');
        fputs($fp, serialize($data));
        fclose($fp);
    }
    /* {@inheritdoc} */
    public function purge($key=null)
    {
        if(is_null($key))
        {
            if(is_dir($this->path))
            {
                $this->rrmdir($this->path);
            }
            @mkdir($this->path, 0777, true);
        } else {
            if(!$this->has($key)) {
                throw new InvalidArgumentException('Invalid key provided: "'.$key.'"');
            }
            unlink($this->resolveFilename($key));
        }
    }
    
    /**
     * Recursively deletes the contents of a folder
     *
     * @param string $dir Folder to delete
     *
     * @return void
     */
    protected function rrmdir($dir) {
        if (is_dir($dir)) {
             $objects = scandir($dir);
             foreach ($objects as $object) {
                 if ($object != "." && $object != "..") {
                     if (filetype($dir."/".$object) == "dir") {
                         $this->rrmdir($dir."/".$object);
                     } else {
                         unlink($dir."/".$object);
                     }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}