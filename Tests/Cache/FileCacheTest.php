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

namespace Etcpasswd\SymfonyBundlerBundle\Tests\Cache;

use Etcpasswd\SymfonyBundlerBundle\Cache\FileCache;
use PHPUnit_Framework_TestCase;
/**
 * Tests the file cache
 *
 *
 * @category  project_name
 * @package   package_name
 * @author    Marcel Beerta <marcel@etcpasswd.de>
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   1.0
 * @link      http://www.etcpasswd.de
 */
class FileTestCache extends PHPUnit_Framework_TestCase
{
    
    /**
     * Tests caching of the filecache class
     *
     * @return void
     * @covers FileCache
     */
    public function testCaching()
    {
        if(!is_dir('/tmp')) {
            $this->markTestSkipped('No /tmp folder');
        }
        
        $cache = new FileCache('/tmp/_FileTestCache');
        $cache ->purge();
        
        $item = new CacheableItem();
        $this->assertFalse($cache->has('item'));
        $cache->set('item', $item);
        $this->assertTrue($cache->has('item'));
        $this->assertEquals($item, $cache->get('item'));
        
        $cache->purge('item');
        $this->assertFalse($cache->has('item'));
        
    }
}

class CacheableItem implements \Serializable
{
    protected $a = 1;
    protected $b = 'string';
    
    public function serialize()
    {
        return serialize(
            array(
                'a' => $this->a,
                'b' => $this->b
            )
        );
    }
    
    public function unserialize($value)
    {
        $data = unserialize($value);
        $this->a = $data['a'];
        $this->b = $data['b'];
    }
}