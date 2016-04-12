<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Utilityscripts_Form_UtilityScripts extends CRM_Core_Form {
  function buildQuickForm() {

    $scripts = getUtilityScripts();
    $scriptNames = array_keys($scripts);
    $scriptNames = array_combine($scriptNames, $scriptNames);

    // add form elements
    $this->add(
      'select', // field type
      'script_name', // field name
      'Script to Run', // field label
      $scriptNames, // list of options
      true // is required
    );
    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => ts('Run Script'),
        'isDefault' => TRUE,
      ),
    ));

    CRM_Core_Resources::singleton()->addStyleFile('com.tobiaslounsbury.utilityscripts', 'utilityScripts.css');

    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  function postProcess() {
    $values = $this->exportValues();
    $scripts = getUtilityScripts();
    if(array_key_exists($values['script_name'], $scripts) && is_callable($scripts[$values['script_name']])) {
      ob_Start();
      $scripts[$values['script_name']]();
      $results = ob_get_clean();
      $this->assign('results', $results);
    } else {
      CRM_Core_Session::setStatus("Unknown script requested: `".$values['script_name']."`", "error", "error");
    }
    parent::postProcess();
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }
}
