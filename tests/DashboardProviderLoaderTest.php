<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

use Flexgrid\Modules\AdminCore\ValueObject\TenantId;
use Flexgrid\Modules\AdminDashboard\Contract\DashboardProviderInterface;
use Flexgrid\Modules\AdminDashboard\Service\DashboardProviderLoader;
use Flexgrid\Utils\_Time;

final class DashboardProviderLoaderFake implements DashboardProviderInterface
{
    public static $tenant = '';
    public static $timestamp = 0;

    public function __construct(TenantId $tenant, _Time $clock)
    {
        self::$tenant = $tenant->toString();
        self::$timestamp = $clock->get();
    }

    public function getDashboardContribution(): array
    {
        return ['modules' => []];
    }
}

$loader = new DashboardProviderLoader('', new TenantId('dashboard-tenant'), new _Time(1234), [], [DashboardProviderLoaderFake::class]);
$providers = $loader->getProviders();

adminDashboardAssert(count($providers) === 1 && $providers[0] instanceof DashboardProviderLoaderFake, 'Loader moet expliciete providerclasses kunnen maken.');
adminDashboardAssert(DashboardProviderLoaderFake::$tenant === 'dashboard-tenant', 'Loader moet de actieve tenant injecteren.');
adminDashboardAssert(DashboardProviderLoaderFake::$timestamp === 1234, 'Loader moet dezelfde requesttijd injecteren.');

$appLoader = new DashboardProviderLoader('', new TenantId('dashboard-tenant'), new _Time(1234), [], [], __DIR__ . '/Fixtures/App');
$appProviders = $appLoader->getProviders();
adminDashboardAssert(count($appProviders) === 1
    && $appProviders[0] instanceof \App\Demo\Integration\Dashboard\DashboardProvider,
    'Projectproviders onder App moeten zonder Flexgrid-corewijziging ontdekt worden.');

$source = dirname(__DIR__) . '/src/Service/DashboardProviderLoader.php';
$loaderCode = file_get_contents($source);
adminDashboardAssert(strpos($loaderCode, '*/src/Integration/Dashboard/DashboardProvider.php') !== false, 'Loader moet de afgesproken moduleconventie gebruiken.');
adminDashboardAssert(strpos($loaderCode, "module === 'AdminDashboard'") !== false, 'Dashboard mag zichzelf niet als businessprovider laden.');

echo "AdminDashboard provider-loader tests passed.\n";
