<?php

declare(strict_types=1);

namespace App\Controller\Admin\Users;

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


    /**
     * {@inheritdoc}
     */
    public function configureFields(string $pageName): iterable
    {

        $email     = TextField::new('email')->hideOnForm();
        $password     = TextField::new('password')->hideOnForm();
        $role     = ArrayField::new('roles')->hideOnForm();

        $firstname = TextField::new('profile')->setProperty('firstName')->hideOnForm();
        $lastName =  TextField::new('profile')->setProperty('lastName')->hideOnForm();
        $email =  TextField::new('profile')->setProperty('email')->hideOnForm();
        $phone =  TextField::new('profile')->setProperty('phone')->hideOnForm();
        $birthDay =  TextField::new('profile')->setProperty('firstName')->hideOnForm();

        if (Crud::PAGE_INDEX === $pageName) {
            return [$email, $password, $role, $firstname, $lastName];
        }

        return [$email, $role];
    }
}
