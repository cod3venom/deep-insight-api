<?php

declare(strict_types=1);

namespace App\Controller\Admin\HumanTraits;

use App\Entity\HumanTraits\TraitAnalysis;
use App\Entity\HumanTraits\TraitCategory;
use App\Entity\HumanTraits\TraitColor;
use App\Entity\HumanTraits\TraitItem;
use App\Entity\User\User;
use App\Repository\TraitAnalysisRepository;
use App\Repository\TraitCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Uid\UuidV4;


class TraitAnalysisCrud extends AbstractCrudController
{
    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;

    private TraitAnalysisRepository $traitAnalysisRepository;

    public function __construct(
        ParameterBagInterface $parameterBag,
        TraitAnalysisRepository $traitAnalysisRepository
    )
    {
        $this->parameterBag = $parameterBag;
        $this->traitAnalysisRepository = $traitAnalysisRepository;
    }

    /**
     * {@inheritdoc}
     */
    public static function getEntityFqcn(): string
    {
        return TraitAnalysis::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Analyse')
            ->setEntityLabelInPlural('Analysis')
            ->setSearchFields(['id', 'birthDay'])
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('id')
            ->add('birthDay');
    }


//    /**
//     * {@inheritdoc}
//     */
//    public function configureFields(string $pageName): iterable
//    {
//
//        $id         = IntegerField::new('id');
//        $name       = TextField::new('name');
//        $dataType   = TextField::new('dataType');
//
//
//        $icon       = ImageField::new('icon')
//            ->setUploadDir($this->parameterBag->get('trait_icons_dir'))
//            ->setBasePath($this->parameterBag->get('trait_icons_upload_dir'));
//
//        if (Crud::PAGE_INDEX === $pageName) {
//            return [$id, $name, $dataType, $icon];
//        }
//        return [$name, $dataType, $icon];
//    }
}
