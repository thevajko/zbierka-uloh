<?php
class Message
{
    public int $id;
    public string $message;
    public string $created;
    public string $user;
    public ?string $private_for;
}