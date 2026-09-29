<?php

declare(strict_types=1);

namespace Flexgrid\Modules\AdminDashboard\Provider;

use Flexgrid\Modules\AdminDashboard\Contract\DashboardProviderInterface;

final class CoreDashboardProvider implements DashboardProviderInterface
{
    public function getDashboardContribution(): array
    {
        return [
            'summary_cards' => [
                [
                    'id' => 'open-invoices',
                    'label' => 'Openstaande facturen',
                    'value' => null,
                    'meta' => 'Factuurmodule nog niet actief',
                    'icon' => 'fas fa-file-invoice',
                    'tone' => 'info',
                ],
                [
                    'id' => 'monthly-revenue',
                    'label' => 'Omzet deze maand',
                    'value' => null,
                    'meta' => 'Rapportagemodule nog niet actief',
                    'icon' => 'fas fa-chart-bar',
                    'tone' => 'success',
                ],
                [
                    'id' => 'pending-quotes',
                    'label' => 'Offertes in afwachting',
                    'value' => null,
                    'meta' => 'Offertemodule nog niet actief',
                    'icon' => 'fas fa-file-alt',
                    'tone' => 'accent',
                ],
                [
                    'id' => 'overdue-invoices',
                    'label' => 'Te laat betaald',
                    'value' => null,
                    'meta' => 'Factuurmodule nog niet actief',
                    'icon' => 'fas fa-exclamation-circle',
                    'tone' => 'warning',
                ],
            ],
            'activities' => [],
            'quick_actions' => [],
            'attention' => [],
            'tables' => [
                [
                    'id' => 'open-invoices',
                    'title' => 'Openstaande facturen',
                    'icon' => 'fas fa-file-invoice',
                    'columns' => ['Nummer', 'Klant', 'Vervaldatum', 'Bedrag', 'Status'],
                    'rows' => [],
                    'empty' => 'De factuurmodule levert hier straks de openstaande facturen.',
                    'priority' => 10,
                ],
                [
                    'id' => 'active-quotes',
                    'title' => 'Offertes in behandeling',
                    'icon' => 'fas fa-file-alt',
                    'columns' => ['Nummer', 'Klant', 'Geldig t/m', 'Bedrag', 'Status'],
                    'rows' => [],
                    'empty' => 'De offertemodule levert hier straks de actieve offertes.',
                    'priority' => 20,
                ],
            ],
            'modules' => [
                [
                    'id' => 'admin-core',
                    'title' => 'Admin Core',
                    'description' => 'Tenant, geld, transacties, audit en events',
                    'icon' => 'fas fa-layer-group',
                    'status' => 'Actief',
                    'tone' => 'success',
                    'href' => null,
                    'priority' => 0,
                ],
            ],
            'agenda' => [],
        ];
    }
}
