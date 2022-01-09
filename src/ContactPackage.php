<?php

/**
 * Part of eva project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace Lyrasoft\Contact;

use Lyrasoft\Contact\Service\ContactService;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Package\PackageInstaller;
use Windwalker\DI\Container;
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
            Container::MERGE_OVERRIDE
        );
    }

    public function install(PackageInstaller $installer): void
    {
        $installer->installConfig(static::path('etc/*.php'), 'config');
        $installer->installLanguages(static::path('resources/languages/**/*.ini'), 'lang');
        $installer->installMigrations(static::path('resources/migrations/**/*'), 'migrations');
        $installer->installSeeders(static::path('resources/seeders/**/*'), 'seeders');
        $installer->installRoutes(static::path('routes/**/*.php'), 'routes');

        // Modules
        $installer->installModules(
            [
                static::path("src/Module/Admin/Contact/**/*") => "@source/Module/Admin/Contact",
            ],
            ['Lyrasoft\\Luna\\Module\\Admin' => 'App\\Module\\Admin'],
            ['modules', 'contact_admin'],
        );

        $installer->installModules(
            [
                static::path("src/Module/Front/Contact/**/*") => "@source/Module/Front/Contact",
            ],
            ['Lyrasoft\\Luna\\Module\\Front' => 'App\\Module\\Front'],
            ['modules', 'contact_front'],
        );

        $installer->installModules(
            [
                static::path("src/Entity/Contact.php") => '@source/Entity',
                static::path("src/Repository/ContactRepository.php") => '@source/Repository',
            ],
            [
                'Lyrasoft\\Luna\\Entity' => 'App\\Entity',
                'Lyrasoft\\Luna\\Repository' => 'App\\Repository',
            ],
            ['modules', 'contact_model']
        );
    }
}
