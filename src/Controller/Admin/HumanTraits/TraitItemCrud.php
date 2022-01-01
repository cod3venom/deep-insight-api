<?php

declare(strict_types=1);

namespace App\Controller\Admin\HumanTraits;

use App\Entity\HumanTraits\TraitCategory;
use App\Entity\HumanTraits\TraitColor;
use App\Entity\HumanTraits\TraitItem;
use App\Entity\User\User;
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


class TraitItemCrud extends AbstractCrudController
{
    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;

    private TraitCategoryRepository $traitCategoryRepository;

    public function __construct(
        ParameterBagInterface $parameterBag,
        TraitCategoryRepository $traitCategoryRepository
    )
    {
        $this->parameterBag = $parameterBag;
        $this->traitCategoryRepository = $traitCategoryRepository;
    }

    /**
     * {@inheritdoc}
     */
    public static function getEntityFqcn(): string
    {
        return TraitItem::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Trait')
            ->setEntityLabelInPlural('Items')
            ->setSearchFields(['id', 'name', 'icon', 'categoryName'])
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('name')
            ->add('dataType');
    }


    /**
     * {@inheritdoc}
     */
    public function configureFields(string $pageName): iterable
    {

        $id         = IntegerField::new('id');
        $name       = TextField::new('name');
        $dataType   = TextField::new('dataType');


        $icon       = ImageField::new('icon')
            ->setUploadDir($this->parameterBag->get('trait_icons_dir'))
            ->setBasePath($this->parameterBag->get('trait_icons_base'));

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $name, $dataType, $icon];
        }
        return [$name, $dataType, $icon];
    }
}
