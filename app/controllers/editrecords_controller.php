<?php

/**
 * This is the admin competitor class that will handle the competitor names
  @author Sanchali Bishnoi
 */
class EditrecordsController extends AppController {
    #Controller Class Name

    var $name = 'Editrecords';
    # Array of helpers used in this controller.
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Form', 'Custom');
    # Array of components used in this controller.
    var $components = array('utility', 'Session', 'Cookie');

    # function showing grid of records

    function showlist($action = 'competitornames', $reset = "") {
        # Include Layout
        $this->layout = 'admin';
        $this->isAdmin(); //Check weather current user is admin or not.
        # Setting current url to come from edit mode.
        $this->Cookie->write('backUrl', SITE_URL . '/' . $this->params['url']['url']);

        # Name for model and object.
        $view_name = strtolower(substr($action, 0, strlen($action) - 1));
        $modelname = ucfirst($view_name);
        #importing a model dynamically

        if (App::import('Model', $modelname)) {
            # Create model's dynamic object.
            $this->$modelname = new $modelname();
        } else {
            print "Class name '" . $modelname . "' not exists.";
            die;
        }

        # Import Productfamilyname model
        App::import('Model', 'Productfamilyname');
        # Create Productfamilyname model object
        $this->Productfamilyname = new Productfamilyname();
        # Set Who Product Family Names Array
        $this->set('arrProductFamilyNames', $this->Productfamilyname->getProductFamilyNames());
        /*
          # Import PilotGroup model
          App::import('Model', 'Pilotgroup');
          # Create Pilotgroup model object
          $this->Pilotgroup = new Pilotgroup();
          # Set Pilotgroup Array
          $arrResult = $this->Pilotgroup->getPilotGroupswithDepartments();

          //
          if($modelname == 'Pilotgroup') {

          foreach ($arrResult as $arr_data){
          $arr_data['Pilotgroup']['id'] = $arr_data['pg']['id'];
          $arr_data['Pilotgroup']['name'] = $arr_data['pg']['name'];
          $arr_data['Pilotgroup']['role'] = $arr_data['pg']['role'];
          $arr_data['Pilotgroup']['isactive'] = $arr_data['pg']['isactive'];
          $arr_data['Pilotgroup']['password'] = $arr_data['pg']['password'];
          $arr_data['Pilotgroup']['emailaddress'] = $arr_data['pg']['emailaddress'];
          $arr_data['Pilotgroup']['department_id'] = $arr_data['pg']['department_id'];
          $arr_data['Pilotgroup']['department_name'] = $arr_data['dept']['department_name'];
          $result[] = $arr_data;
          }
          }
          print_r($result);die; */
        $conditionsArr = array(); // Condition array for pagination
        # Check whether the form for search has been submitted.
        if (isset($this->data)) {
            $searchMode = false;
            switch ($modelname) {
                case "Firm":
                    if (isset($this->data['Firm']['parentid']) && $this->data['Firm']['parentid'] != "") {
                        $conditionsArr = array('Firm.parent_id' => $this->data['Firm']['parentid']);
                    }
                    if (isset($this->data['Firm']['accountnumber'])) {
                        $conditionsArr = array_merge($conditionsArr, array('Firm.account_number like ' => "%" . trim($this->data['Firm']['accountnumber']) . "%"));
                    }
                    if (isset($this->data['Firm']['firmname'])) {
                        $conditionsArr = array_merge($conditionsArr, array('Firm.firm_name like ' => "%" . trim($this->data['Firm']['firmname']) . "%"));
                    }
                    $searchMode = TRUE;
                    break;

                case "Productname":
                    if (isset($this->data['Productname']['productcode']) && $this->data['Productname']['productcode'] != "") {
                        $conditionsArr = array('Productname.product_code like ' => "%" . trim($this->data['Productname']['productcode']) . "%");
                    }
                    if (isset($this->data['Productname']['productname'])) {
                        $conditionsArr = array_merge($conditionsArr, array('Productname.product_name like ' => "%" . trim($this->data['Productname']['productname']) . "%"));
                    }
                    $searchMode = TRUE;
                    break;

                case "Pilotgroup";
                    if (isset($this->data['Pilotgroup']['search_name']) && $this->data['Pilotgroup']['search_name'] != "") {
                        $conditionsArr = array('Pilotgroup.name like ' => "%" . trim($this->data['Pilotgroup']['search_name']) . "%");
                    }
                    $searchMode = TRUE;
                    break;

                case "Competitorname";
                    if (isset($this->data['Competitorname']['search_competitor_name']) && $this->data['Competitorname']['search_competitor_name'] != "") {
                        $conditionsArr = array('Competitorname.competitor_name like ' => "%" . trim($this->data['Competitorname']['search_competitor_name']) . "%");
                    }
                    $searchMode = TRUE;
                    break;

                case "Contenttype";
                    if (isset($this->data['Contenttype']['search_content_type']) && $this->data['Contenttype']['search_content_type'] != "") {
                        $conditionsArr = array('Contenttype.content_type like ' => "%" . trim($this->data['Contenttype']['search_content_type']) . "%");
                    }
                    $searchMode = TRUE;
                    break;
                case "Insighttype";
                    if (isset($this->data['Insighttype']['search_insight_type']) && $this->data['Insighttype']['search_insight_type'] != "") {
                        $conditionsArr = array('Insighttype.insight_type like ' => "%" . trim($this->data['Insighttype']['search_insight_type']) . "%");
                    }
                    $searchMode = TRUE;
                    break;

                case "Insightabout";
                    if (isset($this->data['Insightabout']['search_insight_type']) && $this->data['Insightabout']['search_insight_type'] != "") {
                        $conditionsArr = array('Insightabout.insight_type like ' => "%" . trim($this->data['Insightabout']['search_insight_type']) . "%");
                    }
                    $searchMode = TRUE;
                    break;

                case "Market";
                    if (isset($this->data['Market']['search_market']) && $this->data['Market']['search_market'] != "") {
                        $conditionsArr = array('Market.market like ' => "%" . trim($this->data['Market']['search_market']) . "%");
                    }
                    $searchMode = TRUE;
                    break;

                case "Practicearea";
                    if (isset($this->data['Practicearea']['search_practice_area']) && $this->data['Practicearea']['search_practice_area'] != "") {
                        $conditionsArr = array('Practicearea.practice_area like ' => "%" . trim($this->data['Practicearea']['search_practice_area']) . "%");
                    }
                    $searchMode = TRUE;
                    break;

                case "Productfamilyname";
                    if (isset($this->data['Productfamilyname']['search_family_name']) && $this->data['Productfamilyname']['search_family_name'] != "") {
                        $conditionsArr = array('Productfamilyname.family_name like ' => "%" . trim($this->data['Productfamilyname']['search_family_name']) . "%");
                    }
                    $searchMode = TRUE;
                    break;

                case "Statusinsight";
                    if (isset($this->data['Statusinsight']['search_status']) && $this->data['Statusinsight']['search_status'] != "") {
                        $conditionsArr = array('Statusinsight.status like ' => "%" . trim($this->data['Statusinsight']['search_status']) . "%");
                    }
                    $searchMode = TRUE;
                    break;
                case "Productarea";
                    if (isset($this->data['Productarea']['search_productarea']) && $this->data['Productarea']['search_productarea'] != "") {
                        $conditionsArr = array('Productarea.product_area like ' => "%" . trim($this->data['Productarea']['search_productarea']) . "%");
                    }
                    $searchMode = TRUE;
                    break;
                case "Sellingobstacle";
                    if (isset($this->data['Sellingobstacle']['search_sellingobstacle']) && $this->data['Sellingobstacle']['search_sellingobstacle'] != "") {
                        $conditionsArr = array('Sellingobstacle.selling_obstacles like ' => "%" . trim($this->data['Sellingobstacle']['search_sellingobstacle']) . "%");
                    }
                    $searchMode = TRUE;
                    break;
                case "Departmentname";
                    if (isset($this->data['Departmentname']['search_departmentname']) && $this->data['Departmentname']['search_departmentname'] != "") {
                        $conditionsArr = array('Departmentname.department_name like ' => "%" . trim($this->data['Departmentname']['search_departmentname']) . "%");
                    }
                    $searchMode = TRUE;
                    break;
            }
            /* if($modelname == 'Firm') { //Check if form is submitted for Firm
              if(isset($this->data['Firm']['parent_id']) && $this->data['Firm']['parent_id'] != "") {
              $conditionsArr = array('Firm.parent_id' => $this->data['Firm']['parent_id']);
              }
              if(isset($this->data['Firm']['account_number'])) {
              $conditionsArr = array_merge($conditionsArr, array('Firm.account_number like ' => "%".$this->data['Firm']['account_number']."%"));
              }
              if(isset($this->data['Firm']['firm_name'])) {
              $conditionsArr = array_merge($conditionsArr, array('Firm.firm_name like ' => "%".$this->data['Firm']['firm_name']."%"));
              }
              }elseif($modelname == 'Productname') {
              if(isset($this->data['Productname']['product_code']) && $this->data['Productname']['product_code'] != "") {
              $conditionsArr = array('Productname.product_code like ' => "%".$this->data['Productname']['product_code']."%");
              }
              if(isset($this->data['Productname']['product_name'])) {
              $conditionsArr = array_merge($conditionsArr, array('Productname.product_name like ' => "%".$this->data['Productname']['product_name']."%"));
              }
              }elseif($modelname == 'Pilotgroup') {
              if(isset($this->data['Pilotgroup']['username']) && $this->data['Pilotgroup']['username'] != "") {
              $conditionsArr = array('Pilotgroup.name like ' => "%".$this->data['Pilotgroup']['username']."%");
              }
              }elseif($modelname == 'Competitorname') {
              if(isset($this->data['Competitorname']['competitorname']) && $this->data['Competitorname']['competitorname'] != "") {
              $conditionsArr = array('Competitorname.competitor_name like ' => "%".$this->data['Competitorname']['competitorname']."%");
              }
              }elseif($modelname == 'Contenttype') {
              if(isset($this->data['Contenttype']['contenttype']) && $this->data['Contenttype']['contenttype'] != "") {
              $conditionsArr = array('Contenttype.content_type like ' => "%".$this->data['Contenttype']['contenttype']."%");
              }
              }elseif($modelname == 'Insightabout') {
              if(isset($this->data['Insightabout']['insighttype']) && $this->data['Insightabout']['insighttype'] != "") {
              $conditionsArr = array('Insightabout.insight_type like ' => "%".$this->data['Insightabout']['insighttype']."%");
              }
              }elseif($modelname == 'Market') {
              if(isset($this->data['Market']['marketname']) && $this->data['Market']['marketname'] != "") {
              $conditionsArr = array('Market.market like ' => "%".$this->data['Market']['marketname']."%");
              }
              }
             */
            //print $this->Session->check('searchConditionArr');
            //print "<hr>".$this->Session->read('searchConditionArr')."<br>";
            if ($searchMode == TRUE) {
                $this->Session->write('searchConditionArr', serialize($conditionsArr));
                $this->Session->write('currentModelName', $modelname);
            }
            //print $this->Session->read('searchConditionArr');				
        } else {
            if ($reset == 'showall') {
                $this->Session->delete('searchConditionArr');
            } elseif ($this->Session->check('currentModelName') && $this->Session->read('currentModelName') != $modelname) {
                $this->Session->delete('searchConditionArr');
            }
        }
        // echo $modelname; exit;
        # Set pagination condition array, order and limit
        $this->paginate = array(
            'conditions' => unserialize($this->Session->read('searchConditionArr')),
            'order' => $modelname . '.id',
            'limit' => RECORD_PER_PAGE
        );
        # Retrieving results form paginator.
        if ($modelname == 'Productname')
            $result1 = $this->paginate($modelname);
        else
            $result = $this->paginate($modelname);

        if ($modelname == 'Productname') {

            foreach ($result1 as $arr_data) {
                $arr_data['Productname']['id'] = $arr_data['Productname']['id'];
                $arr_data['Productname']['product_code'] = $arr_data['Productname']['product_code'];
                $arr_data['Productname']['product_name'] = $arr_data['Productname']['product_name'];
                $arr_data['Productname']['isactive'] = $arr_data['Productname']['isactive'];

                $prod_familiy_name_arr = array();
                $prod_familiy_name_arr = $this->Productfamilyname->getProductFamilyInfoById($arr_data['Productname']['product_family_id']);
                $arr_data['Productname']['product_family_id'] = $prod_familiy_name_arr['Productfamilyname']['family_name'];
                $result[] = $arr_data;
            }
        }

        #giving headings on the basis of viewname
        #Sanchali bishnoi.
        if ($modelname == 'Productname') {
            $heading = "Product Name";
        } else if ($modelname == 'Productfamilyname') {
            $heading = "Product Family Name";
        } else if ($modelname == 'Practicearea') {
            $heading = "Practice Area";
        } else if ($modelname == 'Pilotgroup') {
            $heading = "Username";
        } else if ($modelname == 'Market') {
            $heading = "Market";
        } else if ($modelname == 'Insightabout') {
            $heading = "How did this feedback come about";
        } else if ($modelname == 'Firm') {
            $heading = "Firm";
        } else if ($modelname == 'Contenttype') {
            $heading = "Content Type";
        } else if ($modelname == 'Insighttype') {
            $heading = "Insight Type";
        } else if ($modelname == 'Competitorname') {
            $heading = "Competitor Name";
        } else if ($modelname == 'Statusinsight') {
            $heading = "Feedback Status";
        } else if ($modelname == 'Productarea') {
            $heading = "Product Area";
        } else if ($modelname == 'Sellingobstacle') {
            $heading = "Selling Obstacles";
        } else if ($modelname == 'Departmentname') {
            $heading = "Department Name";
        }

        $this->set('result', $result);
        $this->set('view_name', $view_name);
        $this->set('heading', $heading);
    }

    # Set status for the record of content types.

    function savevalues($modeltype = '', $id = '') {
        $this->layout = 'content';
        $this->isAdmin(); //Check weather current user is admin or not.

        if ($modeltype != "") {
            $model = ucfirst($modeltype);
        }
        #Import modeltype(dynamic) model
        App::import('Model', $model);
        # Create Content type model dynamic object.
        $this->$model = new $model();
        # Updating value

        if (isset($this->data)) {
            $this->$model->save($this->data[$model]);
            //die;
        }

        $result = $this->$model->find('first', array('conditions' => array('id' => $id)));
        //$rec_exists = $this->Insight->find('count', array('conditions' => array('competitor_id' => $id)));

        $this->set('result', $result);
        $this->set('modeltype', $modeltype);
    }

    # Set status for the record of content types.

    function setstatus($modeltype, $id, $status, $field = 'isactive') {
        $this->isAdmin(); //Check weather current user is admin or not.
        #Import modeltype(dynamic) model
        $model = ucfirst($modeltype);
        App::import('Model', $model);
        # Create Content type model dynamic object.
        $this->$model = new $model();
        # Updating value
        if ($this->$model->updateAll(array($model . '.' . $field => "'" . $status . "'"), array($model . '.id' => $id))) {
            print TRUE;
        } else {
            print "Not able to change status, contact administrator.";
        }
        die;
    }

    # Saves value to table of model type.

    function savevalue($modeltype, $fieldname, $fieldname2, $id, $value_to_save = "", $status = "") {

        if ($status == 0 && $modeltype == "productfamilyname") {
            App::import('Model', 'Productname');
            $this->Productname = new Productname();
            $productNames = $this->Productname->find('all', array(
                'conditions' => array(
                    'Productname.isactive' => 1,
                    'Productname.product_family_id' => $id
                ),
                'fields' => array('product_code', 'id', 'product_name', '')
            ));

            if (count($productNames) > 0) {
                print 'Product Family Name cannot be deactivated as it is being referenced in one or more Product Names.';
                die;
            }
        }
        #Import modeltype(dynamic) model
        $model = ucfirst($modeltype);
        App::import('Model', $model);
        # Create Content type model dynamic object.
        $this->$model = new $model();
        $value_to_save = trim(str_replace("~~~", "&", str_replace('$$$', "'", str_replace("^^^", '"', str_replace("***", ':', $value_to_save)))));
        //$value_to_save = trim(str_replace("~~~", "&", str_replace('$$$', "'", str_replace("^^^", '"', $value_to_save))));
        # Check for already exists				
        if ($value_to_save != "") {
            $rec_exists1 = $this->$model->find('first', array('conditions' => array($fieldname => $value_to_save)));
            if (isset($rec_exists1[$model]['id']) && $rec_exists1[$model]['id'] != $id) {
                //print '"'.$value_to_save.'" already exists in another record';
                print 'exists';
                die;
            }
        }
        # Updating value
        if (trim($value_to_save) != "") {
            if ($this->$model->updateAll(array($model . '.' . $fieldname => "'" . trim($value_to_save) . "'", $model . '.' . $fieldname2 => "'" . trim($status) . "'"), array($model . '.id' => $id))) {
                print TRUE;
            } else {
                print "Not able to change the value, contact administrator.";
            }
        } else {
            print "Not able to change the value, enter the value to save.";
        }
        die;
    }

    # Saves value to table of model type with two text boxes.

    function savevalue2($modeltype, $fieldname1, $fieldname2, $fieldname3, $fieldname4, $id, $value_to_save1 = "", $value_to_save2 = "", $status = "", $value_to_save3 = "") {
        list($status, $name) = split(";", $status);

        $this->isAdmin(); //Check weather current user is admin or not.
        #Import modeltype(dynamic) model
        $model = ucfirst($modeltype);
        App::import('Model', $model);
        # Create Content type model dynamic object.
        $this->$model = new $model();
        $value_to_save1 = str_replace("~~~", "&", str_replace('$$$', "'", str_replace("^^^", '"', $value_to_save1)));
        $value_to_save2 = str_replace("~~~", "&", str_replace('$$$', "'", str_replace("^^^", '"', $value_to_save2)));

        # Check for already exists				
        if ($value_to_save1 != "") {
            $rec_exists1 = $this->$model->find('first', array('conditions' => array($fieldname1 => $value_to_save1)));
            if (isset($rec_exists1[$model]['id']) && $rec_exists1[$model]['id'] != $id) {
                //print '"'.$value_to_save1.'" already exists in another record';
                print 'exists';
                die;
            }
        }
        //$rec_exists2 = $this->$model->find('count', array('conditions' => array( $fieldname2 => $value_to_save2)));					
        # Updating value
        if (trim($value_to_save1) != "" && trim($value_to_save2) != "") {

            if ($this->$model->updateAll(array($model . '.' . $fieldname1 => "'" . trim($value_to_save1) . "'", $model . '.' . $fieldname2 => "'" . trim($value_to_save2) . "'", $model . '.' . $fieldname4 => "'" . $status . "'", $fieldname3 => "'" . $value_to_save3 . "'"), array($model . '.id' => $id))) {
                print TRUE;
            } else {
                print "Not able to change the value, contact administrator.";
            }
        } else {
            print "Not able to change the value, enter the value to save.";
        }
        die;
    }

    # Saves value to table of model type with three text boxes.

    function savevalue3($modeltype, $fieldname1, $fieldname2, $fieldname3, $fieldname4, $id, $value_to_save1 = "", $value_to_save2 = "", $value_to_save3 = "", $status = "") {
        $this->isAdmin(); //Check weather current user is admin or not.
        #Import modeltype(dynamic) model
        $model = ucfirst($modeltype);
        App::import('Model', $model);
        # Create Content type model dynamic object.
        $this->$model = new $model();
        $value_to_save1 = str_replace("~~~", "&", str_replace('$$$', "'", str_replace("^^^", '"', $value_to_save1)));
        $value_to_save2 = str_replace("~~~", "&", str_replace('$$$', "'", str_replace("^^^", '"', $value_to_save2)));
        $value_to_save3 = str_replace("~~~", "&", str_replace('$$$', "'", str_replace("^^^", '"', $value_to_save3)));

        # Check for already exists				
        if ($value_to_save1 != "") {
            $rec_exists1 = $this->$model->find('first', array('conditions' => array($fieldname2 => $value_to_save2)));
            if (isset($rec_exists1[$model]['id']) && $rec_exists1[$model]['id'] != $id) {
                print $model;
                die;
            }
        }

        # Updating value
        if (trim($value_to_save1) != "" && trim($value_to_save2) != "" && trim($value_to_save3) != "") {
            //print ".$modeltype.$fieldname1.$fieldname2.$fieldname3.$id. $value_to_save1.$value_to_save2.$value_to_save3.";die;
            if ($this->$model->updateAll(array($model . '.' . $fieldname1 => "'" . trim($value_to_save1) . "'", $model . '.' . $fieldname2 => "'" . trim($value_to_save2) . "'", $model . '.' . $fieldname3 => "'" . trim($value_to_save3) . "'", $model . '.' . $fieldname4 => "'" . $status . "'"), array($model . '.id' => $id))) {
                print TRUE;
            } else {
                print "Not able to change the value, contact administrator.";
            }
        } else {
            print "Not able to change the value, enter the value to save.";
        }
        die;
    }

    # Saves value to table of model type.
    # $fieldname1 = username, $fieldname2 = isactive

    function saveuser($modeltype, $fieldname1, $fieldname2, $password, $id, $value_to_save = "", $status = "", $passwd = NULL) {

        $passwd = ($passwd == "NULL") ? NULL : $passwd; //Password check for null.
        $role = NULL;
        #Import modeltype(dynamic) model
        $model = ucfirst($modeltype);
        App::import('Model', $model);
        # Create Content type model dynamic object.
        $this->$model = new $model();
        $value_to_save = str_replace("~~~", "&", str_replace('$$$', "'", str_replace("^^^", '"', $value_to_save)));
        $passwd = str_replace("~~~", "&", str_replace('$$$', "'", str_replace("^^^", '"', $passwd)));
        if ($passwd != "") {
            $passwd = md5($passwd);
            $role = "A";
        }

        //print $status;
        # Updating value
        if (trim($value_to_save) != "") {
            if ($this->$model->updateAll(array($model . '.' . $fieldname1 => "'" . trim($value_to_save) . "'", $model . '.' . $fieldname2 => "'" . trim($status) . "'", $model . '.' . $password => "'" . $passwd . "'", $model . '.role' => "'" . $role . "'"), array($model . '.id' => $id))) {
                print TRUE;
            } else {
                print "Not able to change the value, contact administrator.";
            }
        } else {
            print "Not able to change the value, enter the value to save.";
        }
        die;
    }

    # Removes record from the table.

    function removerecord($modeltype, $id) {

        $this->isAdmin(); //Check weather current user is admin or not.		
        #Import modeltype(dynamic) model
        $model = ucfirst($modeltype);
        App::import('Model', $model);
        # Create Content type model dynamic object.
        $this->$model = new $model();

        #Import modeltype(dynamic) model
        App::import('Model', 'Insight');
        # Create Content type model dynamic object.
        $this->Insight = new Insight();
        $rec_exists = 0;
        if ($model == 'Competitorname') {
            $rec_exists = $this->Insight->find('count', array('conditions' => array("OR" => array('competitor_id' => $id, 'relates_competitor_id' => $id))));
        }
        if ($model == 'Firm') {
            $acc_no = $this->$model->find('first', array('conditions' => array('id' => $id)));
            $rec_exists = $this->Insight->find('count', array('conditions' => array("OR" => array('who_firm_name' => $id, 'what_firm_name' => $id, 'who_account_no' => $acc_no[$model]['account_number']))));
        }
        if ($model == 'Insightabout') {
            $insight_type = $this->$model->find('first', array('conditions' => array('id' => $id)));
            $rec_exists = $this->Insight->find('count', array('conditions' => array('what_how_come' => $insight_type[$model]['insight_type'])));
        }
        if ($model == 'Market') {
            $rec_exists = $this->Insight->find('count', array('conditions' => array('market_id' => $id)));
        }
        if ($model == 'Contenttype') {
            $rec_exists = $this->Insight->find('count', array('conditions' => array('content_type_id' => $id)));
        }
        if ($model == 'Practicearea') {
            $rec_exists = $this->Insight->find('count', array('conditions' => array('practice_area_id' => $id)));
        }
        if ($model == 'Productfamilyname') {

            App::import('Model', 'Productname');
            $this->Productname = new Productname();
            $productNames = $this->Productname->find('all', array(
                'conditions' => array(
                    'Productname.isactive' => 1,
                    'Productname.product_family_id' => $id
                ),
                'fields' => array('product_code', 'id', 'product_name', '')
            ));
            if (count($productNames) > 0) {
                $rec_exists = 1;
                print "Multiple Product Names linked";
                die;
            }
            else
                $rec_exists = $this->Insight->find('count', array('conditions' => array("OR" => array('product_family_id' => $id, 'relates_product_family_id' => $id))));
        }
        if ($model == 'Productname') {
            $rec_exists = $this->Insight->find('count', array('conditions' => array('product_id' => $id)));
        }
        if ($model == 'Pilotgroup') {
            $rec_exists = $this->Insight->find('count', array('conditions' => array("OR" => array('user_id' => $id, 'updated_by' => $id, 'deligated_to' => $id))));
        }
        if ($model == 'Statusinsight') {
            $rec_exists = $this->Insight->find('count', array('conditions' => array('Insight.insight_status' => $id)));
        }
        if ($model == 'Departmentname') {
            App::import('Model', 'Pilotgroup');
            $this->Pilotgroup = new Pilotgroup();
            $rec_exists = $this->Pilotgroup->find('count', array('conditions' => array('Pilotgroup.department_id' => $id)));
        }
        if ($rec_exists > 0) {
            print "Record Exists";
        } else {
            $this->$model->delete($id);
            # URL to go back to search result page.
            print $this->Cookie->read('backUrl');
        }
        die;
    }

// End of removerecord action.
    # expecting a comment

    function addnew($modeltype = '') {
        # Include Layout
        $this->layout = 'pop';
        $this->isAdmin(); //Check weather current user is admin or not.

        $this->set('printmsg', 'hideElement');
        $this->set('table_name', $modeltype);
        $this->set('data', array());

        // Code by Pragya Dave
        # Import Productfamilyname model
        App::import('Model', 'Productfamilyname');
        # Create Productfamilyname model object
        $this->Productfamilyname = new Productfamilyname();
        # Set Who Product Family Names Array
        $this->set('arrProductFamilyNames', $this->Productfamilyname->getProductFamilyNames());

        # Import departmentname model
        App::import('Model', 'Departmentname');
        # Create Departmentname model object
        $this->Departmentname = new Departmentname();
        # Set Who Product Family Names Array
        $this->set('arrDepartmentnames', $this->Departmentname->getDepartmentNames());


        if (isset($this->data) && $this->data != '') {
            //pr($this->data);//die;
            $arr_data = $this->data;

            if ($modeltype != '') {
                #importing dynamic model
                $modelName = ucfirst($modeltype);
                $key = array_keys($arr_data[$modelName]);
                $value = array_values($arr_data[$modelName]);
                //print "hello";die;
                App::import('Model', $modelName);
                # Create dynamic model object.
                $this->$modelName = new $modelName();
                if ($modelName == 'Firm') {
                    $rec_exists = $this->$modelName->find('count', array('conditions' => array($key['1'] => trim($value['1']))));
                } else if ($modelName == 'Pilotgroup') {
                    $rec_exists = $this->$modelName->find('count', array('conditions' => array($key['0'] . " LIKE BINARY " => trim($value['0']))));
                } else {
                    $rec_exists = $this->$modelName->find('count', array('conditions' => array($key['0'] => trim($value['0']))));
                }

                //print $rec_exists ;die;
                if ($rec_exists > 0) {
                    $this->set('printmsg', 'showElement');

                    $this->set('data', $this->data);
                } else {
                    if ($modelName == 'Pilotgroup') {
                        if (isset($arr_data[$modelName]['password']) && $arr_data[$modelName]['password'] != "") {
                            $arr_data[$modelName]['password'] = md5($arr_data[$modelName]['password']);
                        }
                    }
                    // Triming spaces before saving.
                    foreach ($arr_data[$modelName] as $key => $val) {
                        $arr_data[$modelName][$key] = trim($val);
                    }

                    $this->$modelName->save($arr_data[$modelName]);
                    #checks if add has taken place from the same action reach to backurl otherwise go to that action
                    $backUrl = $this->Cookie->read('backUrl');
                    //die('hi');
                    ?>
                    <script language="javascript" type="text/javascript">
                        //parent.parent.GB_hide();							
                        parent.parent.window.location.reload();
                    </script>
                    <?php

                    /* if(strstr($backUrl,$action))
                      {
                      //$this->redirect($backUrl);
                      }
                      else
                      {
                      //$this->redirect(SITE_URL."/editrecords/showlist/".$modeltype."s");
                      } */
                }
            }
        }
    }

//End of addnew action.

    function edituser($modeltype = '', $id = '') {
        # Include Layout
        $this->layout = 'pop';
        $this->isAdmin(); //Check weather current user is admin or not.
        $rec_exists = 0;
        $this->set('printmsg', 'hideElement');
        $this->set('table_name', $modeltype);
        $this->set('data', array());
        $modelName = ucfirst($modeltype);

        # Import dynamic model 
        App::import('Model', $modelName);
        # Create dynamic model object.
        $this->$modelName = new $modelName();

        # Import departmentname model
        App::import('Model', 'Departmentname');
        # Create Departmentname model object
        $this->Departmentname = new Departmentname();
        # Set Who Product Family Names Array
        $this->set('arrDepartmentnames', $this->Departmentname->getDepartmentNames());

        $result = $this->$modelName->findById($id);

        if (isset($this->data) && $this->data != '') {
            $arr_data = $this->data;

            if ($modeltype != '') {
                if ($arr_data[$modelName]['name'] != $arr_data[$modelName]['confirm_name']) {
                    $rec_exists = $this->$modelName->find('count', array('conditions' => array($modelName . '.name' => trim($arr_data[$modelName]['name']))));
                }

                if ($rec_exists > 0) {
                    $this->set('printmsg', 'showElement');
                    $this->set('usre_data', $this->data);
                } else {
                    $key = array_keys($arr_data[$modelName]);
                    $value = array_values($arr_data[$modelName]);

                    if ($modelName == 'Pilotgroup') {
                        if (isset($arr_data[$modelName]['password']) && $arr_data[$modelName]['password'] != "") {
                            $arr_data[$modelName]['password'] = md5($arr_data[$modelName]['password']);
                        } elseif (!$arr_data[$modelName]['role'] == 'A') {
                            $arr_data[$modelName]['password'] = NULL;
                        }
                        //pr($arr_data);
                    }
                    //die;
                    $this->$modelName->save($arr_data[$modelName]);
                    #checks if add has taken place from the same action reach to backurl otherwise go to that action
                    $backUrl = $this->Cookie->read('backUrl');
                    //die('hi');
                    ?>
                    <script language="javascript" type="text/javascript">
                        //parent.parent.GB_hide();
                        parent.parent.parent.location.reload();
                    </script>
                    <?php

                }
            }
        }

        $this->set('usre_data', $result);
    }

//End of edit user action.	
    # Function to check weather the user is admin or not.

    function isAdmin() {
        $current_user_role = $this->Session->read('current_admin_role');
        $pass_login = $this->Session->read('password_login');
        if ($current_user_role != 'A') { //Not admin.
            $this->redirect(SITE_URL . '/admin');
        }

        if ($pass_login != TRUE) {
            $this->redirect(SITE_URL . '/');
        }
    }

    /**
     * @author Mohit Khurana
     * @created on 01/09/2010 
     * This function Export data to excel as per the search request. This function is called on search reults page to export the records based on the search criteria. 
     * @param $type (by default product)
     */
    function exportToExcel($type = '') {
        $this->layout = "empty";

        #importing a model		
        App::import('Model', 'Pilotgroup');
        # Create model object.
        $this->Pilotgroup = new Pilotgroup();

        #importing a model		
        App::import('Model', 'Departmentname');
        # Create model object.
        $this->Departmentname = new Departmentname();

        # Find data based on the search criteria
        $result = $this->Pilotgroup->find('all', array('conditions' => unserialize($this->Session->read('searchConditionArr')), 'order' => ''));
        # Set loop variable
        $i = 0;
        # Processing result.
        if (!empty($result)) {
            $i = 0;
            foreach ($result as $row) {
                # Set all field values in an array to form search records
                $final_result[$i]['first_name'] = ($row['Pilotgroup']['first_name'] != '') ? $row['Pilotgroup']['first_name'] : ' ';
                $final_result[$i]['sur_name'] = ($row['Pilotgroup']['sur_name'] != '') ? $row['Pilotgroup']['sur_name'] : ' ';
                $final_result[$i]['name'] = $row['Pilotgroup']['name'];
                $final_result[$i]['emailaddress'] = $row['Pilotgroup']['emailaddress'];
                $final_result[$i]['cc_emailaddress'] = $row['Pilotgroup']['cc_emailaddress'];
                if ($row['Pilotgroup']['department_id'] > 0) {
                    $deptData = $this->Departmentname->findbyid($row['Pilotgroup']['department_id']);
                    $final_result[$i]['department_name'] = $deptData['Departmentname']['department_name'];
                }


                if ($row['Pilotgroup']['role'] == "A") {
                    $Role = "Moderator";
                } else if ($row['Pilotgroup']['role'] == "S") {
                    $Role = "SME";
                } else {
                    $Role = "Contributor";
                }
                $final_result[$i]['role'] = $Role;
                $i++;
            }
        }

        $i = 0;
        #Set excel file header names

        $arrExcelInfo[0] = array('First Name', 'Last Name', 'User Name', 'Role', 'Email address', 'Cc To', 'Department Name');

        $arrExcelInfo[1] = array();
        App::import('Vendor', 'excelVendor', array('file' => 'excel' . DS . 'class.export_excel.php'));
        $fileName = "user_export.xls";
        #create the instance of the exportexcel format
        $excel_obj = new ExportExcel("$fileName");
        foreach ($final_result as $keySet => $valueSet) {
            $arrExcelInfo[1][$i] = $this->add_export_array($valueSet);
            $i++;
        }

        #setting the values of the headers and data of the excel file 
        #and these values comes from the other file which file shows the data

        $excel_obj->setHeadersAndValues($arrExcelInfo[0], $arrExcelInfo[1]);
        #now generate the excel file with the data and headers set
        $excel_obj->GenerateExcelFile();
    }

# End function	

    function add_export_array($valueSet) {
        # Mapping common fields.
        $arr[0] = $valueSet['first_name'];
        $arr[1] = $valueSet['sur_name'];
        $arr[2] = $valueSet['name'];
        $arr[3] = $valueSet['role'];
        $arr[4] = $valueSet['emailaddress'];
        $arr[5] = $valueSet['cc_emailaddress'];
        $arr[6] = $valueSet['department_name'];
        return $arr;
    }

# End function
}

// End of class.
?>