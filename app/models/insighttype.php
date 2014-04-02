<?php

class Insighttype extends AppModel {

    var $name = 'Insighttype';

    function returnStaticData($selectOption = FALSE) {
        $result = $this->find('list', array('fields' => array('Insighttype.insight_type', 'Insighttype.insight_type'), 'conditions' => array('Insighttype.isactive' => 1), 'order' => ('Insighttype.insight_type')));
        $count = count($result) + 1;
        if ($selectOption) {
            # Add blank option to dropdown.
            $result[0] = ' ';
        }
        # Set blank value at first.
        asort($result);
        # Get key for "other" in result array.
        $key = array_search('Other', $result);
        if ($key == 'Other') {
            # Remove 'Other' from middle.
            unset($result[$key]);
            # Add element at last.
            $result[$key] = 'Other';
        }
        return $result;
    }

    function getinsightTypesValues() {
        $result = $this->find('list', array('fields' => array('Insighttype.id', 'Insighttype.insight_type'), 'conditions' => array('Insighttype.isactive' => 1), 'order' => array('Insighttype.insight_type')));

        $count = count($result) + 1;
        # Add blank option to dropdown.
        $result[0] = ' ';
        # Set blank value at first.
        asort($result);
        # Get key for "other" in result array.
        $key = array_search('Other', $result);
        if ($key > 0) {
            # Remove 'Other' from middle.
            unset($result[$key]);
            # Add element at last.
            $result[$key] = 'Other';
        }
        return $result;
    }

}

?>