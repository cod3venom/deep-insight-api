<?php

declare(strict_types=1);

namespace App\Controller\Admin\Users;

use App\Entity\User\User;
use App\Entity\User\UserCompanyInfo;
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
            $entityInstance->company = new UserCompanyInfo();
        }

        $entityInstance
            ->setUserId($userId)
            ->setLastLoginAt()
            ->setCreatedAt();

        $entityInstance->setPassword(password_hash($entityInstance->getPassword(), PASSWORD_DEFAULT));

        $entityInstance->profile
            ->setUserId($userId)
            ->setEmail($entityInstance->getEmail())
            ->setCreatedAt();

        $entityInstance->profile
            ->setAvatar($_ENV['BACKEND_ASSETS'] . '/avatar/'.$entityInstance->profile->getAvatar());

        $entityInstance->company
            ->setUserId($userId)
            ->setCreatedAt();


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
            $entityInstance->company = new UserCompanyInfo();
        }

        $entityInstance->profile
            ->setEmail($entityInstance->getEmail())
            ->setUpdatedAt();

        if (!str_contains($entityInstance->profile->getAvatar(), 'http')) {
            $entityInstance->profile
                ->setAvatar($_ENV['BACKEND_ASSETS'] . '/avatar/' . $entityInstance->profile->getAvatar());
        }



        $entityInstance->company
            ->setUpdatedAt();


        if (in_array(User::ROLE_SUB_USER, $entityInstance->getRoles())) {
            $entityInstance->setUserAuthorId($entityInstance->getUserId());
        }

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
        $email = TextField::new('email');
        $password = TextField::new('password');
        $role = ArrayField::new('roles');

        // Profile
        $firstName = TextField::new('profile.firstName', 'First Name');
        $lasName = TextField::new('profile.lastName', 'Last Name');
        $phone =  TextField::new('profile.phone', 'Phone number');
        $birthDay =  DateField::new('profile.birthDay', 'Birth Day')
            ->setFormat('d/m/Y')
            ->setHelp('Choose birth date of the user');
        $avatarIcon = ImageField::new('profile.avatar', 'Profile image')
            ->setUploadDir($this->parameterBag->get('user_avatars_dir'));


        // Company
        $companyName = TextField::new('company.companyName', 'Name');
        $companyWww = TextField::new('company.companyWww', 'Web-Site')
            ->setHelp('for eg: www.company.com, https://company.com, https://www.company.com');
        $industry = TextField::new('company.companyIndustry', 'Industry')
            ->setHelp('for eg: IT, Manufacturer, Food');
        $wayToEarnMoney = TextField::new('company.wayToEarnMoney', 'Way to earn money');
        $regon = NumberField::new('company.regon', 'Regon');
        $krs = NumberField::new('company.krs', 'Krs');
        $nip = NumberField::new('company.nip', 'Nip');
        $districts = TextareaField::new('company.districts', 'Districts');
        $headQuartersCity = TextField::new('company.headQuartersCity', 'Head Quarters');
        $businessEmails = TextareaField::new('company.businessEmails', 'Business Emails');
        $businessPhones = TextareaField::new('company.businessPhones', 'Business phones');
        $revenue = TextField::new('company.revenue', 'Revenue');
        $profit = TextField::new('company.profit', 'Profit');
        $growthYearToYear = TextareaField::new('company.growthYearToYear', 'Growth year to year');
        $categories = TextareaField::new('company.Categories', 'Categories');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$avatarIcon, $firstName, $lasName,$email, $role];
        }
        return [
                FormField::addPanel('Credentials'),
                $authorId, $email, $password, $role,
                FormField::addPanel('Profile'),
                $firstName, $lasName, $phone, $birthDay, $avatarIcon,
                FormField::addPanel('Company'),
                $companyName, $companyWww, $industry, $wayToEarnMoney,
                $regon, $krs, $nip, $districts, $headQuartersCity,
                $businessEmails, $businessPhones,
                $revenue, $profit, $growthYearToYear,
                $categories
            ];
    }
}
