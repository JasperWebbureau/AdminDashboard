<?php

declare(strict_types=1);

namespace Flexgrid\Modules\AdminDashboard\Controller;

use Flexgrid\Event\AjaxEvent;
use Flexgrid\Flexgrid;
use Flexgrid\Modules\AdminCore\Service\AdminHeader;
use Flexgrid\Modules\AdminDashboard\Service\DashboardFactory;
use Flexgrid\Response\AjaxResponse;
use Flexgrid\Response\PageResponse;
use Flexgrid\Response\TemplateResponse;

/**
 * @FG\Controller [name=AdminDashboard,type=Flexgrid,icon=fas fa-chart-line,level=2,administrationPanel=true,administrationLabel=Overzicht,administrationRoute=dashboard,administrationPriority=10]
 */
final class AdminDashboardController
{
    public function index()
    {
        return $this->dashboard();
    }

    public function dashboard()
    {
        appendIconAndTitleToHeader('fas fa-chart-line', 'Dashboard', 'Administratie');
        PageResponse::addAsset('Flexgrid/Flexgrid/src/Html/Admin/Css/AdminUi.scss');

        $viewModel = DashboardFactory::create()->getViewModel();
        AdminHeader::AdminAddHeader([new TemplateResponse(
            'Flexgrid/Modules/AdminDashboard/src/Templates/Dashboard/HeaderActions.php',
            [
                'headerActions' => array_slice($viewModel['quick_actions'] ?? [], 0, 2),
                'refreshAction' => $this->getRefreshEventName(),
            ]
        )]);

        return new TemplateResponse('Flexgrid/Modules/AdminDashboard/src/Templates/Dashboard/Dashboard.php', [
            'content' => (string)$this->renderContent($viewModel),
        ]);
    }

    public function refresh()
    {
        $response = new AjaxResponse();
        $response->success = true;
        $response->setContainer(
            '[data-admin-dashboard-content]',
            (string)$this->renderContent(DashboardFactory::create()->getViewModel())
        );

        return $response;
    }

    private function renderContent(array $viewModel): TemplateResponse
    {
        return new TemplateResponse(
            'Flexgrid/Modules/AdminDashboard/src/Templates/Dashboard/Content.php',
            $viewModel
        );
    }

    private function getRefreshEventName(): string
    {
        $event = new AjaxEvent(self::class, 'refresh');
        $event->setMinimumAccessLevel(2);

        return $event->getName();
    }
}
