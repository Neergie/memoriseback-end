<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Editor;
use App\Entity\Genre;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadBooks($manager);

        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('theo@admin.com');

        $password = $this->hasher->hashPassword($admin, 'tests');
        $admin->setPassword($password);
        $admin->setFirstname("Theo");
        $admin->setLastname("Wizman");
        $admin->setRoles(['ROLE_ADMIN']);

        $user = new User();
        $user->setEmail('theo@user.com');

        $password = $this->hasher->hashPassword($admin, 'tests');
        $user->setPassword($password);
        $user->setFirstname("Theo");
        $user->setLastname("Wizman");
        $user->setRoles(['ROLE_USER']);

        $manager->persist($admin);
        $manager->persist($user);
    }

    public function loadBooks(ObjectManager $manager): void
    {
        $genre_bd = new Genre();
        $genre_bd->setName("BD");
        $genre_aventures = new Genre();
        $genre_aventures->setName("Aventures");
        $genre_romans = new Genre();
        $genre_romans->setName("Romans");
        $genre_scifi = new Genre();
        $genre_scifi->setName("Sciences-fictions");
        $genre_romance = new Genre();
        $genre_romance->setName("Romance");
        $genre_mangas = new Genre();
        $genre_mangas->setName("Mangas");
        $genre_horror = new Genre();
        $genre_horror->setName("Horreur");
        $genre_others = new Genre();
        $genre_others->setName("Autres");

        $babelio = new Editor();
        $babelio->setName("Babelio");
        $hachette = new Editor();
        $hachette->setName("Hachette"); 
        $gallimard = new Editor();
        $gallimard->setName("Gallimard");

        $joseph_delaney = new Author();
        $joseph_delaney->setFirstname("Joseph");
        $joseph_delaney->setLastname("Delaney");
        $joseph_delaney->setBirthday(new \DateTime("1945-07-25"));

        $jean_delafontaine = new Author();
        $jean_delafontaine->setFirstname("Jean");
        $jean_delafontaine->setLastname("De la Fontaine");
        $jean_delafontaine->setBirthday(new \DateTime("1695-04-06"));

        $epouvantor_tome_1 = new Book();
        $epouvantor_tome_1->setName("L'Épouvanteur, Tome 1 : L'apprenti-épouvanteur");
        $epouvantor_tome_1->setDescription("Thomas, le septième fils d'un septième fils, possède les qualités requises pour devenir l'apprenti de l'Epouvanteur. C'est une aubaine, car il n'est pas attiré par la vie de fermier. Il possède un don particulier pour voir et entendre ce que le commun des mortels ne perçoit pas. Il débute sa formation chez l'Epouvanteur, qui le teste la première nuit en l'enfermant dans une maison hantée.");
        $epouvantor_tome_1->setEbook(false);
        $epouvantor_tome_1->addEditor($babelio);
        $epouvantor_tome_1->addGenre($genre_horror);
        $epouvantor_tome_1->addAuthor($joseph_delaney);
        $epouvantor_tome_1->setStock(999);
        $epouvantor_tome_1->setPrice("14.99");
        $epouvantor_tome_1->setPublishDate(new \DateTime("2005-03-10"));
        $epouvantor_tome_1->setIsbn("978-2747017107");

        $manager->persist($genre_bd);
        $manager->persist($genre_aventures);
        $manager->persist($genre_romans);
        $manager->persist($genre_scifi);
        $manager->persist($genre_romance);
        $manager->persist($genre_mangas);
        $manager->persist($genre_horror);
        $manager->persist($genre_others);

        $manager->persist($babelio);
        $manager->persist($jean_delafontaine);
        $manager->persist($gallimard);
        $manager->persist($hachette);
        $manager->persist($joseph_delaney);
        $manager->persist($epouvantor_tome_1);
    }
}