<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 16.01.2022
 * Time: 14:35
*/


namespace App\Service\HumanTraitServices\Helpers\SchemaBuilder;
use App\Entity\HumanTraits\TraitAnalysis;
use App\Repository\TraitColorRepository;
use App\Repository\TraitItemRepository;
use JetBrains\PhpStorm\Pure;

class SchemaBuilder
{
    public function buildTraitsFromObject(TraitAnalysis $analysis, TraitItemRepository $itemRepository): array
    {
        if (is_null($analysis->getBirthDay())) {
            return [];
        }
        return [
            [
                'category' => 'The hidden drive',
                'items' => [
                    [
                        'name' => 'Life path',
                        'value' => $analysis->getLifePath(),
                        'icon' => $itemRepository->findIconByTraitName('Life Path')
                    ],
                    [
                        'name' => 'The driving force',
                        'value' => $analysis->getTheDrivingForce(),

                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'The Matrix of Excellence',
                        'value' => $analysis->getTheMatrixOfExellence(),
                        'icon' => $itemRepository->findIconByTraitName('The Matrix of Excellence')
                    ],
                    [
                        'name' => 'The Moral Code',
                        'value' => $analysis->getTheMoralCode(),

                        'icon' => $itemRepository->findIconByTraitName('The Moral Code')
                    ],
                ]
            ],
            [
                'category' => 'The hidden Determinations',
                'items' => [
                    [
                        'name' => 'Goals & Wants',
                        'value' => $analysis->getGoalAndWants(),
                        'icon' => $itemRepository->findIconByTraitName('Goals & Wants')
                    ],
                    [
                        'name' => 'Behaviors & Needs',
                        'value' => $analysis->getBehavioursAndNeeds(),

                        'icon' => $itemRepository->findIconByTraitName('Behaviors & Needs')
                    ],
                    [
                        'name' => 'Seeks & Mindset',
                        'value' => $analysis->getSeekAndMindset(),

                        'icon' => $itemRepository->findIconByTraitName('Seeks & Mindset')
                    ],
                    [
                        'name' => 'React & Motive to Action',
                        'value' => $analysis->getReactAndMotivationToAction(),

                        'icon' => $itemRepository->findIconByTraitName('React & Motive to Action')
                    ],
                    [
                        'name' => 'Joins & Desire',
                        'value' => $analysis->getJoinsAndDesire(),

                        'icon' => $itemRepository->findIconByTraitName('Joins & Desire')
                    ]
                ]
            ],
            [
                'category' => 'The hidden Factors',
                'items' => [
                    [
                        'name' => 'Polarisation',
                        'value' => $analysis->getPolarisation(),

                        'icon' => $itemRepository->findIconByTraitName('Polarisation')
                    ],
                    [
                        'name' => 'Expression',
                        'value' => $analysis->getExpression(),

                        'icon' => $itemRepository->findIconByTraitName('Expression')
                    ],
                    [
                        'name' => 'Keyword',
                        'value' => $analysis->getKeyword(),

                        'icon' => $itemRepository->findIconByTraitName('Keyword')
                    ],
                ]
            ],
            [
                'category' => 'The hidden Memorizing (Learning | Sense | Perception)',
                'items' => [
                    [
                        'name' => 'Visual | See it | Intuition',
                        'value' => $analysis->getVisualSeeItIntuition(),

                        'icon' => $itemRepository->findIconByTraitName('Visual | See it | Intuition')
                    ],
                    [
                        'name' => 'Auditory | Hear it | Thinking',
                        'value' => $analysis->getAuditoryHearItThinking(),

                        'icon' => $itemRepository->findIconByTraitName('Auditory | Hear it | Thinking')
                    ],
                    [
                        'name' => 'Kinesteric | Do it | Sensation',
                        'value' => $analysis->getKinestericDoItSensation(),

                        'icon' => $itemRepository->findIconByTraitName('Kinesteric | Do it | Sensation')
                    ],
                    [
                        'name' => 'Emotive | Feel it | Feeling',
                        'value' => $analysis->getEmotiveFeelItFeeling(),

                        'icon' => $itemRepository->findIconByTraitName('Emotive | Feel it | Feeling')
                    ],
                ]
            ],
            [
                'category' => 'Stage in the process',
                'items' => [
                    [
                        'name' => 'Initializing & antithesis',
                        'value' => $analysis->getInitializingAndAntithesis(),

                        'icon' => $itemRepository->findIconByTraitName('Initializing & antithesis')
                    ],
                    [
                        'name' => 'Stabilizing & synthesis',
                        'value' => $analysis->getStabilizingAndSynthesis(),

                        'icon' => $itemRepository->findIconByTraitName('Stabilizing & synthesis')
                    ],
                    [
                        'name' => 'Finishing & thesis',
                        'value' => $analysis->getFinishingThesis(),

                        'icon' => $itemRepository->findIconByTraitName('Finishing & thesis')
                    ],
                ]
            ],
            [
                'category' => 'Temperament',
                'items' => [
                    [
                        'name' => 'Doer - Control',
                        'value' => $analysis->getDoerControl(),

                        'icon' => $itemRepository->findIconByTraitName('Doer - Control')
                    ],
                    [
                        'name' => 'Thinker - Order',
                        'value' => $analysis->getThinkerOrder(),

                        'icon' => $itemRepository->findIconByTraitName('Thinker - Order')
                    ],
                    [
                        'name' => 'Watcher - Peace',
                        'value' => $analysis->getWaterPeace(),

                        'icon' => $itemRepository->findIconByTraitName('Watcher - Peace')
                    ],
                    [
                        'name' => 'Talker - Fun',
                        'value' => $analysis->getTalkerFun(),

                        'icon' => $itemRepository->findIconByTraitName('Talker - Fun')
                    ],
                ]
            ],
            [
                'category' => 'The hidden Spheres of Life',
                'items' => [
                    [
                        'name' => 'The Value of',
                        'value' => $analysis->getTheValueOf(),

                        'icon' => $itemRepository->findIconByTraitName('The Value of')
                    ],
                    [
                        'name' => 'Belief',
                        'value' => $analysis->getBelief(),

                        'icon' => $itemRepository->findIconByTraitName('Belief')
                    ],
                    [
                        'name' => 'Communication',
                        'value' => $analysis->getCommunication(),

                        'icon' => $itemRepository->findIconByTraitName('Communication')
                    ],
                    [
                        'name' => 'Style',
                        'value' => $analysis->getStyle(),

                        'icon' => $itemRepository->findIconByTraitName('Style')
                    ],
                    [
                        'name' => 'Strength',
                        'value' => $analysis->getStrength(),

                        'icon' => $itemRepository->findIconByTraitName('Strength')
                    ],
                    [
                        'name' => 'Tactic',
                        'value' => $analysis->getTactic(),

                        'icon' => $itemRepository->findIconByTraitName('Tactic')
                    ],
                    [
                        'name' => 'Objective',
                        'value' => $analysis->getObjective(),

                        'icon' => $itemRepository->findIconByTraitName('Objective')
                    ],
                ]
            ],
            [
                'category' => 'Worlds of',
                'items' => [
                    [
                        'name' => 'World of Action',
                        'value' => $analysis->getWorldOfAction(),

                        'icon' => $itemRepository->findIconByTraitName('World of Action')
                    ],
                    [
                        'name' => 'World of Matter',
                        'value' => $analysis->getWorldOfMatter(),

                        'icon' => $itemRepository->findIconByTraitName('World of Matter')
                    ],
                    [
                        'name' => 'World of Information',
                        'value' => $analysis->getWorldOfInformation(),

                        'icon' => $itemRepository->findIconByTraitName('World of Information')
                    ],
                    [
                        'name' => 'World of Feeling',
                        'value' => $analysis->getWorldOfFeeling(),

                        'icon' => $itemRepository->findIconByTraitName('World of Feeling')
                    ],
                    [
                        'name' => 'World of Fun',
                        'value' => $analysis->getWorldOfFun(),

                        'icon' => $itemRepository->findIconByTraitName('World of Fun')
                    ],
                    [
                        'name' => 'World of Usability',
                        'value' => $analysis->getWorldOfUsability(),

                        'icon' => $itemRepository->findIconByTraitName('World of Usability')
                    ],
                    [
                        'name' => 'World of Relations',
                        'value' => $analysis->getWorldOfRelations(),
                        'icon' => $itemRepository->findIconByTraitName('World of Relations')
                    ],
                    [
                        'name' => 'World of Desire&Power',
                        'value' => $analysis->getWorldOfDesireAndPower(),

                        'icon' => $itemRepository->findIconByTraitName('World of Desire&Power')
                    ],
                    [
                        'name' => 'World of Seek&Explore',
                        'value' => $analysis->getWorldOfSeekAndExplore(),

                        'icon' => $itemRepository->findIconByTraitName('World of Seek&Explore')
                    ],
                    [
                        'name' => 'World of Career',
                        'value' => $analysis->getWorldOfCareer(),

                        'icon' => $itemRepository->findIconByTraitName('World of Career')
                    ],
                    [
                        'name' => 'World of Future',
                        'value' => $analysis->getWorldOfFuture(),

                        'icon' => $itemRepository->findIconByTraitName('World of Future')
                    ],
                    [
                        'name' => 'World of Spirituality',
                        'value' => $analysis->getWorldOfSpirituality(),

                        'icon' => $itemRepository->findIconByTraitName('World of Spirituality')
                    ],
                ]
            ],
            [
                'category' => 'Details',
                'items' => [
                    [
                        'name' => 'P1S',
                        'value' => $analysis->getP1S(),

                        'icon' => $itemRepository->findIconByTraitName('P1S')
                    ],
                    [
                        'name' => 'P2M',
                        'value' => $analysis->getP2M(),

                        'icon' => $itemRepository->findIconByTraitName('P2M')
                    ],
                    [
                        'name' => 'P3MY',
                        'value' => $analysis->getP3My(),

                        'icon' => $itemRepository->findIconByTraitName('P3MY')
                    ],
                    [
                        'name' => 'P4W',
                        'value' => $analysis->getP4W(),

                        'icon' => $itemRepository->findIconByTraitName('P4W')
                    ],
                    [
                        'name' => 'P5M',
                        'value' => $analysis->getP5M(),

                        'icon' => $itemRepository->findIconByTraitName('P5M')
                    ],
                    [
                        'name' => 'P6J',
                        'value' => $analysis->getP6J(),

                        'icon' => $itemRepository->findIconByTraitName('P6J')
                    ],
                    [
                        'name' => 'P7S',
                        'value' => $analysis->getP7S(),

                        'icon' => $itemRepository->findIconByTraitName('P7S')
                    ],
                    [
                        'name' => 'P8U',
                        'value' => $analysis->getP8U(),

                        'icon' => $itemRepository->findIconByTraitName('P8U')
                    ],
                    [
                        'name' => 'P9N',
                        'value' => $analysis->getP9N(),

                        'icon' => $itemRepository->findIconByTraitName('P9N')
                    ],
                    [
                        'name' => 'P10N',
                        'value' => $analysis->getP10N(),

                        'icon' => $itemRepository->findIconByTraitName('P10N')
                    ],
                    [
                        'name' => 'PTNde',
                        'value' => $analysis->getPTNde(),

                        'icon' => $itemRepository->findIconByTraitName('PTNde')
                    ],
                ]
            ]
        ];
    }

    public function buildWorldsFromObject(TraitAnalysis $analysis, TraitItemRepository $itemRepository, TraitColorRepository $colorRepository): array
    {
        if (is_null($analysis->getBirthDay())) {
            return [];
        }
        return [
            'category' => 'Worlds of',
            'items' => [
                [
                    'name' => 'World of Action',
                    'value' => $analysis->getWorldOfAction(),
                    'icon' => $itemRepository->findIconByTraitName('World of Action'),
                    'color'=> $colorRepository->findColorByWorldName('World of Action')
                ],
                [
                    'name' => 'World of Matter',
                    'value' => $analysis->getWorldOfMatter(),
                    'icon' => $itemRepository->findIconByTraitName('World of Matter'),
                    'color'=> $colorRepository->findColorByWorldName('World of Matter')
                ],
                [
                    'name' => 'World of Information',
                    'value' => $analysis->getWorldOfInformation(),
                    'icon' => $itemRepository->findIconByTraitName('World of Information'),
                    'color'=> $colorRepository->findColorByWorldName('World of Information')
                ],
                [
                    'name' => 'World of Feeling',
                    'value' => $analysis->getWorldOfFeeling(),
                    'icon' => $itemRepository->findIconByTraitName('World of Feeling'),
                    'color'=> $colorRepository->findColorByWorldName('World of Feeling')
                ],
                [
                    'name' => 'World of Fun',
                    'value' => $analysis->getWorldOfFun(),
                    'icon' => $itemRepository->findIconByTraitName('World of Fun'),
                    'color'=> $colorRepository->findColorByWorldName('World of Fun')
                ],
                [
                    'name' => 'World of Usability',
                    'value' => $analysis->getWorldOfUsability(),
                    'icon' => $itemRepository->findIconByTraitName('World of Usability'),
                    'color'=> $colorRepository->findColorByWorldName('World of Usability')
                ],
                [
                    'name' => 'World of Relations',
                    'value' => $analysis->getWorldOfRelations(),
                    'icon' => $itemRepository->findIconByTraitName('World of Relations'),
                    'color'=> $colorRepository->findColorByWorldName('World of Relations')
                ],
                [
                    'name' => 'World of Desire&Power',
                    'value' => $analysis->getWorldOfDesireAndPower(),
                    'icon' => $itemRepository->findIconByTraitName('World of Desire&Power'),
                    'color'=> $colorRepository->findColorByWorldName('World of Desire&Power')
                ],
                [
                    'name' => 'World of Seek&Explore',
                    'value' => $analysis->getWorldOfSeekAndExplore(),
                    'icon' => $itemRepository->findIconByTraitName('World of Seek&Explore'),
                    'color'=> $colorRepository->findColorByWorldName('World of Seek&Explore')
                ],
                [
                    'name' => 'World of Career',
                    'value' => $analysis->getWorldOfCareer(),
                    'icon' => $itemRepository->findIconByTraitName('World of Career'),
                    'color'=> $colorRepository->findColorByWorldName('World of Career')
                ],
                [
                    'name' => 'World of Future',
                    'value' => $analysis->getWorldOfFuture(),
                    'icon' => $itemRepository->findIconByTraitName('World of Future'),
                    'color'=> $colorRepository->findColorByWorldName('World of Future')
                ],
                [
                    'name' => 'World of Spirituality',
                    'value' => $analysis->getWorldOfSpirituality(),
                    'icon' => $itemRepository->findIconByTraitName('World of Spirituality'),
                    'color'=> $colorRepository->findColorByWorldName('World of Spirituality')
                ]
            ]
        ];
    }
}
