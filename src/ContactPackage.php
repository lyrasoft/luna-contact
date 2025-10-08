<?php

/**
 * Part of eva project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\Contact;

use Lyrasoft\Contact\Entity\Contact;
use Lyrasoft\Contact\Service\ContactService;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Package\PackageInstaller;
use Windwalker\DI\Container;
use Windwalker\DI\MergeOptions;
use Windwalker\DI\ServiceProviderInterface;

/**
 * The ContactPackage class.
 */
class ContactPackage extends AbstractPackage implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container->prepareSharedObject(ContactService::class);

        $container->mergeParameters(
            'renderer.paths',
            [
                static::path('/views')
            ],
            new MergeOptions(override: true)
        );
    }

    public function install(PackageInstaller $installer): void
    {
        $installer->installConfig(static::path('etc/*.php'), 'config');
        $installer->installLanguages(static::path('resources/languages/**/*.ini'), 'lang');
        $installer->installMigrations(static::path('resources/migrations/**/*'), 'migrations');
        $installer->installSeeders(static::path('resources/seeders/**/*'), 'seeders');
        $installer->installRoutes(static::path('routes/**/*.php'), 'routes');

        $installer->installMVCModules(Contact::class);
    }
}
