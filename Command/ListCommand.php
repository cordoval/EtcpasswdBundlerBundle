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

use Etcpasswd\SymfonyBundlerBundle\Specification\BundleSpecification;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

class ListCommand extends BaseCommand
{
    /**
     * Configures the current command.
     */
    public function configure()
    {
        $this->setName('bundler:list');
        $this->setDescription('shows installed bundles');
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
        /* @var $service Etcpasswd\SymfonyBundlerBundle\Services\DiscoveryService */
        $service = $this->getContainer()->get('symfony_bundler.discoveryservice');
        $list    = $service->listBundles();
        
        foreach($list as $descriptor)
        {
            $output->writeln($this->formatDescriptor($descriptor));
        }
    }
    
    /**
     * Formats a descriptor for the console
     *
     * @param BundleSpecification $descriptor Descriptor to format
     * @return string
     */
    protected function formatDescriptor(BundleSpecification $descriptor)
    {
        $str = $descriptor->getName();
        
        return $str;
    }
}