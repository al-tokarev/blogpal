<?php

    abstract class User implements EventListenerInterface
    {
        protected $id, $name, $role;

        abstract public function getTextToEdit();

        public function attachEvent(){}
        public function detouchEvent(){}
    }