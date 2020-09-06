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
        $languageId = \Drupal::languageManager()->getCurrentLanguage()->getId();

        $view_results = views_embed_view('global_search', 'block_after_filter', $type, $fulltext );
        $config = \Drupal::config('d8_utility.settings');
        $image = $config->get('page_image_'.$languageId);
        $title = $config->get('page_title_'.$languageId);
        $description = $config->get('page_description.value_'.$languageId);
        $description_format = $config->get('page_description.format_'.$languageId);
        $uri = $url = "";

        if(!empty($image) && isset($image[0])){
            $file_id = $image[0];
            if((int) $file_id > 0){
                $file = \Drupal\file\Entity\File::load($file_id);
                if(!empty($file)){
                    $uri = $file->getFileUri();
                    //$url = \Drupal\Core\Url::fromUri(file_create_url($uri))->toString();
                }
            }
        }

        return [
            '#theme' => 'd8_utility_results',
            '#data' => ['results' => $view_results,
                'title'=>$title,
                'description_format'=>$description_format,
                'field_introduction'=>$description,
                'field_image_uri'=>$uri
            ],
            //'#cache' => ['max-age' => 0],
        ];
    }
}
