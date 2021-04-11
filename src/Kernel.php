<?php

namespace App;

use App\Domain\Model\Book;
use App\Domain\Model\BookId;
use App\Domain\Model\BookTitle;
use App\Http\Book\Input;
use App\Http\Book\Output;
use App\Infrastructure\Persistence\BookRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('../config/{packages}/*.yaml');
        $container->import('../config/{packages}/'.$this->environment.'/*.yaml');

        if (is_file(\dirname(__DIR__).'/config/services.yaml')) {
            $container->import('../config/services.yaml');
            $container->import('../config/{services}_'.$this->environment.'.yaml');
        } elseif (is_file($path = \dirname(__DIR__).'/config/services.php')) {
            (require $path)($container->withPath($path), $this);
        }
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('../config/{routes}/'.$this->environment.'/*.yaml');
        $routes->import('../config/{routes}/*.yaml');

        if (is_file(\dirname(__DIR__).'/config/routes.yaml')) {
            $routes->import('../config/routes.yaml');
        } elseif (is_file($path = \dirname(__DIR__).'/config/routes.php')) {
            (require $path)($routes->withPath($path), $this);
        }
    }

    /**
     * @Route(name="book_creation", path="/books", methods="POST")
     */
    public function createBook(Request $request, BookRepositoryInterface $bookRepository, ValidatorInterface $validator): Response
    {
        $input = Input::createFromRequest($request);

        if (count($errors = $validator->validate($input)) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse($errorsString, 400);
        }

        $bookRepository->save($book = Book::create(
            new BookId(uniqid()),
            new BookTitle($input->getTitle()),
            $input->getContent()
            )
        );

        return new JsonResponse(Output::toArray($book), 201);
    }

    /**
     * @Route(name="book_delete", path="/books/{id}", methods="POST")
     */
    public function deleteBook(BookRepositoryInterface $bookRepository, $id): Response
    {
        $bookRepository->remove(new BookId($id));

        return new JsonResponse(null, 204);
    }

    /**
     * @Route(name="book_read", path="/books/{id}", methods="GET")
     */
    public function readBook(BookRepositoryInterface $bookRepository, $id): Response
    {
        return new JsonResponse($bookRepository->get(new BookId($id)));
    }

    /**
     * @Route(name="book_list", path="/books", methods="GET")
     */
    public function listBooks(BookRepositoryInterface $bookRepository): Response
    {
        return new JsonResponse(Output::createList($bookRepository->getAll()));
    }
}
