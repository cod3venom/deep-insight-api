<?php

declare(strict_types=1);

namespace App\Controller\Admin\Users;

use App\Entity\User\User;
use App\Entity\User\ContactCompany;
use App\Modules\Reflector\Reflector;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Proxies\__CG__\App\Entity\User\UserProfile;
use Ramsey\Uuid\Uuid;
use ReflectionException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class UsersCrud extends AbstractCrudController
{
    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    public function __construct(
        ParameterBagInterface $parameterBag,
        UserRepository $userRepository
    )
    {
        $this->parameterBag = $parameterBag;
        $this->userRepository = $userRepository;
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
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof User) {
           return;
        }

        $userId = Uuid::uuid4()->toString();

        if (is_null($entityInstance->profile)) {
            $entityInstance->profile = new UserProfile();
        }
        if (is_null($entityInstance->company)) {
            $entityInstance->company = new ContactCompany();
        }

        $entityInstance->setUserId($userId)->setLastLoginAt()->setCreatedAt();
        $entityInstance->setPassword(password_hash($entityInstance->getPassword(), PASSWORD_DEFAULT));
        $entityInstance->profile->setUserId($userId)->setEmail($entityInstance->getEmail())->setCreatedAt();
        $entityInstance->company->setUserId($userId)->setCreatedAt();
        $this->userRepository->save($entityInstance);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof User) {
            return;
        }

        if (is_null($entityInstance->profile)) {
            $entityInstance->profile = new UserProfile();
        }
        if (is_null($entityInstance->company)) {
            $entityInstance->company = new ContactCompany();
        }

        $entityInstance->profile->setEmail($entityInstance->getEmail())->setUpdatedAt();

        if (!str_starts_with($entityInstance->getPassword(), '$2y$')) {
            $entityInstance->setPassword(password_hash($entityInstance->getPassword(), PASSWORD_DEFAULT));
        }

        if (in_array(User::ROLE_SUB_USER, $entityInstance->getRoles())) {
            $entityInstance->setUserAuthorId($entityInstance->getUserId());
        }

        $entityInstance->company->setUpdatedAt();
        $this->userRepository->update($entityInstance);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
      $this->userRepository->delete($entityInstance);
    }

    /**
     * {@inheritdoc}
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {

        // Account
        $authorId = TextField::new('userAuthorId');
        $email = TextField::new('email')
            ->setRequired(true);

        $password = TextField::new('password')
            ->setRequired(true);

        $role = ArrayField::new('roles')
            ->setRequired(true);
        // Profile
        $firstName = TextField::new('profile.firstName', 'First Name')
            ->setRequired(true);
        $lasName = TextField::new('profile.lastName', 'Last Name')
            ->setRequired(true);
        $phone =  TextField::new('profile.phone', 'Phone number');
        $birthDay =  DateField::new('profile.birthDay', 'Birth Day')
            ->setRequired(true)
            ->setFormat('d/m/Y')
            ->setHelp('Choose birth date of the user');

        $avatar = ImageField::new('profile.avatar', 'Profile image')
            ->setUploadDir($this->parameterBag->get('user_avatars_upload_dir'))
            ->setBasePath($this->parameterBag->get('user_avatars_upload_dir_base'));


        // Company
        $companyName = TextField::new('company.companyName', 'Name');
        $companyWww = TextField::new('company.companyWww', 'Web-Site')
            ->setHelp('for eg: www.company.com, https://company.com, https://www.company.com');
        $industry = TextField::new('company.companyIndustry', 'Industry')
            ->setHelp('for eg: IT, Manufacturer, Food');
        $wayToEarnMoney = TextField::new('company.wayToEarnMoney', 'Way to earn money');
        $regon = TextField::new('company.regon', 'Regon');
        $krs = TextField::new('company.krs', 'Krs');
        $nip = TextField::new('company.nip', 'Nip');
        $districts = TextareaField::new('company.districts', 'Districts');
        $headQuartersCity = TextField::new('company.headQuartersCity', 'Head Quarters');
        $businessEmails = TextareaField::new('company.businessEmails', 'Business Emails');
        $businessPhones = TextareaField::new('company.businessPhones', 'Business phones');
        $revenue = TextField::new('company.revenue', 'Revenue');
        $profit = TextField::new('company.profit', 'Profit');
        $growthYearToYear = TextareaField::new('company.growthYearToYear', 'Growth year to year');
        $categories = TextareaField::new('company.Categories', 'Categories');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$avatar, $firstName, $lasName,$email, $role];
        }
        return [
                FormField::addPanel('Credentials'),
                $authorId, $email, $password, $role,
                FormField::addPanel('Profile'),
                $firstName, $lasName, $phone, $birthDay, $avatar,
                FormField::addPanel('Company'),
                $companyName, $companyWww, $industry, $wayToEarnMoney,
                $regon, $krs, $nip, $districts, $headQuartersCity,
                $businessEmails, $businessPhones,
                $revenue, $profit, $growthYearToYear,
                $categories
            ];
    }
}
