<?php

namespace Drupal\d8_utility\Plugin\Block;

use Drupal\Core\Block\BlockBase;
//use Drupal\lms_core\Controller\ControllerManager;
use Drupal\Core\Form\FormState;
use Drupal\views\views;

/**
 * Provides a 'Bloc page top' Block.
 *
 * @Block(
 *   id = "bloc_page_top",
 *   admin_label = @Translation("Bloc page top"),
 *   category = @Translation("IAE"),
 * )
 */

class PageTopBlock extends BlockBase
{

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $data = $menu = array();
        $languageId = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $data['formSearch'] = \Drupal::formBuilder()->getForm('Drupal\search\Form\SearchBlockForm');

        $view = Views::getView('global_search');
        $view->setDisplay('block_global_search_master');
        $view->initHandlers();
        $form_state = (new \Drupal\Core\Form\FormState())
            ->setStorage([
                'view' => $view,
                'display' => &$view->display_handler->display,
                'rerender' => TRUE,
            ])
            ->setMethod('get')
//            ->setAlwaysProcess()
            ->disableRedirect();
        $form_state->set('rerender', NULL);
        $form = \Drupal::formBuilder()
            ->buildForm('\Drupal\views\Form\ViewsExposedForm', $form_state);
        $form['#attributes']['class'][] = "search_header_top";
        $form['type_1']['#attributes'] = ['style'=>'display:none'];
        $nid_search_page = \Drupal::config('d8_utility.globalurls_settings')->get('url_global_search_'.$languageId);
        $alias = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$nid_search_page);
        $form['#action'] = "/".$languageId.$alias;
        $data['formSearch'] = $form;

        return [
            '#theme' => 'block_page_top',
            '#data' => $data,
            //'#cache' => ['max-age' => 0],
        ];
    }
}