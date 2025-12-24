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

    class Author extends User {
        private String $bio ;

        public function createArticle(string $titre, string $content): Article {
            return new Article();
        }

        public function updateOwnArticle(int $id_article, array $data): bool {
            return false;
        }

        public function deleteOwnArticle(int $id_article): bool {
            return false;
        }

        public function getMyArticles(): array {
            return [];
        }
    }

    class Admin extends Moderateur {
        private String $isSuperAdmin ;

        public function createUser(string $username, string $email, string $password): Utilisateur {
            return new Utilisateur();
        }

        public function deleteUser(int $id_utilisateur): bool {
            return false;
        }

        public function updateUserRole(int $id_utilisateur, string $role): bool {
            return false;
        }

        public function listAllUsers(): array {
            return [];
        }        
    }

    class Editeur extends Moderateur {
        private String $moderationLevel ;
    }

    class Article {
        private int $id_article ;
        private String $titre ; 
        private String $content ; 
        private String $excerpt ; 
        private String $status ;
        private String $author ;
        private String $createdAt ;
        private String $publishedAt ;
        private String $updatedAt ;

        public function addCategory(Categorie $category): void {
        }

        public function removeCategory(Categorie $category): void {
        }

        public function getComments(): array {
            return [];
        }
    }

    class Categorie {
        private int $id_categorie ;
        private String $name ;
        private String $description ;
        private Categorie $parent ;
        private DateTime $createdAt ;

        public function getParent(): Categorie {
            return $this->parent;
        }

        public function getTree(): array {
            return [];
        }
    }

    class Commentaire {
        private ind $id_commentaire;
        private String $libelle;
        private String $description;
        private String $createdAt;

        public function addComment(): void {
        }
    }

?>