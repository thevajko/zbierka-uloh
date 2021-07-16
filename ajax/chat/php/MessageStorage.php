<?php

class MessageStorage
{
    /**
     * @return Message[]
     * @throws Exception
     */
    public function getMessages($userName = ""): array
    {
        try {
            if (empty($userName)){
                return Db::conn()
                    ->query("SELECT * FROM messages WHERE private_for IS null ORDER by created DESC LIMIT 50")
                    ->fetchAll(PDO::FETCH_CLASS, Message::class);
            } else {
                $stat = Db::conn()
                    ->prepare("SELECT * FROM messages  WHERE private_for IS null OR private_for LIKE ? OR user LIKE ? ORDER by created DESC LIMIT 50");
                $stat->execute([$userName,$userName ]);
                return $stat->fetchAll(PDO::FETCH_CLASS, Message::class);
            }
        }  catch (PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }


    public function storeMessage(Message $message){
        try {
            $sql = "INSERT INTO messages (message, created, user, private_for) VALUES (?, ?, ?, ?)";
            DB::conn()->prepare($sql)
                ->execute([$message->message, $message->created, $message->user, $message->private_for]);
        }  catch (PDOException $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }
}