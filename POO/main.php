<?php

require_once 'classes.php';

$Collection = new Collection();

$admin = new Admin("SuperAdmin", "admin@test.com", "1234", "YES");
$auth1 = new Author("Victor Hugo", "hugo@test.com", "1234", "French Romantic writer.");
$auth2 = new Author("Jane Austen", "jane@test.com", "1234", "English novelist.");
$auth3 = new Author("George Orwell", "george@test.com", "1234", "Dystopian visionary.");
$support = new Admin("TechSupport", "support@test.com", "1234", "NO");

$Collection->storage['users'][] = $admin;
$Collection->storage['users'][] = $auth1;
$Collection->storage['users'][] = $auth2;
$Collection->storage['users'][] = $auth3;
$Collection->storage['users'][] = $support;

$art1 = $auth1->createArticle("Les Miserables", "A story of justice, redemption, and revolution in France.");
$art1->setStatus("published");
$art2 = $auth1->createArticle("The Hunchback of Notre-Dame", "Quasimodo, the bell-ringer of Notre-Dame.");

$art3 = $auth2->createArticle("Pride and Prejudice", "It is a truth universally acknowledged, that a single man in possession of a good fortune, must be in want of a wife.");
$art3->setStatus("published");
$art4 = $auth2->createArticle("Emma", "Handsome, clever, and rich, with a comfortable home and happy disposition.");
$art4->setStatus("published");
$art5 = $auth2->createArticle("Sense and Sensibility", "The Dashwood sisters and their love lives.");

$art6 = $auth3->createArticle("1984", "Big Brother is watching you. War is Peace. Freedom is Slavery.");
$art6->setStatus("published");
$art7 = $auth3->createArticle("Animal Farm", "All animals are equal, but some animals are more equal than others.");

$c1 = new Commentaire(1, "An absolute masterpiece of literature.", "BookWorm99");
$c1->setStatus("approved");
$art1->addComment($c1);

$c2 = new Commentaire(2, "The book is way too long for me.", "LazyReader");
$art1->addComment($c2);

$c3 = new Commentaire(3, "Mr. Darcy is the ultimate dream.", "RomanceFan");
$c3->setStatus("approved");
$art3->addComment($c3);

$c4 = new Commentaire(4, "I prefer the movie version.", "MovieBuff");
$art3->addComment($c4);

$c5 = new Commentaire(5, "Emma is such a complex character.", "Janeite");
$c5->setStatus("approved");
$art4->addComment($c5);

$c6 = new Commentaire(6, "This book predicted the future.", "TinfoilHat");
$c6->setStatus("approved");
$art6->addComment($c6);

$c7 = new Commentaire(7, "Too scary to read at night.", "ScaredyCat");
$art6->addComment($c7);

$c8 = new Commentaire(8, "Four legs good, two legs bad!", "Snowball");
$c8->setStatus("approved");
$art7->addComment($c8);

$c9 = new Commentaire(9, "Poor Quasimodo.", "Empath");
$art2->addComment($c9);

echo "========================================\n";
echo "      WELCOME TO THE BLOG SYSTEM\n";
echo "========================================\n";

$currentUser = null;

while ($currentUser === null) {
    echo "\nPlease Log In:\n";
    
    $email = readline("Email: ");
    $password = readline("Password: ");

    foreach ($Collection->storage['users'] as $u) {
        if ($u->getEmail() === $email && $u->getPassword() === $password) {
            $currentUser = $u;
            echo "\nLogin Successful! Welcome, " . $u->getUsername() . ".\n";
            break;
        }
    }

    if ($currentUser === null) {
        echo "Wrong email or password. Try again.\n";
    }
}

while (true) {
    echo "\n----------------------------------------\n";
    echo "              MAIN MENU\n";
    echo "----------------------------------------\n";

    echo "1. Logout / Exit\n";
    echo "2. View All Articles (Homepage)\n";
    echo "3. View Comments on an Article\n";
    
    if ($currentUser instanceof Author) {
        echo "4. Add Comment to an Article\n";
        echo "5. [Author] My Articles\n";
        echo "6. [Author] Create Article\n";
        echo "7. [Author] Update My Article\n";
        echo "8. [Author] Delete My Article\n";
    }

    if ($currentUser instanceof Admin) {
        echo "9. [Admin] Create User\n";
        echo "10. [Admin] List All Users\n";
        echo "11. [Admin] Delete User\n";
    }
    
    if ($currentUser instanceof Moderateur) {
        echo "12. [Admin] Delete ANY Article\n";
        echo "13. [Mod] Create Category\n";
        echo "14. [Mod] Publish Article\n";
        echo "15. [Mod] Approve Comment\n";
        echo "16. [Mod] Delete Comment\n";
    }

    echo "----------------------------------------\n";
    $choice = readline("Choose an option number: ");
    echo "\n";

    switch ($choice) {
        case '1':
            echo "Goodbye!\n";
            exit; 

        case '2':
            echo "--- WEBSITE HOMEPAGE ---\n";
            $allArticles = $currentUser->getAllarticles(); 
            if (empty($allArticles)) { echo "No articles found.\n"; }
            foreach ($allArticles as $art) {
                echo "ID: " . $art->getId() . " | " . $art->getTitle() . " | Status: " . $art->getStatus() . " | Comments: " . count($art->getComments()) ."\n";
            }
            break;

        case '3':
            echo "--- VIEW COMMENTS ---\n";
            $artId = (int)readline("Enter Article ID: ");
            
            $foundArt = null;
            
            foreach($Collection->storage['articles'] as $art) {
                if ($art->getId() == $artId) {
                    $foundArt = $art;
                }
            }

            if ($foundArt) {
                $commentsList = $foundArt->getComments();
                if (empty($commentsList)) echo "No comments yet.\n";
                
                foreach($commentsList as $c) {
                    echo "[" . $c->getStatus() . "] " . $c->getAuthorName() . ": " . $c->getContent() . " (ID: " . $c->getId() . ")\n";
                }
            } else {
                echo "Article not found.\n";
            }
            break;

        case '4':
            if ($currentUser instanceof Author) {
                echo "--- ADD COMMENT ---\n";
                $artId = (int)readline("Enter Article ID: ");
                
                $foundArt = null;
                foreach($Collection->storage['articles'] as $art) {
                    if ($art->getId() == $artId) $foundArt = $art;
                }

                if ($foundArt) {
                    $content = readline("Your Comment: ");
                    $newComId = rand(100, 999);
                    
                    $newComment = new Commentaire($newComId, $content, $currentUser->getUsername());
                    
                    $foundArt->addComment($newComment);
                    echo "Comment added! (Pending Review)\n";
                } else {
                    echo "Article not found.\n";
                }
            }
            break;

        case '5':
            if ($currentUser instanceof Author) {
                foreach ($currentUser->getMyArticles() as $art) {
                    echo "ID: " . $art->getId() . " | " . $art->getTitle() . "\n";
                }
            }
            break;

        case '6':
            if ($currentUser instanceof Author) {
                $title = readline("Title: ");
                $content = readline("Content: ");
                $currentUser->createArticle($title, $content);
                echo "Article Created!\n";
            }
            break;

        case '7':
            if ($currentUser instanceof Author) {
                $id = (int)readline("Article ID to update: ");
                $data = ['title' => readline("New Title: "), 'content' => readline("New Content: ")];
                if ($currentUser->updateOwnArticle($id, $data)) echo "Updated.\n";
                else echo "Not found.\n";
            }
            break;

        case '8':
            if ($currentUser instanceof Author) {
                $id = (int)readline("Article ID to delete: ");
                if ($currentUser->deleteOwnArticle($id)) echo "Deleted.\n";
                else echo "Failed.\n";
            }
            break;

        case '9':
            if ($currentUser instanceof Admin) {
                echo "Creating new Author...\n";
                $newUser = new Author(readline("User: "), readline("Email: "), readline("Pass: "), "Bio");
                $currentUser->createUser($newUser);
                echo "User Added.\n";
            }
            break;

        case '10':
            if ($currentUser instanceof Admin) {
                foreach ($currentUser->listAllUsers() as $u) {
                    echo "ID: " . $u->getid_utilisateur() . " | " . $u->getUsername() . " (" . get_class($u) . ")\n";
                }
            }
            break;
            
        case '11':
            if ($currentUser instanceof Admin) {
                $id = (int)readline("User ID to delete: ");
                if ($currentUser->deleteUser($id)) echo "User Deleted.\n";
                else echo "Not found.\n";
            }
            break;

        case '12':
            if ($currentUser instanceof Admin) {
                $id = (int)readline("Article ID to delete: ");
                if ($currentUser->deleteAnyArticle($id)) echo "Article Deleted from Everywhere.\n";
                else echo "Not found.\n";
            }
            break;

        case '13':
            if ($currentUser instanceof Moderateur) {
                $currentUser->createCategory(readline("Name: "), "Description");
                echo "Category Created.\n";
            }
            break;

        case '14':
            if ($currentUser instanceof Moderateur) {
                $id = (int)readline("Article ID to publish: ");
                $currentUser->publishArticle($id);
            }
            break;

        case '15':
            if ($currentUser instanceof Moderateur) {
                $id = (int)readline("Comment ID to Approve: ");
                if ($currentUser->approveComment($id)) echo "Comment Approved.\n";
                else echo "Comment not found.\n";
            }
            break;

        case '16':
            if ($currentUser instanceof Moderateur) {
                $id = (int)readline("Comment ID to Delete: ");
                if ($currentUser->deleteComment($id)) echo "Comment Deleted.\n";
                else echo "Comment not found.\n";
            }
            break;

        default:
            echo "Invalid option.\n";
    }
    
    echo "\n(Press Enter to continue...)";
    fgets(STDIN); 
}
?>