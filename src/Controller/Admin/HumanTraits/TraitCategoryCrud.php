<?php

declare(strict_types=1);

namespace App\Controller\Admin\HumanTraits;

use App\Entity\HumanTraits\TraitCategory;
use App\Entity\HumanTraits\TraitColor;
use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
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


class TraitCategoryCrud extends AbstractCrudController
{
    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;

        public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * {@inheritdoc}
     */
    public static function getEntityFqcn(): string
    {
        return TraitCategory::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Trait')
            ->setEntityLabelInPlural('Categories')
            ->setSearchFields(['categoryId', 'categoryName'])
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('categoryName');
    }


    /**
     * {@inheritdoc}
     */
    public function configureFields(string $pageName): iterable
    {
        $id    = IntegerField::new('categoryId');
        $name     = TextField::new('categoryName');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $name];
        }
        return [$name];
    }
}
