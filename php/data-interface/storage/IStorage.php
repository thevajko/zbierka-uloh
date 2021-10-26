<?php

interface IStorage
{
    public function GetAll();
    public function Remove($id);
    public function Store(Blog $blog);
    public function GetById($id);
}