<?php

class FileStorage implements IStorage
{

    private $filePath = "blog.txt";

    public function GetAll()
    {
        $rawRes = json_decode(file_get_contents($this->filePath));
        $out = [];
        foreach ($rawRes as $item) {
            $newBlog = new Blog();
            $newBlog->setId($item->id);
            $newBlog->setTitle($item->title);
            $newBlog->setBody($item->body);
            $out[] = $newBlog;
        }
        return $out;
    }

    public function Remove($id)
    {
        $blogs = $this->GetAll();
        $toBeDeleted = $this->FindBlogById($blogs, $id);
        if ($toBeDeleted != null) {
            $index = array_search($toBeDeleted, $blogs);
            unset($blogs[$index]);
            file_put_contents($this->filePath, json_encode($blogs));
        }
    }

    public function Store(Blog $blog)
    {
        $blogs = $this->GetAll();

        if (empty($blog->getId())) {
            // insert
            $lastBlog = end($blogs);
            $blog->setId( $lastBlog->getId() + 1);
            $blogs[] = $blog;
        } else {
            // edit
            $found = $this->FindBlogById($blogs, $blog->getId());

            $found->setTitle($blog->getTitle());
            $found->setBody($blog->getBody());
        }
        file_put_contents($this->filePath, json_encode($blogs));
    }

    private function FindBlogById($blogs, $id) : Blog|null {
        foreach ($blogs as $blog) {
            if ($blog->getId() == $id) {
                return $blog;
            }
        }
        return null;
    }

    public function GetById($id)
    {
        $blogs = $this->GetAll();
        return $this->FindBlogById($blogs, $id);
    }
}