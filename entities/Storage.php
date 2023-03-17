<?php

    abstract class Storage implements LoggerInterface, EventListenerInterface {
        public function logMessage(){}
        public function lastMessages(){}
        public function attachEvent(){}
        public function detouchEvent(){}

        abstract public function create($object);
        abstract public function read($slug);
        abstract public function update($id, $object);
        abstract public function delete($id);
        abstract public function list();
    }