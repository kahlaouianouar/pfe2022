<?php

namespace Drupal\d8_utility\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

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
    public function submitForm(array &$form, FormStateInterface $form_state) {die(ok);
        parent::submitForm($form, $form_state);
    }
}
