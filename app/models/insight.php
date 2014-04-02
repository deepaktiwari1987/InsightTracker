<?php

class Insight extends AppModel {

    var $name = 'Insight';
    var $belongsTo = array(
        'Pilotgroup' => array(
            'className' => 'Pilotgroup',
            'foreignKey' => 'user_id'
        ),
        'Pilotgroup_D' => array(
            'className' => 'Pilotgroup',
            'foreignKey' => 'deligated_to',
            'fields' => 'Pilotgroup_D.id,Pilotgroup_D.name,Pilotgroup_D.emailaddress,Pilotgroup_D.cc_emailaddress,Pilotgroup_D.first_name,Pilotgroup_D.sur_name'
        ),
        'Firm' => array(
            'className' => 'Firm',
            'foreignKey' => false,
            'fields' => 'Firm.parent_id, Firm.firm_name',
            'conditions' => array(
                "Insight.who_account_no = Firm.account_number",
            )),
        'Productfamilyname' => array(
            'className' => 'Productfamilyname',
            'foreignKey' => 'product_family_id'
        ),
        'Productname' => array(
            'className' => 'Productname',
            'foreignKey' => 'product_id'
        ),
        'Contenttype' => array(
            'className' => 'Contenttype',
            'foreignKey' => 'content_type_id'
        ),
        'Practicearea' => array(
            'className' => 'Practicearea',
            'foreignKey' => 'practice_area_id'
        ),
        'Competitorname' => array(
            'className' => 'Competitorname',
            'foreignKey' => 'competitor_id'
        ),
        'Sellingobstacle' => array(
            'className' => 'Sellingobstacle',
            'foreignKey' => 'selling_obstacle_id'
        ),
        'Statusinsight' => array(
            'className' => 'Statusinsight',
            'foreignKey' => 'insight_status'
        ),
        'Issue' => array(
            'className' => 'Issue',
            'foreignKey' => 'issue_field'
        //'fields' => 'Issue.issue_title, Issue.issue_description'
        ),
        /* 		'Productarea' => array(
          'className'     => 'Productarea',
          'foreignKey'    => 'product_area_id'
          ), */
        'Market' => array(
            'className' => 'Market',
            'foreignKey' => 'market_id'
        )
    );

    function getInsightData($arrFields = array(), $arrConditions = array()) {
        return $this->find('first', array('fields' => $arrFields, 'conditions' => $arrConditions));
    }

    /**
     * Returns pilotgroup id on the basis of insight id.
     */
    function getCreatedById($insightId) {
        $result = $this->find('first', array('fields' => array('user_id'), 'conditions' => array('Insight.id' => $insightId)));
        return $result['Insight']['user_id'];
    }

    /* 	function paginate($conditions ='', $fields, $order, $limit=2, $page = 1, $recursive = null, $extra = array() ) {

      $recursive = -1;
      if($page)
      $from=$page*$limit-$limit;
      else
      $from=0;

      $order_nby= "ORDER BY ".$order;

      $cond = "";
      if(is_array($conditions) && count($conditions) > 0) {

      $cond = " WHERE "." 1 = 1";
      foreach($conditions as $key=>$val){
      $cond .= " AND ".$key ." = '" .$val. "'";

      }
      }
      echo	$sql = "SELECT *	FROM `Insights`  Insight,`competitornames` Competitorname, `pilotgroups` Pilotgroup ,`contenttypes`,`firms`,`insightabouts`,`insighttypes`,`markets`,`practiceareas`,`productareas`,`productfamilynames`,`productnames`,`sellingobstacles`,`statusinsights`  ".$cond ."	$order_nby LIMIT $from , $limit ";

      $orders = $this->query($sql);


      return $orders;


      }
      function paginateCount($conditions = null, $recursive = 0, $extra = array()) {

      $recursive = -1;
      if($page)
      $from=$page*$limit-$limit;
      else
      $from=0;
      preg_match("/sort:(.*?)\//s",$_SERVER["REQUEST_URI"],$order_on);
      preg_match("/direction:(.*?)$/s",$_SERVER["REQUEST_URI"],$order_by);


      $order_nby="";


      $cond = "";
      if(is_array($conditions) && count($conditions) > 0) {

      $cond = " WHERE "." 1 = 1";
      foreach($conditions as $key=>$val){
      $cond .= " AND ".$key ." = '" .$val. "'";

      }
      }
      echo	$sql = "SELECT *	FROM `Insights`  Insight,`competitornames` Competitorname ,`contenttypes`,`firms`,`insightabouts`,`insighttypes`,`markets`,`practiceareas`,`productareas`,`productfamilynames`,`productnames`,`sellingobstacles`,`statusinsights`  ".$cond ;
      die;

      return count($results);
      } */
}

?>