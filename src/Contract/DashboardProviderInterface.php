<?php

declare(strict_types=1);

namespace Flexgrid\Modules\AdminDashboard\Contract;

interface DashboardProviderInterface
{
    /**
     * Ondersteunde keys: summary_cards, activities, quick_actions, attention,
     * tables, modules, agenda en panels. Items hebben een stabiele id nodig.
     * Panels bevatten vertrouwde, server-side gerenderde HTML van de provider.
     */
    public function getDashboardContribution(): array;
}
