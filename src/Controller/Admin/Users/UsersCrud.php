<?php

declare(strict_types=1);

namespace App\Controller\Admin\Users;

use App\Entity\User\User;
use App\Entity\User\UserCompanyInfo;
use App\Modules\Reflector\Reflector;
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
use Proxies\__CG__\App\Entity\User\UserProfile;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class UsersCrud extends AbstractCrudController
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
        return User::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('User')
            ->setEntityLabelInPlural('Users')
            ->setSearchFields(['id', 'email'])
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('email')
            ->add('roles');
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User)
        {
            $userId = Uuid::uuid4()->toString();
            $entityInstance
                ->profile
                ->setUserId($userId)
                ->setEmail($entityInstance->getEmail())
                ->setCreatedAt();

            $entityInstance
                ->company
                ->setUserId($userId)
                ->setCreatedAt();;

            if (in_array(User::ROLE_SUB_USER, $entityInstance->getRoles())) {
                $entityInstance->setUserAuthorId($entityInstance->getUserId());
            }
        }
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User)
        {
           $entityInstance
                ->profile
                ->setEmail($entityInstance->getEmail())
                ->setUpdatedAt();

           $entityInstance
               ->company
               ->setUpdatedAt();

           if (in_array(User::ROLE_SUB_USER, $entityInstance->getRoles())) {
                $entityInstance->setUserAuthorId($entityInstance->getUserId());
            }
        }
    }

    /**
     * {@inheritdoc}
     * @throws \ReflectionException
     */
    public function configureFields(string $pageName): iterable
    {

        $authorId = TextField::new('userAuthorId');
        $email = TextField::new('email');
        $password = TextField::new('password');
        $role = ArrayField::new('roles');

        $firstName = TextField::new('profile')->setProperty('s');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$email, $role];
        }
        return [];
    }
}
