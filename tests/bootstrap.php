<?php

declare(strict_types=1);

$source = dirname(__DIR__) . '/src';
$flexgridSource = dirname(__DIR__, 3) . '/flexgrid/src';

require_once $flexgridSource . '/Utils/_Time.php';
require_once dirname(__DIR__, 2) . '/AdminCore/src/ValueObject/TenantId.php';
require_once $source . '/Contract/DashboardProviderInterface.php';
require_once $source . '/Provider/CoreDashboardProvider.php';
require_once $source . '/Service/DashboardProviderLoader.php';
require_once $source . '/Service/DashboardService.php';

function adminDashboardAssert($condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}
