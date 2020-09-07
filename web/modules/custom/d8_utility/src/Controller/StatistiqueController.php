<?php

namespace Drupal\d8_utility\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
/**
 * Class StatistiqueController.
 ** @package Drupal\d8_utility\Controller
 */ class StatistiqueController extends ControllerBase
{
    /**
     * Hello.
     * @param Request $count
     * @return array Return Hello string.
     * Return Hello string.
     */
    public function callback($type = null, $fulltext = null)
    {


        return [
            '#theme' => 'page_statistique',
            '#data' => [
                'results' => 'kkkkkk',
            ],
            //'#cache' => ['max-age' => 0],
        ];
    }
}
