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
use App\Repository\TraitItemRepository;
use JetBrains\PhpStorm\Pure;

class SchemaBuilder
{
   public function buildFromObject(TraitAnalysis $analysis, TraitItemRepository $itemRepository): array
    {
        if (is_null($analysis->getBirthDay())) {
            return [];
        }
        return [
           [
              'category'=> 'The hidden drive',
              'items' => [
                   [
                       'name' => 'Life path',
                       'value'=> $analysis->getLifePath(),
                       'type' => 'int',
                       'icon' =>  $itemRepository->findIconByTraitName('Life Path')
                  ],
                  [
                      'name' => 'The driving force',
                      'value'=> $analysis->getTheDrivingForce(),
                      'type' => 'int',
                      'icon' => $itemRepository->findIconByTraitName('The driving force')
                  ],
                   [
                       'name' => 'The Matrix of Excellence',
                       'value'=> $analysis->getTheMatrixOfExellence(),
                       'type' => 'int',
                       'icon' => $itemRepository->findIconByTraitName('The Matrix of Excellence')
                  ],
                  [
                      'name' => 'The Moral Code',
                      'value'=> $analysis->getTheMoralCode(),
                      'type' => 'int',
                      'icon' => $itemRepository->findIconByTraitName('The Moral Code')
                  ],
              ]
          ],
            [
                'category' => 'The hidden Determinations',
                'items' =>  [
                    [
                        'name' => 'Goals & Wants',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('Goals & Wants')
                    ],
                    [
                        'name' => 'Behaviors & Needs',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('Behaviors & Needs')
                    ],
                    [
                        'name' => 'Seeks & Mindset',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('Seeks & Mindset')
                    ],
                    [
                        'name' => 'React & Motive to Action',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('React & Motive to Action')
                    ],
                    [
                        'name' => 'Joins & Desire',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('Joins & Desire')
                    ]
                ]
            ],
            [
                'category' => 'The hidden Factors',
                'items' =>  [
                    [
                        'name' => 'Polarisation',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('Polarisation')
                    ],
                    [
                        'name' => 'Expression',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('Expression')
                    ],
                    [
                        'name' => 'Keyword',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('Keyword')
                    ],
                ]
            ],
            [
                'category' => 'The hidden Memorizing (Learning | Sense | Perception)',
                'items' =>  [
                    [
                        'name' => 'Visual | See it | Intuition',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('Visual | See it | Intuition')
                    ],
                    [
                        'name' => 'Auditory | Hear it | Thinking',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('Auditory | Hear it | Thinking')
                    ],
                    [
                        'name' => 'Kinesteric | Do it | Sensation',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' =>  $itemRepository->findIconByTraitName('Kinesteric | Do it | Sensation')
                    ],
                    [
                        'name' => 'Emotive | Feel it | Feeling',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('Emotive | Feel it | Feeling')
                    ],
                ]
            ],
            [
                'category' => 'Stage in the process',
                'items' =>  [
                    [
                        'name' => 'Initializing & antithesis',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('Initializing & antithesis')
                    ],
                    [
                        'name' => 'Stabilizing & synthesis',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('Stabilizing & synthesis')
                    ],
                    [
                        'name' => 'Finishing & thesis',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('Finishing & thesis')
                    ],
                ]
            ],
            [
                'category' => 'Temperament',
                'items' =>  [
                    [
                        'name' => 'Doer - Control',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('Temperament')
                    ],
                    [
                        'name' => 'Thinker - Order',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('Thinker - Order')
                    ],
                    [
                        'name' => 'Water - Peace',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('Water - Peace')
                    ],
                    [
                        'name' => 'Talker - Fun',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('Talker - Fun')
                    ],
                ]
            ],
            [
                'category' => 'The hidden Spheres of Life',
                'items' =>  [
                    [
                        'name' => 'The Value of',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The hidden Spheres of Life')
                    ],
                    [
                        'name' => 'Belief',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('Belief')
                    ],
                    [
                        'name' => 'Communication',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('Communication')
                    ],
                    [
                        'name' => 'Style',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('Style')
                    ],
                    [
                        'name' => 'Strength',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('Strength')
                    ],
                    [
                        'name' => 'Tactic',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('Tactic')
                    ],
                    [
                        'name' => 'Objective',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('Objective')
                    ],
                ]
            ],
            [
                'category' => 'Worlds of',
                'items' =>  [
                    [
                        'name' => 'World of Action',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('Worlds of')
                    ],
                    [
                        'name' => 'World of Matter',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('World of Matter')
                    ],
                    [
                        'name' => 'World of Feeling',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('World of Feeling')
                    ],
                    [
                        'name' => 'World of Fun',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('World of Fun')
                    ],
                    [
                        'name' => 'World of Usability',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('World of Usability')
                    ],
                    [
                        'name' => 'World of Relations',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('World of Relations')
                    ],
                    [
                        'name' => 'World of Desire&Power',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('World of Desire&Power')
                    ],
                    [
                        'name' => 'World of Seek&Explore',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('World of Seek&Explore')
                    ],
                    [
                        'name' => 'World of Career',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('World of Career')
                    ],
                    [
                        'name' => 'World of Future',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('World of Future')
                    ],
                    [
                        'name' => 'World of Spirituality',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('World of Spirituality')
                    ],
                ]
            ],
            [
                'category' => 'Details',
                'items' =>  [
                    [
                        'name' => 'P1S',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('P1S')
                    ],
                    [
                        'name' => 'P2M',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('P2M')
                    ],
                    [
                        'name' => 'P3MY',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('P3MY')
                    ],
                    [
                        'name' => 'P4W',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('P4W')
                    ],
                    [
                        'name' => 'P5M',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('P5M')
                    ],
                    [
                        'name' => 'P6J',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('P6J')
                    ],
                    [
                        'name' => 'P7S',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('P7S')
                    ],
                    [
                        'name' => 'P8U',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('P8U')
                    ],
                    [
                        'name' => 'P9N',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('P9N')
                    ],
                    [
                        'name' => 'P10N',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('P10N')
                    ],
                    [
                        'name' => 'PTNde',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('PTNde')
                    ],
                ]
            ]
        ];
    }
}
