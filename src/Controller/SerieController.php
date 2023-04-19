<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Serie;
use App\Repository\EpisodeRepository;
use App\Repository\GenreRepository;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SerieController extends AbstractController
{
    #[Route('/home', name: 'categor')]
    public function index(GenreRepository $genreRepository): Response
    {
        $genres = $genreRepository->findAll();

        return $this->render('serie/index.html.twig', [
            'genres'=>$genres
        ]);
    }
    #[Route('/serie/{id}', name: 'serieshower')]
    public function serieShower(SerieRepository $serieRepository, Genre $genreclass): Response
    {
        $serie = $serieRepository->findBy(['Genre'=>$genreclass]);
        return $this->render('serie/serieshow.html.twig', [
            'serie'=> $serie
            ]);
    }
    #[Route('/episode/{id}', name: 'episode')]
    public function episodes(EpisodeRepository $episodeRepository, Serie $serieclass): Response
    {
        $episode = $episodeRepository->findBy(['serie'=> $serieclass]);
        return $this->render('serie/episode.html.twig', [
            'episode'=> $episode
        ]);
    }

    #[Route('/insert/{id}', name: 'insert')]
    public function insert(Request $request, EntityManagerInterface $entityManager)
    {
        $genre = new Genre();
        $form = $this->createForm(Genre::class, $genre);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $genre = $form->getData();
            $entityManager->persist($genre);
            $entityManager->flush();
            $this->addFlash('warning', 'succesfully added');
            $this->redirectToRoute('home');

        }

    }

}
