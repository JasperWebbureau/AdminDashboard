<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

use Flexgrid\Modules\AdminDashboard\Contract\DashboardProviderInterface;
use Flexgrid\Modules\AdminDashboard\Provider\CoreDashboardProvider;
use Flexgrid\Modules\AdminDashboard\Service\DashboardService;
use Flexgrid\Utils\_Time;

$overrideProvider = new class implements DashboardProviderInterface {
    public function getDashboardContribution(): array
    {
        return [
            'summary_cards' => [
                [
                    'id' => 'open-invoices',
                    'label' => 'Openstaande facturen',
                    'value' => '3',
                    'meta' => '€ 1.250,00',
                    'icon' => 'fas fa-file-invoice',
                    'tone' => 'warning',
                ],
            ],
            'activities' => [
                ['id' => 'event-1', 'title' => 'Factuur verzonden'],
            ],
            'panels' => [[
                'id' => 'test-settings', 'html' => '<div class="panel" style="--cw:6">Instellingen</div>', 'priority' => 20,
            ]],
        ];
    }
};

$service = new DashboardService([
    new CoreDashboardProvider(),
    $overrideProvider,
], new _Time(0));
$viewModel = $service->getViewModel();

adminDashboardAssert(count($viewModel['summary_cards']) === 4, 'Provideroverride mag geen dubbele summary card maken.');
adminDashboardAssert($viewModel['summary_cards'][0]['value'] === '3', 'Latere provider moet dezelfde stabiele id kunnen invullen.');
adminDashboardAssert(count($viewModel['activities']) === 1, 'Activiteiten van providers moeten worden samengevoegd.');
adminDashboardAssert(count($viewModel['panels']) === 1
    && $viewModel['panels'][0]['id'] === 'test-settings', 'Projectpanelen moeten als dashboardbijdrage beschikbaar zijn.');
adminDashboardAssert($viewModel['updated_at'] === date('d-m-Y H:i', 0), 'Dashboardtijd moet uit de geïnjecteerde _Time komen.');

echo "AdminDashboard service tests passed.\n";
