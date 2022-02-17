<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 16.02.2022
 * Time: 22:51
*/

namespace App\DAO;

use App\Entity\HumanTraits\TraitAnalysis;
use App\Entity\User\User;
use App\Entity\User\UserCompanyInfo;
use App\Entity\User\UserProfile;
use App\Repository\TraitColorRepository;
use App\Repository\TraitItemRepository;
use App\Service\HumanTraitServices\HumanTraitsService;

final class UserPackTObject
{
    public User $user;



    public function __construct(
        $id, $userId, $userAuthorId, $email, $password, $pwdRecoveryToken, $roles,
        $profileId, $firstName, $lastName, $profileEmail,  $avatar, $birthDay, $placeOfBirth, $linksToProfiles, $notes, $positionInCompany, $country,
        $companyName, $companyWww, $companyIndustry, $wayToEarnMoney, $regon, $krs, $nip, $districts, $headQuartersCity,$businessEmails, $businessPhones,
        $revenue, $profit, $growthYearToEar, $categories,

        $traitBirthDay, $lifePath, $theDrivingForce, $theMatrixOfExellence, $theMoralCode, $goalAndWants, $behavioursAndNeeds, $seekAndMindset, $reactAndMotivationToAction,
        $joinsAndDesire, $polarisation, $expression, $keyword, $visualSeeItIntuition, $auditoryHearItThinking, $kinestericDoItSensation, $emotiveFeelItFeeling,
        $initializingAndAntithesis, $stabilizingAndSynthesis, $finishingThesis, $doerControl, $thinkerOrder, $waterPeace, $talkerFun, $theValueOf, $belief, $communication,
        $style, $strength, $reward, $tactic, $objective, $worldOfAction, $worldOfMatter, $worldOfInformation, $worldOfFeeling, $worldOfFun, $worldOfUsability, $worldOfRelations,
        $worldOfDesireAndPower, $worldOfSeekAndExplore, $worldOfCareer, $worldOfFuture, $worldOfSpirituality, $p1S, $p2M, $p3My, $p4W, $p5M, $p6J, $p7S, $p8U, $p9N, $p10N, $ptnde

    )
    {

      $user = new User();
      $profile = new UserProfile();
      $company = new UserCompanyInfo();
      $trait = new TraitAnalysis();

      $user->setId($id);
      $profile->setId($id);
      $company->setId($id);

      $user
          ->setUserId($userId)
          ->setUserAuthorId($userAuthorId)
          ->setEmail($email)
          ->setPassword($password)
          ->setPwdRecoveryToken($pwdRecoveryToken)
          ->setRoles($roles);

      $profile->setId($profileId);
      $profile
          ->setUserId($userId)
          ->setFirstName($firstName)
          ->setLastName($lastName)
          ->setEmail($profileEmail)
          ->setAvatar($avatar)
          ->setBirthDay($birthDay)
          ->setPlaceOfBirth($placeOfBirth)
          ->setLinksToProfiles($linksToProfiles)
          ->setNotesDescriptionsComments($notes)
          ->setPositionInTheCompany($positionInCompany)
          ->setCountry($country);

      $company
          ->setUserId($userId)
          ->setCompanyName($companyName)
          ->setCompanyWww($companyWww)
          ->setCompanyIndustry($companyIndustry)
          ->setWayToEarnMoney($wayToEarnMoney)
          ->setRegon($regon)
          ->setKrs($krs)
          ->setNip($nip)
          ->setDistricts($districts)
          ->setHeadQuartersCity($headQuartersCity)
          ->setBusinessEmails($businessEmails)
          ->setBusinessPhones($businessPhones)
          ->setRevenue($revenue)
          ->setProfit($profit)
          ->setGrowthYearToYear($growthYearToEar)
          ->setCategories($categories);

        $trait->setBirthDay($traitBirthDay)
            ->setLifePath($linksToProfiles)
            ->setTheDrivingForce($theDrivingForce)
            ->setTheMatrixOfExellence($theMatrixOfExellence)
            ->setTheMoralCode($theMoralCode)
            ->setGoalAndWants($goalAndWants)
            ->setBehavioursAndNeeds($behavioursAndNeeds)
            ->setSeekAndMindset($seekAndMindset)
            ->setReactAndMotivationToAction($reactAndMotivationToAction)
            ->setJoinsAndDesire($joinsAndDesire)
            ->setPolarisation($polarisation)
            ->setExpression($expression)
            ->setKeyword($keyword)
            ->setVisualSeeItIntuition($visualSeeItIntuition)
            ->setAuditoryHearItThinking($auditoryHearItThinking)
            ->setKinestericDoItSensation($kinestericDoItSensation)
            ->setEmotiveFeelItFeeling($emotiveFeelItFeeling)
            ->setInitializingAndAntithesis($initializingAndAntithesis)
            ->setStabilizingAndSynthesis($stabilizingAndSynthesis)
            ->setFinishingThesis($finishingThesis)
            ->setDoerControl($doerControl)
            ->setThinkerOrder($thinkerOrder)
            ->setWaterPeace($waterPeace)
            ->setTalkerFun($talkerFun)
            ->setTheValueOf($theValueOf);
        $trait
            ->setBelief($belief)
            ->setCommunication($communication)
            ->setStyle($style)
            ->setStrength($strength)
            ->setReward($reward)
            ->setTactic($tactic)
            ->setObjective($objective)
            ->setWorldOfAction($worldOfAction)
            ->setWorldOfMatter($worldOfMatter)
            ->setWorldOfInformation($worldOfInformation)
            ->setWorldOfFeeling($worldOfFeeling)
            ->setWorldOfFun($worldOfFun)
            ->setWorldOfUsability($worldOfUsability)
            ->setWorldOfRelations($worldOfRelations)
            ->setWorldOfDesireAndPower($worldOfDesireAndPower)
            ->setWorldOfSeekAndExplore($worldOfSeekAndExplore);

        $trait->setWorldOfCareer($worldOfCareer)
            ->setWorldOfFuture($worldOfFuture)
            ->setWorldOfSpirituality($worldOfSpirituality)
            ->setP10N($p1S)
            ->setP2M($p2M)
            ->setP3My($p3My)
            ->setP4W($p4W)
            ->setP5M($p5M)
            ->setP6J($p6J)
            ->setP7S($p7S)
            ->setP8U($p8U)
            ->setP9N($p9N)
            ->setP10N($p10N)
            ->setPTNde($ptnde);

        $this->user = $user;
        $this->user->profile = $profile;
        $this->user->company = $company;
        $this->user->profile->traitAnalysis = $trait;
    }
}
