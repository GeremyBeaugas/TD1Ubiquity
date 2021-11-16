<?php
namespace controllers;
use Ubiquity\attributes\items\router\Get;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;

/**
  * Controller TodosController
  */
#[Route('/todos/')]
class TodosController extends \controllers\ControllerBase{


    const CACHE_KEY = 'datas/lists/';
    const EMPTY_LIST_ID='not saved';
    const LIST_SESSION_KEY='list';
    const ACTIVE_LIST_SESSION_KEY='active-list';

    #[Route(path: "#/_default/",name: "home")]
	public function index(){
        $list=USession::get(self::ACTIVE_LIST_SESSION_KEY,[]);
        $this->loadView('TodosController/index.html',['list'=>$list]);
	}

    #[Get(path: "new/{force}",name: "todos.new")]
    public function newList($force){

        if($force == false){
            ['message'=>"Créer une nouvelle liste ?"];
        }
        $list= USession::get('list');
        ['message'=>'Liste créée'];
        $this->loadView('TodosController/newList.html',['list'=>$list]);

    }

	#[Post(path: "add/",name: "todos.add")]
	public function addElement(){
		$v = URequest::post('list');
        $list = URequest::get(self::ACTIVE_LIST_SESSION_KEY);

		$this->loadView('TodosController/addElement.html');

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

		$this->loadView('TodosController/saveList.html',['list'=>$list]);

	}


	#[Post(path: "loadList/",name: "todos.loadListPost")]
	public function loadListFromForm(){
		
		$this->loadView('TodosController/loadListFromForm.html');

	}


	#[Get(path: "loadList/{uniqid}/",name: "todos.loadList")]
	public function loadList($uniqid){
		
		$this->loadView('TodosController/loadList.html');

	}

}
