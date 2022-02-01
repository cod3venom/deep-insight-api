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

/**
 * [
 *   "The Hidden Driver": {
      * "Life Path": {
 *       "icon": "lifePath.svg",
 *       "value": 15%,
 *       "type": "percent"
 *    }
 *  }
 *
 * ]
 */
class SchemaBuilder
{
   #[Pure] public function buildFromObject(TraitAnalysis $analysis, TraitItemRepository $itemRepository): array
    {
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
                       'icon' => $itemRepository->findIconByTraitName('The driving force')
                  ],
                  [
                      'name' => 'The Moral Code',
                      'value'=> $analysis->getTheMoralCode(),
                      'type' => 'int',
                      'icon' => $itemRepository->findIconByTraitName('The driving force')
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
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'Behaviors & Needs',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'Seeks & Mindset',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'React & Motive to Action',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => ' Joins & Desire',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
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
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'Expression',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'Keyword',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
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
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'Auditory | Hear it | Thinking',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'Kinesteric | Do it | Sensation',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' =>  $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'Emotive | Feel it | Feeling',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
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
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'Stabilizing & synthesis',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'Finishing & thesis',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
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
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'Thinker - Order',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'Water - Peace',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'Talker - Fun',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
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
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'Belief',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'Communication',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'Style',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'Strength',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'Tactic',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'Objective',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
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
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'World of Matter',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'World of Feeling',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'World of Fun',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'World of Usability',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'World of Relations',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'World of Desire&Power',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'World of Seek&Explore',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'World of Career',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'World of Future',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
                    ],
                    [
                        'name' => 'World of Spirituality',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => $itemRepository->findIconByTraitName('The driving force')
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
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'P2M',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'P3MY',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'P4W',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'P5M',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'P6J',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'P7S',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'P8U',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'P9N',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'P10N',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'PTNde',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                ]
            ]
        ];
    }
}
