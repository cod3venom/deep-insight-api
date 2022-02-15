<?php

declare(strict_types=1);

namespace App\Controller\Admin\HumanTraits;

use App\Entity\HumanTraits\TraitCategory;
use App\Entity\HumanTraits\TraitColor;
use App\Entity\HumanTraits\TraitItem;
use App\Entity\User\User;
use App\Repository\TraitCategoryRepository;
use App\Repository\TraitItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\UuidV4;


class TraitItemCrud extends AbstractCrudController
{
    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;

    /**
     * @var TraitCategoryRepository
     */
    private TraitCategoryRepository $traitCategoryRepository;

    /**
     * @var TraitItemRepository
     */
    private TraitItemRepository $traitItemRepository;

    public function __construct(
        ParameterBagInterface $parameterBag,
        TraitCategoryRepository $traitCategoryRepository,
        TraitItemRepository $traitItemRepository
    )
    {
        $this->parameterBag = $parameterBag;
        $this->traitCategoryRepository = $traitCategoryRepository;
        $this->traitItemRepository = $traitItemRepository;
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
            ->setSearchFields(['name', 'icon', 'categoryId'])
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('name')
            ->add('dataType');
    }


    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof TraitItem) {
            $entityInstance->setCreatedAt();
            $this->traitItemRepository->save($entityInstance);
        }
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof TraitItem) {
            $entityInstance->setUpdatedAt();
            $this->traitItemRepository->update($entityInstance);
        }
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof TraitItem) {
            $this->traitItemRepository->delete($entityInstance);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureFields(string $pageName): iterable
    {
        $allCategories = $this->traitCategoryRepository->getAllCategoryNames();
        $categoryChoices = [];

        foreach ($allCategories as $category) {
            $categoryChoices[$category['id']] = $category['categoryName'];
        }

        $id         = IntegerField::new('id');
        $name       = TextField::new('name');
        $dataType   = ChoiceField::new('dataType')
            ->setChoices(array_flip((new TraitItem())->dataTypes));

        $categories   = ChoiceField::new('categoryId')
            ->setChoices(array_flip($categoryChoices));




        $icon       = ImageField::new('icon')
            ->setUploadDir($this->parameterBag->get('trait_icons_upload_dir'))
            ->setBasePath($this->parameterBag->get('trait_icons_upload_dir_base'));

        $icon->setUploadedFileNamePattern(function (UploadedFile $file) {
            $name = $file->getClientOriginalName();
            if (strpos($name, '&')) {
                $name = str_replace('&', 'And', $name);
            }

            return $name;
        });

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $name, $categories, $dataType, $icon];
        }
        return [$name, $dataType, $categories, $icon];
    }

}
