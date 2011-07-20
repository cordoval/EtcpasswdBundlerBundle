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

use Symfony\Component\Console\Input\InputArgument;

use Symfony\Component\Finder\Finder;

use Symfony\Component\Yaml\Yaml;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Verify a bundle specification
 *
 * @category  project_name
 * @package   package_name
 * @author    Marcel Beerta <marcel@etcpasswd.de>
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   1.0
 * @link      http://www.etcpasswd.de
 */
class VerifyCommand extends BaseCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('bundler:spec:verify');
        $this->setDescription('verifies the integrity of a spec file');
        $this->addArgument('file', InputArgument::REQUIRED, 'File to verify');
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
        $specRoot = $this->getContainer()->getParameter('symfony_bundler.spec.path');
        $filename = $input->getArgument('file').'.yml';
        
        $output->writeln("Checking spec in $specRoot/$filename");
        if(!is_file($specRoot.'/'.$filename)) {
            throw new \RuntimeException("File does not exist");
        }
        
        $yaml = Yaml::parse($specRoot.'/'.$filename);
        $this->verifyHeader($yaml);
        $this->verifyTag($yaml, 'source');
        $this->verifyScm($yaml['source']);
        
        if(is_array($yaml['dependencies'])) {
            $this->verifyTag($yaml, 'dependencies');
            foreach($yaml['dependencies'] as $name => $dep) {
                $this->verifyScm($dep);
            }
        }
        
        $this->verifyTag($yaml, 'config');
        $this->verifyTag($yaml['config'], 'autoloader');
        $this->verifyTag($yaml['config']['autoloader'], 'namespaces');
        foreach($yaml['config']['autoloader']['namespaces'] as $entry) {
            $this->verifyTag($entry, 'namespace');
            $this->verifyTag($entry, 'target');
        }
        
        $this->verifyTag($yaml, 'config');
        $this->verifyTag($yaml['config'], 'bundles');
        foreach($yaml['config']['bundles'] as $entry) {
            $this->verifyTag($entry, 'class');
        }
        
        $output->writeln("Everything Ok");
        
    }
    
    /**
     * Verify an SCM Block
     * @param array $data
     * @return void
     */
    private function verifyScm($data)
    {
        $this->verifyTag($data, 'url');
        $this->verifyTag($data, 'scm');
        $this->verifyTag($data, 'target');
    }
    
    /**
     * Verify spec header contents
     *
     * @param array $data
     *
     * @return bool
     */
    private function verifyHeader($data)
    {
        $this->verifyTag($data, 'name');
        $this->verifyTag($data, 'description');
        $this->verifyTag($data, 'source');
    }
    
    /**
     * Verify a tag exists
     *
     * @param array $data
     * @param string $tag
     *
     * @return bool
     */
    private function verifyTag($data, $tag)
    {
        if(!isset($data[$tag]) ) {
            throw new \InvalidArgumentException(
                sprintf('Missing tag %s', $tag)
            );
        }
        if(is_array($data[$tag]) && count($data[$tag]) <= 0 ) {
            throw new \InvalidArgumentException(
                sprintf('Tag %s given but empty', $tag)
            );
        }
        if(is_scalar($data[$tag]) && trim($data[$tag]) == "") {
            throw new \InvalidArgumentException(
            sprintf('Tag %s given but empty', $tag)
            );
        }
        
    }
}