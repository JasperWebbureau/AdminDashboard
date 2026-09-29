<?php

declare(strict_types=1);

namespace App\Demo\Integration\Dashboard;

use Flexgrid\Modules\AdminCore\ValueObject\TenantId;
use Flexgrid\Modules\AdminDashboard\Contract\DashboardProviderInterface;
use Flexgrid\Utils\_Time;

final class DashboardProvider implements DashboardProviderInterface
{
    public function __construct(TenantId $tenant, _Time $clock) {}

    public function getDashboardContribution(): array
    {
        return ['panels' => [[
            'id' => 'demo-settings',
            'html' => '<div class="panel" style="--cw:6">Instellingen</div>',
            'priority' => 25,
        ]]];
    }
}
