<?php
namespace controllers;
use Ubiquity\attributes\items\router\Get;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\controllers\auth\AuthController;
use Ubiquity\controllers\auth\WithAuthTrait;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;

/**
  * Controller TodosController
  */
#[Route('/todos/')]
class TodosController extends \controllers\ControllerBase{

    use WithAuthTrait;
    const CACHE_KEY = 'datas/lists/';
    const EMPTY_LIST_ID='not saved';
    const LIST_SESSION_KEY='list';
    const ACTIVE_LIST_SESSION_KEY='active-list';


    private function showMessage(string $header, string $message, string $type = '', string $icon = 'info circle',array $buttons=[]) {
        $this->loadView('main/message.html', compact('header', 'type', 'icon', 'message','buttons'));
    }

    #[Route(path: "#/_default/",name: "home")]
	public function index(){
        $list=USession::get(self::ACTIVE_LIST_SESSION_KEY,[]);
        $this->loadView('TodosController/index.html',['list'=>$list]);
	}

    #[Get(path: "new/{force}",name: "todos.new")]
    public function newList($force){

        if(!$force){
            $this->showMessage(
              'Nouvelle liste',
              'Une liste a déjà été créée. Souhaitez vous la vider ?',
              'info',
              'warning circle',
              [
                  ['caption'=>'Annuler','class'=>'inverted','url'=>[]],
                  ['caption'=>'Confirmer','class'=>'inverted','url'=>['todos.new',[1]]]
              ]
            );
            return;
        }
        USession::delete(self::ACTIVE_LIST_SESSION_KEY);
        $this->index();

    }

	#[Post(path: "add/",name: "todos.add")]
	public function addElement(){
		$v = URequest::post('list');
        $list = URequest::get(self::ACTIVE_LIST_SESSION_KEY);
        $list = USession::addValueToArray();

		$this->index();

	}


    #[Post(path: "edit/{index}/",name: "todos.edit")]
    public function editElement($index){

        $v = URequest::post('list');
        $list = URequest::get(self::ACTIVE_LIST_SESSION_KEY);

        $this->loadView('TodosController/editElement.html');

    }


    #[Get(path: "delete/{index}/",name: "todos.delete")]
    public function deleteElement($index){

        $v = URequest::post('list');
        $list = URequest::get(self::ACTIVE_LIST_SESSION_KEY);

        $this->loadView('TodosController/deleteElement.html');

    }


	#[Get(path: "savesList/",name: "todos.save")]
	public function saveList(){

		$this->loadView('TodosController/saveList.html');

	}


	#[Post(path: "loadList/",name: "todos.loadListPost")]
	public function loadListFromForm(){
		
		$this->loadView('TodosController/loadListFromForm.html');

	}


	#[Get(path: "loadList/{uniqid}/",name: "todos.loadList")]
	public function loadList($uniqid){
		
		$this->loadView('TodosController/loadList.html');

	}

    protected function getAuthController(): AuthController
    {
        return new MyAuth($this);
    }
}
