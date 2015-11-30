<?php

/**
 * Class CRM_Businesscards_Config holds all configuration for this extension
 * The configuration exists of custom field names and activity type
 *
 * Singelton pattern
 */
class CRM_Businesscards_Config {

  private static $singleton;

  public $business_request_activity_type;

  public $default_activity_status;

  public $business_card_custom_group;

  public $job_title_custom_field;

  public $job_title_additional_custom_field;

  public $additional_data_custom_group;

  public $skype_custom_field;

  public $twitter_custom_field;

  public $phone_number_visible_custom_field;
  public $mobile_number_visible_custom_field;
  public $skype_visible_custom_field;
  public $twitter_visible_custom_field;
  public $home_address_visible_custom_field;

  private function __construct() {
    $this->business_request_activity_type = civicrm_api3('OptionValue', 'getvalue', array('return' => 'value', 'name' => 'Business card request', 'option_group_id' => 2));
    $activity_status = civicrm_api3('OptionGroup', 'getvalue', array('return' => 'id', 'name' => 'activity_status'));
    $this->default_activity_status = civicrm_api3('OptionValue', 'getvalue', array('return' => 'value', 'name' => 'scheduled', 'option_group_id' => $activity_status));

    $this->business_card_custom_group = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'business_card_request'));
    $this->job_title_custom_field = civicrm_api3('CustomField', 'getsingle', array('name' => 'job_title', 'custom_group_id' => $this->business_card_custom_group['id']));
    $this->job_title_additional_custom_field = civicrm_api3('CustomField', 'getsingle', array('name' => 'job_title_additional', 'custom_group_id' => $this->business_card_custom_group['id']));

    $this->phone_number_visible_custom_field = civicrm_api3('CustomField', 'getsingle', array('name' => 'phone_number_visible', 'custom_group_id' => $this->business_card_custom_group['id']));
    $this->mobile_number_visible_custom_field = civicrm_api3('CustomField', 'getsingle', array('name' => 'mobile_number_visible', 'custom_group_id' => $this->business_card_custom_group['id']));
    $this->skype_visible_custom_field = civicrm_api3('CustomField', 'getsingle', array('name' => 'skype_visible', 'custom_group_id' => $this->business_card_custom_group['id']));
    $this->twitter_visible_custom_field = civicrm_api3('CustomField', 'getsingle', array('name' => 'twitter_visible', 'custom_group_id' => $this->business_card_custom_group['id']));
    $this->home_address_visible_custom_field = civicrm_api3('CustomField', 'getsingle', array('name' => 'home_address_visible', 'custom_group_id' => $this->business_card_custom_group['id']));

    $this->additional_data_custom_group = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'Additional_Data'));
    $this->skype_custom_field = civicrm_api3('CustomField', 'getsingle', array('name' => 'Skype_Name', 'custom_group_id' => $this->additional_data_custom_group['id']));
    $this->twitter_custom_field = civicrm_api3('CustomField', 'getsingle', array('name' => 'Twitter_account_name', 'custom_group_id' => $this->additional_data_custom_group['id']));
  }

  /**
   * @return \CRM_Businesscards_Config
   */
  public static function singleton() {
    if (!self::$singleton) {
      self::$singleton = new CRM_Businesscards_Config();
    }
    return self::$singleton;
  }

}