<?php
/* /app/views/helpers/link.php */

class CustomHelper extends AppHelper {
    function formatDate($date) {
				if($date != "")
        	return $this->output(date('dS M, Y', strtotime($date)));
				else
					return "--";
    }

		function getCurrentStatus($id = '') {
				# Import Statusinsight model
				App::import('Model', 'Statusinsight');
				# Create Statusinsight model object
				$this->Statusinsight = new Statusinsight();
				
				$res = $this->Statusinsight->findById($id);
				if(trim($res['Statusinsight']['status']) == "") {
					$res['Statusinsight']['status'] = "";
				}
				
				return $res['Statusinsight']['status'];
		}

		function getUserNameById($id = '') {
				# Import Pilotgroup model
				App::import('Model', 'Pilotgroup');
				# Create Pilotgroup model object
				$this->Pilotgroup = new Pilotgroup();
								
				$res = $this->Pilotgroup->findById($id);
				if(trim($res['Pilotgroup']['name']) == "") {
					$res['Pilotgroup']['name'] = "";
				}
				return $res['Pilotgroup']['name'];
		}

		function getStatusActive($modelname, $id) {
				# Import Pilotgroup model
				App::import('Model', $modelname);
				# Create Pilotgroup model object
				$this->$modelname = new $modelname();
								
				$res = $this->$modelname->find('first', array('fields' => array($modelname.'.isactive'), 'conditions' => array($modelname.'.id' => $id)));
				
				return $res[$modelname]['isactive'];
		}

}

?>
