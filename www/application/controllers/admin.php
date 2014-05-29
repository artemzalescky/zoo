<?php
	
	// контроллер админки

	class Application_Controllers_Admin  extends Lib_BaseController {
		function index() { 
			$model=new Application_Models_Admin;

			$auth_model = new Application_Models_Auth;	// модель авторизации
			if ($auth_model->checkAuth() == false) {		// если не авторизованы
				header('Location: /auth');		// перекидываем на логинку
			}

			if (count($_GET) == 1) {		// если нет параметров кроме $_GET['route']
				$this->catalogs = $model->getAllCatalogs();
			}
			
			if (isset($_GET['createtablebutton'])) {	// создание новой таблицы
				$model->createNewTable();
			}
			
			if (isset($_GET['add_subcatalog'])) {				// запрос на добавление подкаталога
				$this->parent_catalog = $model->getCatalogInfo($_GET['id_parent_catalog']);
			}
			if (isset($_GET['add_subcatalog_add_button'])) {	// запрос на добавление подкаталога (шаг 2, форма уже заполнена)
				$this->logs_add_subcatalog = $model->createNewCatalog($_GET['subcatalog_id'],$_GET['subcatalog_name'],$_GET['subcatalog_url'],$_GET['subcatalog_name_rus']);
				$this->parent_catalog_name_rus = $model->getNameRusParent($_GET['subcatalog_name']);
			}

			if (isset($_GET['delete_catalog'])) {				// запрос на удаление каталога
				$this->catalog = $model->getCatalogInfo($_GET['delete_catalog_id']);
			}
			if (isset($_GET['delete_catalog_confirm'])) {		// подтвердили удаление
				$this->logs_delete_catalog = $model->deleteCatalog($_GET['delete_catalog_id'],$_GET['delete_catalog_name']);
			}
			
			// =====================================================================================================================
			
			if (isset($_GET['id_catalog'])) {		// задали конкретный каталог

				$this->products = $model->getAllProducts($_GET['id_catalog']);
                $this->eng_rus_massive = $model->getAssosiateEnglishRussian();    //массив с ассоциациями русских - английских фраз
			}

            //если нажали на ссылку (изменить продукт)
            if (isset($_GET['change_product'])) {				// изменение продукта
				$this->product_info = $model->getProductInfo($_GET['show_id_catalog'],$_GET['id_class_catalog']);
            }

            //если нажали на кнопку отправить button_change_data_product, что отправляет данные и редактирует данные в базе данных
            if (isset($_GET['button_change_data_product'])) {				// обработать нажатие кнопки
				$logs_change_product = $model->changeDateProduct();
				if ($logs_change_product['success'] == true){
					header("Location:/admin?id_catalog={$logs_change_product['id_catalog']}");
				}
			}


            //если нажали на ссылкк(кнопку) - удалить
            if (isset($_GET['delete_product'])) {				// Обработать нажатие кнопки-ссылки

                $bool_delete_product = $model->deleteProduct($_GET['show_id_catalog'],$_GET['id_class_catalog']);
                header("Location:/admin?id_catalog={$bool_delete_product['id_catalog']}");
            }

            //если нажали на кнопку добавить новый товар
            //1-е необходимо получить все поля талицы в которую будем добавлять товар
            if (isset($_GET['create_product'])) {				// Обработать нажатие кнопки-ссылки
               $this->attribute_new_product = $model->getAttributeNewProduct($_GET['current_id_catalog']);  //получить все поля таблицы в которую будем добалять товар
               $this->last_id = $model->getLastNumberId($_GET['current_id_catalog']);
            }

            //если была нажата кнопка добавить новый продукт
            if (isset($_GET['button_new_product'])) {
                $bool_create_product = $model->createProduct(); //если все в порядке и продукт создан вернёт true, иначе false
                header("Location:/admin?id_catalog={$bool_create_product['id_catalog']}");
            }

		}
	}
?>