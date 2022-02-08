<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\HumanTraits\TraitAnalysisCrud;
use App\Controller\Admin\HumanTraits\TraitCategoryCrud;
use App\Controller\Admin\HumanTraits\TraitColorCrud;
use App\Controller\Admin\HumanTraits\TraitItemCrud;
use App\Controller\Admin\Users\UsersCrud;
use App\Entity;
use Doctrine\Common\Collections\Criteria;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard as EADashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class Dashboard extends AbstractDashboardController
{
    private AdminUrlGenerator $adminUrlGenerator;
    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    /**
     * @Route("/admin", name="dashboard")
     * @return Response
     */
    public function index(): Response
    {
        return $this->redirect(
            $this->adminUrlGenerator
                ->setController(UsersCrud::class)
                ->setAction('index')
                ->generateUrl()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureDashboard(): EADashboard
    {
        return EADashboard::new()->setTitle('Deep-Insight-Discovery');
    }

    /**
     * {@inheritdoc}
     */
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Users', 'fas fa-users', Entity\User\User::class)
            ->setController(UsersCrud::class);

        yield MenuItem::subMenu('Human traits', 'fa fa-database')->setSubItems([

            MenuItem::linkToCrud('Trait Categories', 'fas fa-sitemap', Entity\HumanTraits\TraitCategory::class)
                ->setController(TraitCategoryCrud::class),

            MenuItem::linkToCrud('Trait Items', 'fas fa-list', Entity\HumanTraits\TraitItem::class)
                ->setController(TraitItemCrud::class),

            MenuItem::linkToCrud('Trait Colors', 'fas fa-palette', Entity\HumanTraits\TraitColor::class)
                ->setController(TraitColorCrud::class)

        ]);

        yield MenuItem::subMenu('Analyses', 'fas fa-chart-pie')->setSubItems([

            MenuItem::linkToCrud('Records', 'fas fa-list', Entity\HumanTraits\TraitAnalysis::class)
                ->setController(TraitAnalysisCrud::class),

            MenuItem::linkToRoute('Import from sheet', 'fa fa-file-import', 'admin_import_analysis_from_sheet')

        ]);
    }
}
