<?php
 namespace OCA\OwnNotes\Controller;

 use OCP\IRequest;
 use OCP\AppFramework\Controller;

 class NoteController extends Controller {

     public function __construct($AppName, IRequest $request){
         parent::__construct($AppName, $request);
     }

     /**
      * @NoAdminRequired
      */
     public function index() {
         // empty for now
     }

     /**
      * @NoAdminRequired
      *
      * @param int $id
      */
     public function show($id) {
         // empty for now
     }

     /**
      * @NoAdminRequired
      *
      * @param string $title
      * @param string $content
      */
     public function create($title, $content) {
         // empty for now
     }

     /**
      * @NoAdminRequired
      *
      * @param int $id
      * @param string $title
      * @param string $content
      */
     public function update($id, $title, $content) {
         // empty for now
     }

     /**
      * @NoAdminRequired
      *
      * @param int $id
      */
     public function destroy($id) {
         // empty for now
     }

 }