<?php

namespace Everlution\SendinBlueBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('everlution_sendin_blue');

        $rootNode
            ->children()
                ->scalarNode('api_key')
                    ->isRequired()
                    ->info('SendinBlue API key.')
                ->end()
                ->scalarNode('base_url')
                    ->defaultValue('https://api.sendinblue.com/v2.0')
                    ->info('SendinBlue API base url.')
                ->end()
                ->scalarNode('timeout')
                    ->defaultValue('')
                    ->info('SendinBlue API timeout.')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
