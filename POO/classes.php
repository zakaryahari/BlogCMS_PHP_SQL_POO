<?php

    require_once 'data.php';

    $currentUser = null;

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


        public function login(string $email, string $password): ?User {

            global $users;

            foreach($users as $u){
                if ($u->getEmail() == $email && password_verify($password , $u->getPassword())) {
                    $u->setLastLogin(new DateTime());

                    return $u;
                }
            }
            return NULL;
        }

        public function logout(): void {
            echo "Déconnexion en cours...\n";
        }
    }

    class Moderateur extends User {

        public function approveComment(int $id_commentaire): bool {
            return false;
        }

        public function deleteComment(int $id_commentaire): bool {
            return false;
        }

        public function createCategory(string $name, string $desc , Categorie $parentCategory = null): Categorie {

            global $categories;
            $newId = count($categories) + 1;

            $cat = new Categorie($newId, $name , $desc , $parentCategory);

            $categories[] = $cat;

            return $cat;
        }

        public function deleteCategory(int $id_categorie): bool {
            global $categories;

            foreach($categories as $key => $cat){
                if ($cat->getId() == $id_categorie) {
                    unset($cat[$key]);
                    return true;
                }
            }
            
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
        private array $articles = [];

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
        private DateTime $createdAt ;
        private ?DateTime $publishedAt ;
        private ?DateTime $updatedAt ;

        public function __construct(int $id, string $titre, string $content, string $excerpt) {
            $this->id_article = $id;
            $this->titre = $titre;
            $this->content = $content;
            $this->excerpt = substr($content, 0, 150) . "...";
            $this->status = "draft";
            $this->createdAt = new DateTime();
        }
        
        public function getId(): int {
            return $this->id_article; 
        }

        public function getTitle(): string {
            return $this->titre; 
        }

        public function getContent(): string {
            return $this->content; 
        }

        public function getExcerpt(): string {
            return $this->excerpt; 
        }

        public function getStatus(): string {
            return $this->status; 
        }


        public function getCreatedAt(): DateTime {
            return $this->createdAt; 
        }



        public function setTitle(string $titre): void {
            $this->titre = $titre; 
        }

        public function setContent(string $content): void {
            $this->content = $content; 
        }
        
        public function setStatus(string $status): void {
            $this->status = $status; 
        }

        public function setPublishedAt(string $date): void { 
            $this->publishedAt = $date; 
        }



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

        public function __construct(int $id, string $name, string $desc, ?Categorie $parent = null) {
            $this->id_categorie = $id;
            $this->name = $name;
            $this->description = $desc;
            $this->parent = $parent;
            $this->createdAt = new DateTime();
        }


        public function getId(): int {
            return $this->id_categorie;
        }

        public function getName(): string {
            return $this->name;
        }

        public function getDescription(): string {
            return $this->description;
        }

        public function getParent(): ?Categorie {
            return $this->parent;
        }

        public function getCreatedAt(): DateTime {
            return $this->createdAt;
        }


        public function setName(string $name): void {
            $this->name = $name;
        }

        public function setDescription(string $description): void {
            $this->description = $description;
        }
        public function getParent(): ?Categorie {
            return $this->parent;
        }

        public function getTree(): array {
            return [];
        }


    }

    class Commentaire {
        private int $id_commentaire;
        private String $contenu_commentaire;
        private DateTime $createdAt;

        public function __construct(int $id , String $contenu_commentaire){

        }

        public function addComment(): void {
        }
    }

?>