<?php

namespace Drupal\d8_utility\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * annuaire annonces config settings.
 */
class RemplirQuestionnaire extends ConfigFormBase {
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'remplirquestionnaire_settings';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() {
        return [
            'remplirquestionnaire.settings',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, WorkflowInterface $workflow = NULL, $id = NULL) {
      if (isset($id) && !empty($id)){
        $node = Node::load($id);
        if ($node && $node->getType() == 'sondage_satisfaction'){

          $form['description'] = array(
          '#type' => 'markup',
          '#markup' => '<p>Cher/chère étudiant/e,

Merci de consacrer quelques minutes à remplir ce court questionnaire. Les résultats du sondage nous aideront à éclaircir ce qu\'il faut améliorer à l\'avenir.
</p>',
        );
          $questions = $node->field_questions->getValue();
          $i=0;
          foreach($questions as $question){
            $i++;
            $form['position_'.$i] = array(
              '#type' => 'fieldset',
              '#title' => '<h2>'.$i.'</h2>'
            );
            $form['position_'.$i]['question_'.$i] = array(
              '#type' => 'markup',
              '#markup' => '<p>'.$question["value"].'</p>',
            );
            $form['question_'.$i] = array(
              '#type' => 'hidden',
              '#value' => $question["value"]
            );
            $form['position_'.$i]['reponse_'.$i] = array(
              '#type' => 'radios',
              '#title' => 'Réponse*',
              '#required' => TRUE,
              '#options' => array(
                0 => $this
                  ->t('Non'),
                1 => $this
                  ->t('Oui'),
              ),
            );
          }
        }
      }else{
        $form['description'] = array(
          '#type' => 'markup',
          '#markup' => "<p>ID incorrect, merci de contacter l'administration</p>");
      }

        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
      $uid = \Drupal::currentUser()->id();
      $questions = $form_state->getValue('date');
      $request = \Drupal::request();
      $current_path = $request->getPathInfo();
      $path_args = explode('/', $current_path);
      $idQuestionnaire = $path_args[2];
      $quesionnaire = Node::load($idQuestionnaire);
      $nbrQ = $quesionnaire->field_questions->getValue();
      $nbrQ = count($nbrQ);
      
            $content=array();
            $content['type']            = 'reponses';      
            $content['title']           = "Réponses pour le questionnaire ".$quesionnaire->getTitle();
            $content['created']         = REQUEST_TIME;
            $content['changed']         = REQUEST_TIME;
            $content['uid']         = $uid;
            $content['field_sondage']   = $idQuestionnaire;

            for ($i = 1; $i <= $nbrQ; $i++) {
              $paragraph = Paragraph::create([
                'type' => 'question',   // paragraph type machine name
                'field_saisir_la_question' => [   // paragraph's field machine name
                    'value' => $_POST['question_'.$i],                  // body field value
                    'format' => 'full_html',         // body text format
                ],
                'field_reponse' => $_POST['reponse_'.$i]
              ]);

              $paragraph->save();

              $content['field_pg_questions'][$i] = array(
                'target_id' => $paragraph->id(),
                'target_revision_id' => $paragraph->getRevisionId()
                );
            } 
            $this->createChaine($content);
      
      // set relative internal path
      $dest_url = "/liste-sandages?msg=success";
      $response = new RedirectResponse($dest_url);
      $response->send();
      return;

    }
    public function createChaine($content){
        $node = Node::create($content);
        $node->save();
        return $node->id();
    }
}
