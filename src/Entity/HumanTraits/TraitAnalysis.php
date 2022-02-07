<?php

namespace App\Entity\HumanTraits;

use App\Entity\Traits\CreatedTrait;
use App\Entity\Traits\UpdatedTrait;
use App\Repository\TraitAnalysisRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TraitAnalysisRepository::class)
 */
class TraitAnalysis
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $birthDay = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $lifePath = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $theDrivingForce = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $theMatrixOfExellence = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $theMoralCode = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $goalAndWants = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $behavioursAndNeeds = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $seekAndMindset = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $reactAndMotivationToAction = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $joinsAndDesire = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $polarisation = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $expression = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $keyword = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $visualSeeItIntuition = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $auditoryHearItThinking = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $kinestericDoItSensation = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $emotiveFeelItFeeling = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $initializingAndAntithesis = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $stabilizingAndSynthesis = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $finishingThesis = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $doerControl = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $thinkerOrder = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $waterPeace = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $talkerFun = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $theValueOf = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $belief = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $communication = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $style = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $strength = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $reward = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $tactic = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $objective = null;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $worldOfMatter = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $worldOfInformation = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $worldOfFeeling = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $worldOfFun = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $worldOfUsability = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $worldOfRelations = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $worldOfDesireAndPower = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $worldOfSeekAndExplore = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $worldOfCareer = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $worldOfFuture = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $worldOfSpirituality = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $P1S = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $P2M = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $P3MY = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $P4W = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $P5M = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $P6J = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $P7S = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $P8U = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $P9N = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $P10N = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $PTNde = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBirthDay(): ?string
    {
        return $this->birthDay;
    }

    public function setBirthDay(?string $birthDay): self
    {
        $this->birthDay = $birthDay;

        return $this;
    }

    public function getLifePath(): ?string
    {
        return $this->lifePath;
    }

    public function setLifePath(?string $lifePath): self
    {
        $this->lifePath = $lifePath;

        return $this;
    }

    public function getTheDrivingForce(): ?string
    {
        return $this->theDrivingForce;
    }

    public function setTheDrivingForce(?string $theDrivingForce): self
    {
        $this->theDrivingForce = $theDrivingForce;

        return $this;
    }

    public function getTheMatrixOfExellence(): ?string
    {
        return $this->theMatrixOfExellence;
    }

    public function setTheMatrixOfExellence(?string $theMatrixOfExellence): self
    {
        $this->theMatrixOfExellence = $theMatrixOfExellence;

        return $this;
    }

    public function getTheMoralCode(): ?string
    {
        return $this->theMoralCode;
    }

    public function setTheMoralCode(?string $theMoralCode): self
    {
        $this->theMoralCode = $theMoralCode;

        return $this;
    }

    public function getGoalAndWants(): ?string
    {
        return $this->goalAndWants;
    }

    public function setGoalAndWants(?string $goalAndWants): self
    {
        $this->goalAndWants = $goalAndWants;

        return $this;
    }

    public function getBehavioursAndNeeds(): ?string
    {
        return $this->behavioursAndNeeds;
    }

    public function setBehavioursAndNeeds(?string $behavioursAndNeeds): self
    {
        $this->behavioursAndNeeds = $behavioursAndNeeds;

        return $this;
    }

    public function getSeekAndMindset(): ?string
    {
        return $this->seekAndMindset;
    }

    public function setSeekAndMindset(?string $seekAndMindset): self
    {
        $this->seekAndMindset = $seekAndMindset;

        return $this;
    }

    public function getReactAndMotivationToAction(): ?string
    {
        return $this->reactAndMotivationToAction;
    }

    public function setReactAndMotivationToAction(?string $reactAndMotivationToAction): self
    {
        $this->reactAndMotivationToAction = $reactAndMotivationToAction;

        return $this;
    }

    public function getJoinsAndDesire(): ?string
    {
        return $this->joinsAndDesire;
    }

    public function setJoinsAndDesire(?string $joinsAndDesire): self
    {
        $this->joinsAndDesire = $joinsAndDesire;

        return $this;
    }

    public function getPolarisation(): ?string
    {
        return $this->polarisation;
    }

    public function setPolarisation(?string $polarisation): self
    {
        $this->polarisation = $polarisation;

        return $this;
    }

    public function getExpression(): ?string
    {
        return $this->expression;
    }

    public function setExpression(?string $expression): self
    {
        $this->expression = $expression;

        return $this;
    }

    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    public function setKeyword(?string $keyword): self
    {
        $this->keyword = $keyword;

        return $this;
    }

    public function getVisualSeeItIntuition(): ?string
    {
        return $this->visualSeeItIntuition;
    }

    public function setVisualSeeItIntuition(?string $visualSeeItIntuition): self
    {
        $this->visualSeeItIntuition = $visualSeeItIntuition;

        return $this;
    }

    public function getAuditoryHearItThinking(): ?string
    {
        return $this->auditoryHearItThinking;
    }

    public function setAuditoryHearItThinking(?string $auditoryHearItThinking): self
    {
        $this->auditoryHearItThinking = $auditoryHearItThinking;

        return $this;
    }

    public function getKinestericDoItSensation(): ?string
    {
        return $this->kinestericDoItSensation;
    }

    public function setKinestericDoItSensation(?string $kinestericDoItSensation): self
    {
        $this->kinestericDoItSensation = $kinestericDoItSensation;

        return $this;
    }

    public function getEmotiveFeelItFeeling(): ?string
    {
        return $this->emotiveFeelItFeeling;
    }

    public function setEmotiveFeelItFeeling(?string $emotiveFeelItFeeling): self
    {
        $this->emotiveFeelItFeeling = $emotiveFeelItFeeling;

        return $this;
    }

    public function getInitializingAndAntithesis(): ?string
    {
        return $this->initializingAndAntithesis;
    }

    public function setInitializingAndAntithesis(?string $initializingAndAntithesis): self
    {
        $this->initializingAndAntithesis = $initializingAndAntithesis;

        return $this;
    }

    public function getStabilizingAndSynthesis(): ?string
    {
        return $this->stabilizingAndSynthesis;
    }

    public function setStabilizingAndSynthesis(?string $stabilizingAndSynthesis): self
    {
        $this->stabilizingAndSynthesis = $stabilizingAndSynthesis;

        return $this;
    }

    public function getFinishingThesis(): ?string
    {
        return $this->finishingThesis;
    }

    public function setFinishingThesis(?string $finishingThesis): self
    {
        $this->finishingThesis = $finishingThesis;

        return $this;
    }

    public function getDoerControl(): ?string
    {
        return $this->doerControl;
    }

    public function setDoerControl(?string $doerControl): self
    {
        $this->doerControl = $doerControl;

        return $this;
    }

    public function getThinkerOrder(): ?string
    {
        return $this->thinkerOrder;
    }

    public function setThinkerOrder(?string $thinkerOrder): self
    {
        $this->thinkerOrder = $thinkerOrder;

        return $this;
    }

    public function getWaterPeace(): ?string
    {
        return $this->waterPeace;
    }

    public function setWaterPeace(?string $waterPeace): self
    {
        $this->waterPeace = $waterPeace;

        return $this;
    }

    public function getTalkerFun(): ?string
    {
        return $this->talkerFun;
    }

    public function setTalkerFun(?string $talkerFun): self
    {
        $this->talkerFun = $talkerFun;

        return $this;
    }
    public function getTheValueOf(): ?string
    {
        return $this->theValueOf;
    }

    public function setTheValueOf(?string $theValueOf): self
    {
        $this->theValueOf = $theValueOf;

        return $this;
    }

    public function getBelief(): ?string
    {
        return $this->belief;
    }

    public function setBelief(?string $belief): self
    {
        $this->belief = $belief;

        return $this;
    }

    public function getCommunication(): ?string
    {
        return $this->communication;
    }

    public function setCommunication(?string $communication): self
    {
        $this->communication = $communication;

        return $this;
    }

    public function getStyle(): ?string
    {
        return $this->style;
    }

    public function setStyle(?string $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function getStrength(): ?string
    {
        return $this->strength;
    }

    public function setStrength(?string $strength): self
    {
        $this->strength = $strength;

        return $this;
    }

    public function getReward(): ?string
    {
        return $this->reward;
    }

    public function setReward(?string $reward): self
    {
        $this->reward = $reward;

        return $this;
    }

    public function getTactic(): ?string
    {
        return $this->tactic;
    }

    public function setTactic(?string $tactic): self
    {
        $this->tactic = $tactic;

        return $this;
    }

    public function getObjective(): ?string
    {
        return $this->objective;
    }

    public function setObjective(?string $objective): self
    {
        $this->objective = $objective;

        return $this;
    }
    public function getWorldOfMatter(): ?string
    {
        return $this->worldOfMatter;
    }

    public function setWorldOfMatter(?string $worldOfMatter): self
    {
        $this->worldOfMatter = $worldOfMatter;

        return $this;
    }

    public function getWorldOfInformation(): ?string
    {
        return $this->worldOfInformation;
    }

    public function setWorldOfInformation(?string $worldOfInformation): self
    {
        $this->worldOfInformation = $worldOfInformation;

        return $this;
    }

    public function getWorldOfFeeling(): ?string
    {
        return $this->worldOfFeeling;
    }

    public function setWorldOfFeeling(?string $worldOfFeeling): self
    {
        $this->worldOfFeeling = $worldOfFeeling;

        return $this;
    }

    public function getWorldOfFun(): ?string
    {
        return $this->worldOfFun;
    }

    public function setWorldOfFun(?string $worldOfFun): self
    {
        $this->worldOfFun = $worldOfFun;

        return $this;
    }

    public function getWorldOfUsability(): ?string
    {
        return $this->worldOfUsability;
    }

    public function setWorldOfUsability(?string $worldOfUsability): self
    {
        $this->worldOfUsability = $worldOfUsability;

        return $this;
    }

    public function getWorldOfRelations(): ?string
    {
        return $this->worldOfRelations;
    }

    public function setWorldOfRelations(?string $worldOfRelations): self
    {
        $this->worldOfRelations = $worldOfRelations;

        return $this;
    }

    public function getWorldOfDesireAndPower(): ?string
    {
        return $this->worldOfDesireAndPower;
    }

    public function setWorldOfDesireAndPower(?string $worldOfDesireAndPower): self
    {
        $this->worldOfDesireAndPower = $worldOfDesireAndPower;

        return $this;
    }

    public function getWorldOfSeekAndExplore(): ?string
    {
        return $this->worldOfSeekAndExplore;
    }

    public function setWorldOfSeekAndExplore(?string $worldOfSeekAndExplore): self
    {
        $this->worldOfSeekAndExplore = $worldOfSeekAndExplore;

        return $this;
    }

    public function getWorldOfCareer(): ?string
    {
        return $this->worldOfCareer;
    }

    public function setWorldOfCareer(?string $worldOfCareer): self
    {
        $this->worldOfCareer = $worldOfCareer;

        return $this;
    }

    public function getWorldOfFuture(): ?string
    {
        return $this->worldOfFuture;
    }

    public function setWorldOfFuture(?string $worldOfFuture): self
    {
        $this->worldOfFuture = $worldOfFuture;

        return $this;
    }

    public function getWorldOfSpirituality(): ?string
    {
        return $this->worldOfSpirituality;
    }

    public function setWorldOfSpirituality(?string $worldOfSpirituality): self
    {
        $this->worldOfSpirituality = $worldOfSpirituality;

        return $this;
    }

    public function getP1S(): ?string
    {
        return $this->P1S;
    }

    public function setP1S(?string $P1S): self
    {
        $this->P1S = $P1S;

        return $this;
    }

    public function getP2M(): ?string
    {
        return $this->P2M;
    }

    public function setP2M(?string $P2M): self
    {
        $this->P2M = $P2M;

        return $this;
    }

    public function getP3My(): ?string
    {
        return $this->P3MY;
    }

    public function setP3My(?string $P3MY): self
    {
        $this->P3MY = $P3MY;

        return $this;
    }

    public function getP4W(): ?string
    {
        return $this->P4W;
    }

    public function setP4W(?string $P4W): self
    {
        $this->P4W = $P4W;

        return $this;
    }

    public function getP5M(): ?string
    {
        return $this->P5M;
    }

    public function setP5M(?string $P5M): self
    {
        $this->P5M = $P5M;

        return $this;
    }

    public function getP6J(): ?string
    {
        return $this->P6J;
    }

    public function setP6J(?string $P6J): self
    {
        $this->P6J = $P6J;

        return $this;
    }

    public function getP7S(): ?string
    {
        return $this->P7S;
    }

    public function setP7S(?string $P7S): self
    {
        $this->P7S = $P7S;

        return $this;
    }

    public function getP8U(): ?string
    {
        return $this->P8U;
    }

    public function setP8U(?string $P8U): self
    {
        $this->P8U = $P8U;

        return $this;
    }

    public function getP9N(): ?string
    {
        return $this->P9N;
    }

    public function setP9N(?string $P9N): self
    {
        $this->P9N = $P9N;

        return $this;
    }

    public function getP10N(): ?string
    {
        return $this->P10N;
    }

    public function setP10N(?string $P10N): self
    {
        $this->P10N = $P10N;

        return $this;
    }

    public function getPTNde(): ?string
    {
        return $this->PTNde;
    }

    public function setPTNde(?string $PTNde): self
    {
        $this->PTNde = $PTNde;

        return $this;
    }

}
