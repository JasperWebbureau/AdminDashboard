<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

require_once __DIR__ . '/bootstrap.php';

$projectRoot = dirname(__DIR__, 4);
$modulesRoot = dirname(__DIR__, 2);
require_once $projectRoot . '/flexgrid/flexgrid/src/Database/Connection.php';
require_once $modulesRoot . '/AdminCore/src/ValueObject/Currency.php';
require_once $modulesRoot . '/AdminCore/src/Support/IntegerMath.php';
require_once $modulesRoot . '/AdminCore/src/ValueObject/Money.php';
require_once dirname(__DIR__) . '/src/Provider/AbstractPdoDashboardProvider.php';
foreach (['AdminCustomer', 'AdminQuote', 'AdminInvoice', 'AdminExpense', 'AdminBanking'] as $module) {
    require_once $modulesRoot . '/' . $module . '/src/Integration/Dashboard/DashboardProvider.php';
}

require $projectRoot . '/.env.php';
$connection = new PDO(
    'mysql:host=' . $db['host'] . ';dbname=' . $db['name'],
    $db['username'],
    $db['password'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
);
unset($db);

$tenant = new \Flexgrid\Modules\AdminCore\ValueObject\TenantId('dashboard-provider-test-empty');
$clock = new \Flexgrid\Utils\_Time(strtotime('2026-09-21 12:00:00'));
$classes = [
    \Flexgrid\Modules\AdminCustomer\Integration\Dashboard\DashboardProvider::class,
    \Flexgrid\Modules\AdminQuote\Integration\Dashboard\DashboardProvider::class,
    \Flexgrid\Modules\AdminInvoice\Integration\Dashboard\DashboardProvider::class,
    \Flexgrid\Modules\AdminExpense\Integration\Dashboard\DashboardProvider::class,
    \Flexgrid\Modules\AdminBanking\Integration\Dashboard\DashboardProvider::class,
];

foreach ($classes as $class) {
    $contribution = (new $class($tenant, $clock, $connection))->getDashboardContribution();
    adminDashboardAssert(isset($contribution['modules'][0]['id']), $class . ' moet ook zonder tenantdata een modulebijdrage leveren.');
}

$configuredTenant = new \Flexgrid\Modules\AdminCore\ValueObject\TenantId((string)constant('__ADMIN_TENANT_ID__'));
foreach ($classes as $class) {
    $contribution = (new $class($configuredTenant, $clock, $connection))->getDashboardContribution();
    adminDashboardAssert(isset($contribution['modules'][0]['href']), $class . ' moet actuele projectdata veilig kunnen uitlezen en linken.');
}

echo "AdminDashboard PDO-provider tests passed.\n";
