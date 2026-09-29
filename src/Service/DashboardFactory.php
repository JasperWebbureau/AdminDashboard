<?php

declare(strict_types=1);

namespace Flexgrid\Modules\AdminDashboard\Service;

use Flexgrid\Autowire\ControllerResolver;
use Flexgrid\Modules\AdminDashboard\Provider\CoreDashboardProvider;
use Flexgrid\Modules\AdminCore\ValueObject\TenantId;
use Flexgrid\Utils\_Time;

final class DashboardFactory
{
    public static function create(): DashboardService
    {
        if (!class_exists(TenantId::class)) {
            throw new \LogicException('AdminDashboard vereist de AdminCore-module.');
        }

        if (!defined('__ADMIN_TENANT_ID__')) {
            throw new \LogicException('Definieer __ADMIN_TENANT_ID__ expliciet voor het administratiedashboard.');
        }

        $requestTime = new _Time();
        $tenant = new TenantId((string)constant('__ADMIN_TENANT_ID__'));
        $providers = [new CoreDashboardProvider()];
        $providers = array_merge($providers, (new DashboardProviderLoader(
            dirname(__DIR__, 3),
            $tenant,
            $requestTime,
            self::activeModules(),
            [],
            rtrim((string)__ROOTDIR__, '/\\') . '/App'
        ))->getProviders());

        return new DashboardService($providers, $requestTime);
    }

    private static function activeModules(): array
    {
        $modules = [];
        foreach (ControllerResolver::getAll() as $controller) {
            if (!is_object($controller) || !method_exists($controller, 'getClass') || !method_exists($controller, 'canAccess') || !$controller->canAccess(false)) { continue; }
            if (preg_match('/^Flexgrid\\\\Modules\\\\([A-Za-z][A-Za-z0-9]*)\\\\/', $controller->getClass(), $match) === 1) { $modules[$match[1]] = true; }
        }
        return array_keys($modules);
    }
}
