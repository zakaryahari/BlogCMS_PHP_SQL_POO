<?php

    class User {
        protected int $id_utilisateur ;
        protected String $username ;
        protected String $email ;
        protected String $password ;
        protected DateTime $createdAt ;
        protected DateTime $lastLogin ;
        protected 
        
        public function login(string $email, string $password): bool {
            foreach()
            return false;
        }

        public function logout(): void {
        }
    }

    class Moderateur extends user {
        public function approveComment(int $id_commentaire): bool {
            return false;
        }

        public function deleteComment(int $id_commentaire): bool {
            return false;
        }

        public function createCategory(string $name, Categorie $parentCategory = null): Categorie {
            return new Categorie();
        }

        public function deleteCategory(int $id_categorie): bool {
            return false;
        }

        public function publishArticle(int $id_article): void {

        }

        public function deleteAnyArticle(int $id_article): bool {
            return false;
        }
    }

?>