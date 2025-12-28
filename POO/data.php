<?php
    class Collection {
        
        public array $storage = [];

        public function __construct() {
            
            $this->storage = [
                'users' => [
                    new Admin('SuperAdmin', 'admin@blog.com',"1234", 'YES'),
                    new Author('Alice', 'alice@blog.com',"1234", 'I love PHP'),
                    new Author('Bob', 'bob@blog.com',"1234", 'Coding is life')
                ],
                'categories' => [],
                'articles'   => [],
                'comments'   => []
            ];
        }
    }
?>