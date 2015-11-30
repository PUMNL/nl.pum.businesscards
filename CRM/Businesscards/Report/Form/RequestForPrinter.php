<?php

class CRM_Businesscards_Report_Form_RequestForPrinter extends CRM_Report_Form {

  protected $_noFields = TRUE;

  protected $_addressField = FALSE;

  protected $_emailField = FALSE;

  protected $_customGroupExtends = array();
  protected $_customGroupGroupBy = FALSE;

  public function __construct() {
    $config = CRM_Businesscards_Config::singleton();

    $this->_columns = array(
      'civicrm_activity' => array(
        'filters' => array(
          'activity_type_id' => array(
            'title' => ts('Activity Tyoe'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Core_BAO_OptionValue::getOptionValuesAssocArrayFromName('activity_type'),
            'default' => array($config->business_request_activity_type),
          ),
          'status_id' => array(
            'title' => ts('Activity Status'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Core_BAO_OptionValue::getOptionValuesAssocArrayFromName('activity_status'),
            'default' => array($config->default_activity_status),
          ),
        ),
      ),
    );

    parent::__construct();
  }

  public function select() {
    parent::select();

    $config = CRM_Businesscards_Config::singleton();

    $this->_selectClauses[] = "{$this->_aliases['civicrm_activity']}.id";
    $this->_selectClauses[] = "contact.display_name as contact_display_name";

    $this->_selectClauses[] = "address.street_address as address_street_address";
    $this->_selectClauses[] = "address.supplemental_address_1 as address_supplemental_address_1";
    $this->_selectClauses[] = "address.postal_code as address_postal_code";
    $this->_selectClauses[] = "address.city as address_city";
    $this->_selectClauses[] = "country.name as address_country";
    $this->_selectClauses[] = "phone.phone as phone";
    $this->_selectClauses[] = "mobile.phone as mobile";
    $this->_selectClauses[] = "email.email as email";

    $this->_selectClauses[] = "business_card_request.{$config->job_title_custom_field['column_name']} AS business_card_request_job_title";
    $this->_selectClauses[] = "business_card_request.{$config->job_title_additional_custom_field['column_name']} AS business_card_request_job_title_additional";
    $this->_selectClauses[] = "additional_data.{$config->skype_custom_field['column_name']} as skype";
    $this->_selectClauses[] = "additional_data.{$config->twitter_custom_field['column_name']} as twitter";

    $this->_selectClauses[] = "business_card_request.{$config->phone_number_visible_custom_field['column_name']} AS business_card_request_phone_visible";
    $this->_selectClauses[] = "business_card_request.{$config->mobile_number_visible_custom_field['column_name']} AS business_card_request_mobile_visible";
    $this->_selectClauses[] = "business_card_request.{$config->skype_visible_custom_field['column_name']} AS business_card_request_skype_visible";
    $this->_selectClauses[] = "business_card_request.{$config->twitter_visible_custom_field['column_name']} AS business_card_request_twitter_visible";
    $this->_selectClauses[] = "business_card_request.{$config->home_address_visible_custom_field['column_name']} AS business_card_request_home_address_visible";

    $this->_select = "SELECT " . implode(', ', $this->_selectClauses);
  }

  public function from() {
    $config = CRM_Businesscards_Config::singleton();
    $phone_type_id = CRM_Core_OptionGroup::getValue('phone_type', 'Phone', 'name', 'String', 'value');
    $mobile_type_id = CRM_Core_OptionGroup::getValue('phone_type', 'Mobile', 'name', 'String', 'value');

    $this->_from = "FROM `civicrm_activity` {$this->_aliases['civicrm_activity']}";
    $this->_from.=" LEFT JOIN civicrm_activity_contact target ON {$this->_aliases['civicrm_activity']}.id = target.activity_id and target.record_type_id = 3";
    $this->_from.=" LEFT JOIN civicrm_contact contact ON target.contact_id = contact.id";
    $this->_from.=" LEFT JOIN civicrm_address address on address.contact_id = contact.id and is_primary = 1";
    $this->_from.=" LEFT JOIN civicrm_country country ON country.id = address.country_id";
    $this->_from.=" LEFT JOIN {$config->business_card_custom_group['table_name']} business_card_request ON business_card_request.entity_id = {$this->_aliases['civicrm_activity']}.id";
    $this->_from.=" LEFT JOIN civicrm_phone phone on phone.contact_id = contact.id and phone.phone_type_id = '{$phone_type_id}'";
    $this->_from.=" LEFT JOIN civicrm_phone mobile on mobile.contact_id = contact.id and mobile.phone_type_id = '{$mobile_type_id}'";
    $this->_from.=" LEFT JOIN civicrm_email email ON email.contact_id = contact.id and email.is_primary = 1";
    $this->_from.=" LEFT JOIN {$config->additional_data_custom_group['table_name']} additional_data ON additional_data.entity_id = contact.id";
  }

  public function where() {
    parent::where();
    if (!strlen($this->_where)) {
      $this->_where = " WHERE 1 ";
    }
    $this->_where .= " AND {$this->_aliases['civicrm_activity']}.is_test = 0 AND
                                {$this->_aliases['civicrm_activity']}.is_deleted = 0 AND
                                {$this->_aliases['civicrm_activity']}.is_current_revision = 1";
  }

  public function modifyColumnHeaders() {
    parent::modifyColumnHeaders(); // TODO: Change the autogenerated stub
    $this->_columnHeaders['contact_display_name'] = array('title' => '01_Naam');
    $this->_columnHeaders['business_card_request_job_title'] = array('title' => '02_Functie');
    $this->_columnHeaders['address_street_address'] = array('title' => '03_Adres');
    $this->_columnHeaders['mobile'] = array('title' => '04_Contactgegevensveld1');
    $this->_columnHeaders['05_Contactgegevensveld2'] = array('title' => '05_Contactgegevensveld2');
    $this->_columnHeaders['email'] = array('title' => '06_Contactgegevensveld3');
    $this->_columnHeaders['phone'] = array('title' => '07_Contactgegevensveld4');
    $this->_columnHeaders['08_Contactgegevensveld5'] = array('title' => '08_Contactgegevensveld5');
    $this->_columnHeaders['09_PrivateAddress'] = array('title' => '09_PrivateAddress');
    $this->_columnHeaders['10_Priveadres'] = array('title' => '10_Priveadres');
    $this->_columnHeaders['skype'] = array('title' => '11_Privecontactveld1');
    $this->_columnHeaders['12_Privecontactveld2'] = array('title' => '12_Privecontactveld2');
    $this->_columnHeaders['13_Privecontactveld3'] = array('title' => '13_Privecontactveld3');
    $this->_columnHeaders['14_Privecontactveld4'] = array('title' => '14_Privecontactveld4');
    $this->_columnHeaders['15_Privecontactveld5'] = array('title' => '15_Privecontactveld5');

    $this->_columnHeaders['twitter'] = array('title' => 'twitter');

    $this->_columnHeaders['address_supplemental_address_1'] = array('no_display' => true);
    $this->_columnHeaders['address_postal_code'] = array('no_display' => true);
    $this->_columnHeaders['address_city'] = array('no_display' => true);
    $this->_columnHeaders['address_country'] = array('no_display' => true);

    $this->_columnHeaders['business_card_request_job_title_additional'] = array('no_display' => true);

    $this->_columnHeaders['business_card_request_phone_visible'] = array('no_display' => true);
    $this->_columnHeaders['business_card_request_mobile_visible'] = array('no_display' => true);
    $this->_columnHeaders['business_card_request_skype_visible'] = array('no_display' => true);
    $this->_columnHeaders['business_card_request_twitter_visible'] = array('no_display' => true);
    $this->_columnHeaders['business_card_request_home_address_visible'] = array('no_display' => true);
  }

  public function alterDisplay(&$rows) {
    foreach($rows as $index => $row) {
      if (!empty($row['address_supplemental_address_1'])) {
        $rows[$index]['address_street_address'] .= $row['address_supplemental_address_1'] . '/[b]';
      } else {
        $rows[$index]['address_street_address'] .= '/[b]';
      }
      if (!empty($row['address_postal_code'])) {
        $rows[$index]['address_street_address'] .= $row['address_postal_code'];
      }
      if (!empty($row['address_city'])) {
        $rows[$index]['address_street_address'] .= $row['address_city'];
      }
      if (!empty($row['address_postal_code']) || !empty($row['address_city'])) {
        $rows[$index]['address_street_address'] .= '/[b]';
      }
      if (!empty($row['address_country'])) {
        $row[$index]['address_street_address'] .= $row['address_country'];
      }

      if (!empty($row['business_card_request_job_title_additional'])) {
        $rows[$index]['business_card_request_job_title'] .= " ".$row['business_card_request_job_title_additional'];
      }

      if (!empty($row['skype'])) {
        $rows[$index]['skype'] = "Skype: ".$row['skype'];
      }

      if (!empty($row['twitter'])) {
        $rows[$index]['twitter'] = "Twitter: ".$row['twitter'];
      }

      if (empty($row['business_card_request_phone_visible'])) {
        $rows[$index]['phone'] = '';
      }
      if (empty($row['business_card_request_mobile_visible'])) {
        $rows[$index]['mobile'] = '';
      }
      if (empty($row['business_card_request_skype_visible'])) {
        $rows[$index]['skype'] = '';
      }
      if (empty($row['business_card_request_twitter_visible'])) {
        $rows[$index]['twitter'] = '';
      }
      if (empty($row['business_card_request_home_address_visible'])) {
        $rows[$index]['address_street_address'] = '';
      }

      if (!empty($rows[$index]['address_street_address'])) {
        $rows[$index]['09_PrivateAddress'] = 'Private address';
      }
    }
  }

}