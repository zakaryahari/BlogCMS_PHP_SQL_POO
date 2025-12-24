<?php

    require_once 'data.php';

    class User {
        protected int $id_utilisateur ;
        protected String $username ;
        protected String $email ;
        protected String $password ;
        protected DateTime $createdAt ;
        protected ?DateTime $lastLogin ;

        public function __construct(int $id , String $username , String $email , String $password){
            $this->id_utilisateur = $id;
            $this->username = $username;
            $this->email = $email;
            $this->password = $password;
            $this->createdAt = new DateTime();
            $this->lastLogin = null;
        }

        public function getid_utilisateur() : int {
            return $this->id_utilisateur;
        }

        public function getUsername(): string {
            return $this->username; 
        }

        public function getEmail(): string {
            return $this->email; 
        }

        public function getPassword(): string {
            return $this->password; 
        }

        public function getCreatedAt(): DateTime {
            return $this->createdAt; 
        }

        public function getLastLogin(): ?DateTime {
             return $this->lastLogin; 
        }



        public function setUsername(string $username): void {
             $this->username = $username; 
        }

        public function setEmail(string $email): void {
             $this->email = $email; 
        }

        public function setPassword(string $password): void {
             $this->password = $password; 
        }

        public function setLastLogin(DateTime $date): void {
             $this->lastLogin = $date; 
        }


        public function login(string $email, string $password): bool {
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

        public function __construct(int $id, string $username, string $email, string $password, string $bio) {
            parent::__construct($id, $username, $email, $password);
            $this->bio = $bio;
        }

        public function getBio(): string { 
            return $this->bio; 
        }

        public function setBio(string $bio): void { 
            $this->bio = $bio; 
        }

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

        public function __construct(int $id, string $username, string $email, string $password, string $isSuperAdmin) {
            parent::__construct($id, $username, $email, $password);
            $this->isSuperAdmin = $isSuperAdmin;
        }        

        public function getIsSuperAdmin(): string { 
            return $this->isSuperAdmin; 
        }

        public function setIsSuperAdmin(string $val): void { 
            $this->isSuperAdmin = $val; 
        }

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

        public function __construct(int $id, string $username, string $email, string $password, string $moderationLevel) {
            parent::__construct($id, $username, $email, $password);
            $this->moderationLevel = $moderationLevel;
        }
        
        public function getModerationLevel(): string { 
            return $this->moderationLevel; 
        }

        public function setModerationLevel(string $level): void { 
            $this->moderationLevel = $level; 
        }
    }

    class Article {
        private int $id_article ;
        private String $titre ; 
        private String $content ; 
        private String $excerpt ; 
        private String $status ;
        private User $author ;
        private DateTime $createdAt ;
        private ?DateTime $publishedAt ;
        private ?DateTime $updatedAt ;


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
        private ?Categorie $parent ;
        private DateTime $createdAt ;

        public function getParent(): ?Categorie {
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