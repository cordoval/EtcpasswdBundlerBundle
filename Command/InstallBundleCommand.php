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

use Etcpasswd\SymfonyBundlerBundle\Manipulator\AutoloaderManipulator;
use Etcpasswd\SymfonyBundlerBundle\Manipulator\KernelManipulator;
use Etcpasswd\SymfonyBundlerBundle\Loader\SpecFileLoader;
use Etcpasswd\SymfonyBundlerBundle\Services\ScmService;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\HttpKernel\KernelInterface;
/**
 * Command to install a bundle
 *
 * @category  project_name
 * @package   package_name
 * @author    Marcel Beerta <marcel@etcpasswd.de>
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   1.0
 * @link      http://www.etcpasswd.de
 */
class InstallBundleCommand extends BaseCommand
{
    
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('bundler:install');
        $this->setDescription('installs a given bundle');
        $this->addArgument('bundle', InputArgument::REQUIRED, 'The name of the bundle to install');
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
        $spec = $this->getBundleSpecification($input->getArgument('bundle'));
        $source = $spec['source'];
        $output->writeln("Installing ".$spec['name'].' from '.$source['url']);

        
        $installRoot = $this->getContainer()
            ->getParameter('symfony_bundler.vendor_path');
        
        /* @var $scm ScmService */
        $scm = $this->getContainer()->get('symfony_bundler.scmservice');
        
        $branch = isset($source['branch']) ? $source['branch'] : null ;

        // fetch bundle
        $scm -> checkoutRepository(
            $source['url'],
            $installRoot.'/bundles/'.$source['target'],
            $source['scm'],
            $branch
        );
        
        // fetch dependencies
        if(isset($spec['dependencies'])) {
            foreach($spec['dependencies'] as $name => $dep)
            {
                $output->writeln("Checking out Dependency '".$name."' to ".$dep['target']);
                
                $branch = isset($dep['branch']) ? $dep['branch'] : null;
                
                $scm->checkoutRepository(
                    $dep['url'],
                    $installRoot.'/'.$dep['target'],
                    $dep['scm'],
                    $branch
                );
            }
        }
        
        // register autoloader namespaces
        foreach($spec['config']['autoloader']['namespaces'] as $namespace)
        {
            $this->updateAutoloader($namespace);
        }
        // register bundle within AppKernel
        foreach($spec['config']['bundles'] as $bundle)
        {
            $this->updateKernel($this->getContainer()->get('kernel'), $bundle['class']);
        }
    }
    
    /**
     * Updates the autoloader to register a new namespace
     *
     * @return void
     */
    protected function updateAutoloader($namespace)
    {
        $kernel_root = $this->getContainer()->getParameter("kernel.root_dir");
        $manip = new AutoloaderManipulator($kernel_root.'/autoload.php');
        $target    = $namespace['target'];
        $namespace = str_replace('\\','\\\\',$namespace['namespace']);
        if($target == 'default' ) {
            $target = "__DIR__.'/../vendor/bundles'";
            
        }
        $manip -> addNamespace($namespace, $target);
    }
    
    /**
     * Updates the kernel and installs new bundles
     *
     * @todo specify environment
     *
     * @return void
     */
    protected function updateKernel(KernelInterface $kernel, $bundle)
    {
        $manip = new KernelManipulator($kernel);
        try {
            $manip->addBundle($bundle);
        } catch (\RuntimeException $e) {}
    }
    
    
    /**
     * Loads the bundle's specification in order to install it
     *
     * @param string $bundle Bundle Name
     *
     * @return array
     */
    private function getBundleSpecification($bundle)
    {
        $specRoot = $this->getContainer()->getParameter('symfony_bundler.spec.path');
        $locator = new FileLocator(array($specRoot));
        $fileLoader = new SpecFileLoader($locator);
        $fileLoader -> load($bundle.'.yml');
        return $fileLoader->getContent();
    }
}