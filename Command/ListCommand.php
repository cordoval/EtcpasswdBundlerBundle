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

namespace Etcpasswd\SymfonyBundlerBundle\Command;

use Symfony\Component\Finder\Finder;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Yaml\Yaml;

use \SplFileInfo;
/**
 * lists available bundles
 *
 * @category  project_name
 * @package   package_name
 * @author    Marcel Beerta <marcel@etcpasswd.de>
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   1.0
 * @link      http://www.etcpasswd.de
 */
class ListCommand extends BaseCommand
{
    /**
    * Configures the current command.
    */
    protected function configure()
    {
         $this->setName('bundler:list');
         $this->setDescription('Lists available bundles');
    }
    
    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return integer 0 if everything went fine, or an error code
     *
     * @throws \LogicException When this abstract class is not implemented
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $specs = $this->getContainer()->getParameter('symfony_bundler.spec.path');
        if(!is_dir($specs)) {
            throw new \RuntimeException("No specs found. Did you run bundler:spec:update-all ?");
        }
        $finder = new Finder();
        $finder -> name('*.yml') -> in($specs) ->sortByName();
        
        foreach($finder as $file) {
            /* @var $file SplFileInfo */
            $temp = explode(DIRECTORY_SEPARATOR, $file->getPath());
            $vendor  = array_pop($temp);
            $package = substr($file->getFilename(),0,-4);
            $data = Yaml::parse($file->getPathname());
            $description = isset($data['description']) ? $data['description'] : '';
            $output->writeln(str_pad($vendor.'/'.$package, 40).$description);
        }
    }
    
}