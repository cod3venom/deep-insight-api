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
   #[Pure] public function buildFromObject(TraitAnalysis $analysis): array
    {
        return [
           [
              'category'=> 'The hidden drive',
              'items' => [
                   [
                       'name' => 'Life path',
                       'value'=> $analysis->getLifePath(),
                       'type' => 'int',
                       'icon' => 'lifePath.svg'
                  ],
                  [
                      'name' => 'The driving force',
                      'value'=> $analysis->getTheDrivingForce(),
                      'type' => 'int',
                      'icon' => 'theDrivingForce.svg'
                  ],
                   [
                       'name' => 'The Matrix of Excellence',
                       'value'=> $analysis->getTheMatrixOfExellence(),
                       'type' => 'int',
                       'icon' => 'theMatrixOfExcellence.svg'
                  ],
                  [
                      'name' => 'The Moral Code',
                      'value'=> $analysis->getTheMoralCode(),
                      'type' => 'int',
                      'icon' => 'theMoralCode.svg'
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
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'Behaviors & Needs',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'Seeks & Mindset',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'React & Motive to Action',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => ' Joins & Desire',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
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
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'Expression',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
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
                        'icon' => 'lifePath.svg'
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
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'Emotive | Feel it | Feeling',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
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
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'Stabilizing & synthesis',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'Finishing & thesis',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
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
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'Thinker - Order',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'Water - Peace',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'Talker - Fun',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
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
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'Belief',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'Communication',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'Style',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'Strength',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'Tactic',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'Objective',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
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
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'World of Matter',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'World of Feeling',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'World of Fun',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'World of Usability',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'World of Relations',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'World of Desire&Power',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'World of Seek&Explore',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'World of Career',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'World of Future',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
                    ],
                    [
                        'name' => 'World of Spirituality',
                        'value'=> $analysis->getLifePath(),
                        'type' => 'int',
                        'icon' => 'lifePath.svg'
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