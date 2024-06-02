<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Editor;
use App\Entity\Genre;
use App\Model\Dto\BookAddDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class BookController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/book/add', name: 'add_book', methods: ['POST'])]
    public function add_book(#[MapRequestPayload] BookAddDto $bookAddDto, EntityManagerInterface $entityManager): JsonResponse
    {
        $book = new Book();

        $book->setName($bookAddDto->title);
        $book->setDescription($bookAddDto->description);
        $book->setPublishDate($bookAddDto->publicationDate);
        $book->setISBN($bookAddDto->isbn);
        $book->setStock($bookAddDto->stock);
        $book->setPrice($bookAddDto->price);
        $book->setEbook($bookAddDto->ebook);

        foreach ($bookAddDto->authors as $author) {
            $book->addAuthor($entityManager->getReference(Author::class, $author));
        }
        foreach ($bookAddDto->editors as $editor) {
            $book->addEditor($entityManager->getReference(Editor::class, $editor));
        }

        foreach ($bookAddDto->genres as $genre) {
            $book->addGenre($entityManager->getReference(Genre::class, $genre));
        }

        $entityManager->persist($book);
        $entityManager->flush();

        if ($bookAddDto->coverImage != null && base64_decode($bookAddDto->coverImage)) {
            $filesystem = new Filesystem();

            try {
                $path = '/Users/wizman/Desktop/memorise_picture';
                $filesystem->mkdir(
                    Path::normalize($path),
                );

                $picturebase64 = $bookAddDto->coverImage;
                $picture_decoded = base64_decode(explode(';base64,', $bookAddDto->coverImage)[1]);
                $extension = substr($picturebase64, strlen("data:image/"), strpos($picturebase64, ";base64") - strlen("data:image/"));

                $coverImagePath = $path . '/' . $book->getId() . '.' . $extension;
                $filesystem->dumpFile($coverImagePath, $picture_decoded);
                $book = $entityManager->getRepository(Book::class)->find($book->getId());
                $book->setCoverImagePath($coverImagePath);
                $entityManager->flush();
            } catch (IOExceptionInterface $exception) {
                $entityManager->remove($book);
                $entityManager->flush();
                return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
            }
        }
            
        return new JsonResponse(null, Response::HTTP_OK);
    }

    #[Route('/book/search/{book_name}/{books_count}', name: 'search_book', methods: ['GET'])]
    public function search(string $book_name, int $books_count, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $qb = $entityManager->createQueryBuilder()
            ->select('b')
            ->from('App\Entity\Book', 'b')
            ->where('lower(b.name) LIKE lower(:title)')
            ->orderBy('b.name', 'ASC')
            ->setMaxResults($books_count)
            ->setParameter('title', '%' . $book_name . '%');

        return JsonResponse::fromJsonString($serializer->serialize($qb->getQuery()->execute(), 'json'));
    }

    #[Route('/books/genre/{genre_id}/{books_count}', name: 'search_by_genre_book', methods: ['GET'])]
    public function searchByGenre(int $genre_id, int $books_count, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $qb = $entityManager->createQueryBuilder()
            ->select('b')
            ->from('App\Entity\Book', 'b')
            ->where(':genre_id MEMBER OF b.genres')
            ->orderBy('b.id', 'DESC')
            ->orderBy('b.stock', 'DESC')
            ->setMaxResults($books_count)
            ->setParameter('genre_id', $genre_id);

        return JsonResponse::fromJsonString($serializer->serialize($qb->getQuery()->execute(), 'json'));
    }

    #[Route('/book/{book_id}', name: 'show_book', methods: ['GET'])]
    public function show(
        #[MapEntity(id: 'book_id')]
        Book $book,
        SerializerInterface $serializer
    ): JsonResponse {
        return JsonResponse::fromJsonString($serializer->serialize($book, 'json'));
    }
}