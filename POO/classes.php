<?php
    require_once 'data.php';

    $Collection = new Collection();

    class User {
        protected int $id_utilisateur ;
        protected string $username ;
        protected string $email ;
        protected string $password ;
        protected DateTime $createdAt ;
        protected ?DateTime $lastLogin ;

        public function __construct(int $id , string $username , string $email , string $password){
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

        public function getAllarticles() : array {
            global $Collection;
            $articlesArray = [];

            foreach($Collection->storage['users'] as $key => $u){
                if ($u instanceof Author) {
                    $art = $u->getArticles();
                    $articlesArray = array_merge($articlesArray, $art);
                }
            }
            return $articlesArray;
        }

        public function login(string $email, string $password): ?User {
            global $Collection;
            foreach($Collection->storage['users'] as $u){
                if ($u->getEmail() == $email && $password == $u->getPassword()) {
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
            global $Collection;

            foreach ($Collection->storage['articles'] as $art) {
                    
                    foreach ($art->getComments() as $com) {
                        if ($com->getId() == $id_commentaire) {
                            $com->setStatus("approved");
                            return true;
                        }
                    }
                }

                return false;
        }

        public function deleteComment(int $id_commentaire): bool {
            global $Collection;
            foreach ($Collection->storage['articles'] as $art) {
                  
                if ($art->deleteComment($id_commentaire)) {
                    return true;
                }
            }
            return false;
        }

        public function createCategory(string $name, string $desc , Categorie $parentCategory = null): Categorie {
            global $Collection;
            $newId = count($Collection->storage['categories']) + 1;

            $cat = new Categorie($newId, $name , $desc , $parentCategory);

            $Collection->storage['categories'][] = $cat;

            return $cat;

        }

        public function deleteCategory(int $id_categorie): bool {
            global $Collection;
            foreach($Collection->storage['categories'] as $key => $cat){
                if ($cat->getId() == $id_categorie) {
                    unset($Collection->storage['categories'][$key]);
                    return true;
                }
            }
            
            return false;
        }

        public function publishArticle(int $id_article): void {
            global $Collection;
            foreach($Collection->storage['articles'] as $art){
                if ($art->getId() == $id_article) {
                    $art->setStatus("published");
                    $art->setPublishedAt(new DateTime());

                    echo "Article $id_article publié avec succès !\n";
                    return;
                }
            }

            echo "Article introuvable.\n";
        }

        public function deleteAnyArticle(int $id_article): bool {
            global $Collection;
            $choix = false;

            foreach($Collection->storage['articles'] as $key => $art){
                if ($art->getId() == $id_article) {
                    unset($Collection->storage['articles'][$key]);
                    $choix = true;
                }
            }

            foreach($Collection->storage['users'] as $u) {
                if ($u instanceof Author) {
                    if ($u->removeLocalArticle($id_article)) {
                        break;
                    }
                }
            }
            return $choix;
        }
    }

    class Author extends User {
        private string $bio ;
        private array $articles = [];

        public function __construct(string $username, string $email, string $password, string $bio) {
            global $Collection;
            $newId = count($Collection->storage['users']) + 1;
            parent::__construct($newId, $username, $email, $password);
            $this->bio = $bio;
        }

        public function getBio(): string { 
            return $this->bio; 
        }

        public function setBio(string $bio): void { 
            $this->bio = $bio; 
        }

        public function getArticles(): array {
            return $this->articles;
        }

        public function addArticle(Article $article): void {
            $this->articles[] = $article; 
        }

        public function createArticle(string $titre, string $content): Article {
            global $Collection;
            $art = new Article($titre , $content);
            $Collection->storage['articles'][] = $art;
            $this->addArticle($art);
            return $art;
        }

        public function updateOwnArticle(int $id_article, array $data): bool {

            foreach($this->articles as $art){
                if ($art->getId() == $id_article) {
                    $art->setTitle($data['title']);
                    $art->setContent($data['content']);
                    $art->setExcerpt($data['content']);
                    $art->setupdatedAt(new DateTime());
                    return true;
                }
            }
            return false;
        }

        public function deleteOwnArticle(int $id_article): bool {

            $choix = false;

            foreach($this->articles as $key => $art){
                if ($art->getId() == $id_article) {
                    unset($this->articles[$key]);
                    $choix = true;
                }
            }
            return $choix;
        }

        public function getMyArticles(): array {
            return $this->articles;
        }

        public function removeLocalArticle(int $id_article) : bool{
            foreach($this->articles as $key => $art){
                if ($art->getId() == $id_article) {
                    unset($this->articles[$key]);
                    return true;
                }
            }
            return false;
        }
    }

    class Admin extends Moderateur {
        private string $isSuperAdmin ;

        public function __construct(string $username, string $email, string $password, string $isSuperAdmin) {
            global $Collection;
            $newId = count($Collection->storage['users']) + 1;
            parent::__construct($newId, $username, $email, $password);
            $this->isSuperAdmin = $isSuperAdmin;
        }        

        public function getIsSuperAdmin(): string { 
            return $this->isSuperAdmin; 
        }

        public function setIsSuperAdmin(string $val): void { 
            $this->isSuperAdmin = $val; 
        }

        public function createUser(User $utilisateur): User {
            global $Collection; 
            
            $Collection->storage['users'][] = $utilisateur;

            return $utilisateur;
        }

        public function deleteUser(int $id_utilisateur): bool {
            global $Collection;

            $choix = false;

            foreach($Collection->storage['users'] as $key => $u){
                if ($u->getid_utilisateur() == $id_utilisateur) {
                    unset($Collection->storage['users'][$key]);
                    $choix = true;
                }
            }
            
            return $choix;

        }

        public function listAllUsers(): array {
            global $Collection;
            return $Collection->storage['users'];
        }       
        
        
    }

    class Editeur extends Moderateur {
        private string $moderationLevel = 'junior';

        public function __construct(string $username, string $email, string $password, string $moderationLevel) {
            global $Collection;
            $newId = count($Collection->storage['users']) + 1;
            parent::__construct($newId, $username, $email, $password);
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
        private string $titre ; 
        private string $content ; 
        private string $excerpt ; 
        private string $status ;
        private DateTime $createdAt ;
        private ?DateTime $publishedAt ;
        private ?DateTime $updatedAt ;
        private array $comments = [];


        public function __construct(string $titre, string $content) {
            global $Collection;
            $newId = count($Collection->storage['articles']) + 1;
            $this->id_article = $newId;
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

        public function getComments(): array {
            return $this->comments;
        }

        public function addComment(Commentaire $comment): void {
            $this->comments[] = $comment;
        }

        public function setTitle(string $titre): void {
            $this->titre = $titre; 
        }

        public function setContent(string $content): void {
            $this->content = $content; 
        }

        public function setExcerpt(string $content): void {
            $this->excerpt = substr($content, 0, 150) . "..."; 
        }
        
        public function setStatus(string $status): void {
            $this->status = $status; 
        }

        public function setPublishedAt(DateTime $date): void { 
            $this->publishedAt = $date; 
        }

        public function setupdatedAt(DateTime $date): void { 
            $this->updatedAt = $date; 
        }

        public function deleteComment(int $commentId): bool {
            foreach ($this->comments as $key => $com) {
                if ($com->getId() == $commentId) {
                    unset($this->comments[$key]);
                    return true;
                }
            }
            return false;
        }

    }

    class Categorie {
        private int $id_categorie ;
        private string $name ;
        private string $description ;
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

        public function getTree(): array {
            return [];
        }


    }

    class Commentaire {
        private int $id_commentaire;
        private string $contenu_commentaire;               
        private string $status;
        private string $authorName;             
        private DateTime $createdAt;

        public function __construct(int $id, string $content ,string $authorName) {
            $this->id_commentaire = $id;
            $this->contenu_commentaire = $content;
            $this->authorName = $authorName;
            $this->status = "pending";
            $this->createdAt = new DateTime();
        }

        public function getId(): int {
            return $this->id_commentaire;
        }

        public function getContent(): string {
            return $this->contenu_commentaire;
        }

        public function getStatus(): string {
            return $this->status;
        }

        public function getCreatedAt(): DateTime {
            return $this->createdAt;
        }

        public function getAuthorName(): string {
            return $this->authorName;
        }



        public function setContent(string $content): void {
            $this->contenu_commentaire = $content;
        }

        public function setStatus(string $status): void {
            $this->status = $status;
        }
    }

?>