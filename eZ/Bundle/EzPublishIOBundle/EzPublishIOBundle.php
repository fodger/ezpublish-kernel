<?php

namespace eZ\Bundle\EzPublishIOBundle;

use eZ\Bundle\EzPublishIOBundle\DependencyInjection\Compiler;
use eZ\Bundle\EzPublishIOBundle\DependencyInjection\EzPublishIOExtension;
use eZ\Publish\Core\Base\Container\Compiler\IOHandlerTagPass;
use eZ\Bundle\EzPublishIOBundle\DependencyInjection\ConfigurationFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EzPublishIOBundle extends Bundle
{
    /** @var EzPublishIOExtension */
    protected $extension;

    public function build( ContainerBuilder $container )
    {
        $container->addCompilerPass( new IOHandlerTagPass() );
        $container->addCompilerPass(
            new Compiler\IOConfigurationPass(
                $this->extension->getMetadataHandlerFactories(),
                $this->extension->getBinarydataHandlerFactories()
            )
        );
        parent::build( $container );
    }

    public function getContainerExtension()
    {
        if ( !isset( $this->extension ) )
        {
            $flysystemFactory = new ConfigurationFactory\Flysystem();

            $this->extension = new EzPublishIOExtension();
            $this->extension->addMetadataHandlerFactory( 'flysystem', $flysystemFactory );
            $this->extension->addBinarydataHandlerFactory( 'flysystem', $flysystemFactory );
        }

        return $this->extension;
    }
}
