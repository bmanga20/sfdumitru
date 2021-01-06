<?php

/**
 * @version     $Id: mailchimp.php 19014 2012-11-28 04:48:56Z anhnt $
 * @package     JSNUniform
 * @subpackage  Plugin
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2016 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.plugin.plugin');
require_once 'class/Mailchimp.php';

//require_once 'director.php';
class plgUniformMailchimp extends JPlugin {

    public $mailchimp;
    public $listID = array();

    /**
     * Constructor
     *
     * @param   object  &$subject  The object to observe
     *
     * @param   array   $config    An array that holds the plugin configuration
     */
    public function __construct(&$subject, $config) {
        parent::__construct($subject, $config);
        JPlugin::loadLanguage('plg_uniform_mailchimp', JPATH_PLUGINS);
    }

    // load plugin type layout
    public function mailchimp() {
        $model = new JSNUniformModelForm();
        $data = $model->getItem();
        $data = json_decode($data->form_settings);
        $mailchimp = $data->form_mailchimp;
        $path = str_replace('administrator', '', JPATH_BASE) . '/plugins/uniform/mailchimp/layout/mailchimp.php';
        if (file_exists($path)) {
            ob_start();
            include $path;
            $return = ob_get_contents();
            ob_end_clean();
            return $return;
        }
    }

    public function saveBackEnd($data) {
        $this->saveFieldToList($data);
    }

    public function saveFrontEnd($data) {

        $this->saveInfoToMailchimp($data);
    }

    /**
     * Check API key Mailchimp
     *
     * @param   string  &$str  API Key string
     *
     * @return msg
     */
    // grab an API Key from http://admin.mailchimp.com/account/api/
    public function checkApiKey($str) {
        $key = (isset($str['key']) && !empty($str['key']) ? $str['key'] : '');
        $mailchimp = new Mailchimp($key, array('ssl_verifypeer' => false));
        $mergeVars = array('EMAIL' => 'support@joomlashine.com');
        $list_id = '';
        $result = $mailchimp->lists->subscribe($list_id, array('email' => 'support@joomlashine.com'), $mergeVars, false, true, false, false);
        return $result;
    }

    /**
     * Show All List Mailchimp
     *
     * @param   string  &$str  API Key string
     * mergeVars($id)
     * @return msg
     */
    public function showListMailchimp($str) {
        $key = (isset($str['key']) && !empty($str['key']) ? $str['key'] : '');
        $mailchimp = new Mailchimp($key, array('ssl_verifypeer' => false));
        $result = $mailchimp->lists->getList(array(), 0, 100, 'created', 'DESC');
        return $result;
    }

    /**
     * Save form field to List Mailchimp
     *
     * @param   array  &$arr field array to save
     *
     * @return void
     */
    public function saveFieldToList($str) {
        if (!empty($str)) {
            $arr = json_decode($str);
        }
        if (is_object($arr)) {
            $arr = (array) $arr;
            $arrfield = (array) $arr['arrfield'];
            $lisId = (array) $arr['arrfield'];
            $mailchimp = new Mailchimp($arr['keyApi'], array('ssl_verifypeer' => false));
            foreach ($arrfield as $k => $arrVl) {
                $arrVl = (array) json_decode($arrVl);
                $arrField = (array) $arrVl['Field'];
                $newField = (array) $arrField['new'];
                $tagField = (array) $arrField['tag'];
                if (is_array($newField)) {
                    foreach ($newField as $key => $field) {
                        if (array_search($field, $tagField)) {
                            $tag = array_search($field, $tagField);
                            $option = array('name' => $tagField[$tag], 'req' => false, 'public' => true);
                            $mailchimp->lists->mergeVarUpdate($k, $tag, $option);
                        } else {
                            $fields = str_replace(" ", '', $field);
                            $fields = preg_replace('/[^A-Za-z0-9\-]/', '', $fields);
                            $fields = 'J' . substr($fields, 0, 9);
                            $option = array('field_type' => 'text', 'req' => false, 'public' => true);
                            $mailchimp->lists->mergeVarAdd($k, strtoupper($fields), $field, $option);
                        }
                    }
                }
            }
        }
    }

    /*
     * Show list field in form and Mailchimp
     * @param array @response
     * @param string @id
     * Return html
     */

    public function listAllFieldInListOnMailchimp($arr) {
        $key = (isset($arr['key']) && !empty($arr['key']) ? $arr['key'] : '');
        $listId = (isset($arr['listId']) && !empty($arr['listId']) ? $arr['listId'] : '');
        $mailchimp = new Mailchimp($key, array('ssl_verifypeer' => false));
        $Arrmerge = $mailchimp->lists->mergeVars(array($listId));
        $arr = array();
        foreach ($Arrmerge['data'] as $arrVar) {
            $arr = $arrVar['merge_vars'];
        }
        return $arr;
    }

    /**
     * Save info form to Mailchimp server
     *
     * @param   array  &$arrField field array to save
     * @param   array  &$post info array from submit form
     * @return void
     */
    public function saveInfoToMailchimp($data) {
        $arrField = $data['mailchimp'];
        if (isset($arrField) && !empty($arrField))
        {
            $arrField = json_decode($arrField, true);
            $useMailChimp = ($arrField['useMailchimp'] == 1)? 1 :'';
            $apiKey = $arrField['keyApi'];
            $arrFieldChild = (array) $arrField['arrfield'];
            $mailChimp = new Mailchimp($apiKey, array('ssl_verifypeer' => false));

            //$data['dataContentEmail'] is an array has been processed, it lose some information.
            //I supplemented the deficit from $data['post']
            //$data['post'] array struct:
            //{
            //"8":"star@gmail.com",
            //"name":{
            //  "10":{
            //      "title":"Master",
            //      "first":"Rhona Ross",
            //      "suffix":"Gretchen Decker"
            ////    "last":"Rogan Rose"
            //      }
            foreach ($data['post'] AS $gKey => $gVal)
            {
                foreach ($gVal AS $gChild)
                {
                    foreach ($gChild AS $kField => $vField)
                    {
                        if (!isset($data['dataContentEmail'][$kField]))
                        {
                            $data['dataContentEmail'][$kField] = $vField;
//                            if ($gKey == 'name')
//                            {
//                                if ($kField == 'first')
//                                {
//                                    $data['dataContentEmail']['FNAME'] = $vField;
//                                }
//                                if ($kField == 'suffix')
//                                {
//                                    $data['dataContentEmail']['MNAME'] = $vField;
//                                }
//                                if ($kField == 'last')
//                                {
//                                    $data['dataContentEmail']['LNAME'] = $vField;
//                                }
//                            }
                        }
                    }
                }
            }
            if ( isset( $useMailChimp ) && !empty( $useMailChimp ) )
            {
                foreach ($arrFieldChild as $k => $arrVl)
                {
                    $mergeVars = array();
                    if ($arrVl != '')
                    {
                        $arrVl = json_decode($arrVl, true);
                        $allow = $arrVl['allow'];
                        if ($allow == 1)
                        {
                            $identify = (array) $arrVl['Field']['identify'];
                            $tag = (array) $arrVl['Field']['tag'];
                            foreach ($identify AS $key => $value)
                            {
                                //Detect custom field
                                if (!in_array($value, $tag))
                                {
                                    $value = 'J'.strtoupper(preg_replace('/(\s|[^A-Za-z0-9\-])+/', '', $value));
                                    if (strlen($value) > 10)
                                    {
                                        $value = substr($value, 0, 10);
                                    }
                                }

                                if($key == 'middle')
                                {
                                    $key = 'suffix';
                                }

                                $mergeVars[$value] = $data['dataContentEmail'][$key];
                            }
                            if (isset($mergeVars) && !empty($mergeVars))
                            {
                                $mailChimp->lists->subscribe($k, array('email' => $mergeVars['EMAIL']), $mergeVars, false, false, false, false);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Validate tag to save on Mailchimp server
     *
     * @param   string  &$oldField value of field to save
     * @param   array  &$tagField
     * @return string
     */
    public function checkTagMergeVar($apiKey,$listId,$tag){
        $mailchimp = new Mailchimp($apiKey, array('ssl_verifypeer' => false));
        $Arrmerge = $mailchimp->lists->mergeVars(array($listId));
        $arr = array();
        foreach ($Arrmerge['data'] as $arrVar) {
            $arr = $arrVar['merge_vars'];
        }
        $check =0;
        if(isset($arr) && !empty($arr)){
            foreach ($arr as $ar){
                if($ar['tag'] == $tag){
                    $check = 1;
                }
            }
        }
        return $check;
    }
    /**
     * Validate tag to save on Mailchimp server
     *
     * @param   string  &$oldField value of field to save
     * @param   array  &$tagField
     * @return string
     */
    public function func($oldField, $tagField) {
        if (array_search($oldField, $tagField)) {
            $tag = array_search($oldField, $tagField);
        } else {
            if($oldField != 'FNAME' && $oldField != 'LNAME' ){
                $tag = 'J' . substr(str_replace(" ", '', $oldField), 0, 9);
            }else{$tag = substr(str_replace(" ", '', $oldField), 0, 9);}
            $tag = strtoupper($tag);
        }
        return $tag;
    }

}
