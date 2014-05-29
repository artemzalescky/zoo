<?
	// контроллер поиска

	class Application_Controllers_Search  extends Lib_BaseController {
	
		function index() {  
			$model = new Application_Models_Search;
			$this->search_result = $model->search($_GET['words']);
		}
		
	}
	
?>