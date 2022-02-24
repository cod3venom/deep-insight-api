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
	 * @param EntityManagerInterface $entityManager
	 * @param                        $entityInstance
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
        

        $entityInstance->setUserId($userId)->setLastLoginAt()->setCreatedAt();
        $entityInstance->setPassword(password_hash($entityInstance->getPassword(), PASSWORD_DEFAULT));
        $entityInstance->profile->setUserId($userId)->setEmail($entityInstance->getEmail())->setCreatedAt();
        $this->userRepository->save($entityInstance);
    }
	
	/**
	 * @param EntityManagerInterface $entityManager
	 * @param                        $entityInstance
	 */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof User) {
            return;
        }

        if (is_null($entityInstance->profile)) {
            $entityInstance->profile = new UserProfile();
        }

        $entityInstance->profile->setEmail($entityInstance->getEmail())->setUpdatedAt();

        if (!str_starts_with($entityInstance->getPassword(), '$2y$')) {
            $entityInstance->setPassword(password_hash($entityInstance->getPassword(), PASSWORD_DEFAULT));
        }
 
        $this->userRepository->update($entityInstance);
    }
	
	/**
	 * @param EntityManagerInterface $entityManager
	 * @param                        $entityInstance
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
        $email = TextField::new('email')->setRequired(true);
        $password = TextField::new('password')->setRequired(true);
        $role = ArrayField::new('roles')->setRequired(true);
		
        // Profile
		$avatar = ImageField::new('profile.avatar', 'Profile image')
			->setUploadDir($this->parameterBag->get('user_avatars_upload_dir'))
			->setBasePath($this->parameterBag->get('user_avatars_upload_dir_base'));
        $firstName = TextField::new('profile.firstName', 'First Name')->setRequired(true);
        $lasName = TextField::new('profile.lastName', 'Last Name')->setRequired(true);
        $birthDay =  DateField::new('profile.birthDay', 'Birth Day')
            ->setRequired(true)
            ->setFormat('d/m/Y')
            ->setHelp('Choose birth date of the user');
	
		$country = TextField::new('profile.country', 'Country')->setRequired(true);
		
        if (Crud::PAGE_INDEX === $pageName) {
            return [$avatar, $firstName, $lasName, $email, $country, $role];
        }
		
        return [
                FormField::addPanel('Credentials'),
				$email, $password, $role,
                FormField::addPanel('Profile'),
				$avatar, $firstName, $lasName, $birthDay,$country,
			
            ];
    }
}
