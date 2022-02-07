<?php

declare(strict_types=1);

namespace App\Controller\Admin\HumanTraits;

use App\Entity\HumanTraits\TraitCategory;
use App\Entity\HumanTraits\TraitColor;
use App\Entity\User\User;
use App\Repository\TraitCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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

    private TraitCategoryRepository $repository;
        public function __construct(ParameterBagInterface $parameterBag, TraitCategoryRepository $repository)
    {
        $this->parameterBag = $parameterBag;
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public static function getEntityFqcn(): string
    {
        return TraitCategory::class;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof TraitCategory) {
            $entityInstance->setCreatedAt();
            $this->repository->save($entityInstance);
        }
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof TraitCategory) {
            $entityInstance->setUpdatedAt();
            $this->repository->update($entityInstance);
        }
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->repository->delete($entityInstance);
    }

    /**
     * {@inheritdoc}
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Trait Category')
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
        $id    = IntegerField::new('id');
        $name     = TextField::new('categoryName');
        $position =  IntegerField::new('position');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $name, $position];
        }
        return [$name, $position];
    }
}
