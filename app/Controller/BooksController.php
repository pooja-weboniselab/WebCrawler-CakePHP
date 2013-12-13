<?php
/**
 * Created by JetBrains PhpStorm.
 * User: webonise
 * Date: 11/12/13
 * Time: 2:10 PM
 * To change this template use File | Settings | File Templates.
 */

class BooksController extends AppController {
   public $helpers = array ('Html' , 'Form','Session');
    public $component = array('Session');
    public function index() {
        $value = $this->Book->find('all');
        $this->set('books', $value);
        //pr($value );
    }
    public function view($id = null) {
        if(!$id){
            throw new NotFoundException(_('Invalid post'));

        }
        $book =    $this->Book->findById($id);
        if(!$book){
            throw new NotFoundException(_('Invalid post'));
        }
        $this->set('book',$book);

    }
    public function add() {
//       $this->layout='book';
        if($this->request->is('post')) {
            $this->Book->create();
            if($this->Book->save($this->request->data)){
                $this->Session->setFlash(_('your book has been saved.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('unable to add your book'));
        }


    }

    public function edit($id = null) {
        if(!$id){
            throw new NotFoundException(_('Invalid post'));

        }
        $book =    $this->Book->findById($id);
        if(!$book){
            throw new NotFoundException(_('Invalid post'));
        }
       if($this->request->is(array('post','put'))) {
           $this->Book->id = $id ;
           if($this->Book->save($this->request->data)){
               $this->Session->setFlash(_('your book has been updated.'));
               return $this->redirect(array('action' => 'index'));
           }
           return $this->redirect(array('action' => 'index'));

        }

        if(!$this->request->data) {
            $this->request->data = $book ;
        }

    }


    public function delete($id) {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }

        if ($this->Book->delete($id)) {
            $this->Session->setFlash(
                __('The post with id: %s has been deleted.', h($id))
            );
            return $this->redirect(array('action' => 'index'));
        }
    }
}


