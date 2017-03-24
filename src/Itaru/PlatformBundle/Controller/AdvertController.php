<?php

// src/Itaru/PlatformBundle/Controller/AdvertController.php

namespace Itaru\PlatformBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request; //pour la gestion en request des paramètres hors route
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//declaration des entities
use \Itaru\PlatformBundle\Entity\Advert;


class AdvertController extends Controller
{
  public function indexAction($page)
  {
    // On ne sait pas combien de pages il y a
    // Mais on sait qu'une page doit être supérieure ou égale à 1
    if ($page < 1) {
      // On déclenche une exception NotFoundHttpException, cela va afficher
      // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
      throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
    }

    // Ici, on récupère la liste des annonces - en l'absence de DB notre liste d'annonce en dur
    $listAdverts = array(
      array(
        'title'   => 'Recherche développpeur Symfony',
        'id'      => 1,
        'author'  => 'Alexandre',
        'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
        'date'    => new \Datetime()),
      array(
        'title'   => 'Mission de webmaster',
        'id'      => 2,
        'author'  => 'Hugo',
        'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
        'date'    => new \Datetime()),
      array(
        'title'   => 'Offre de stage webdesigner',
        'id'      => 3,
        'author'  => 'Mathieu',
        'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
        'date'    => new \Datetime())
    );


    // puis on pace la liste au template index.html
    return $this->render(
      'ItaruPlatformBundle:Advert:index.html.twig',
      array('listAdverts' =>  $listAdverts )
    );
  }

  public function singleAction($id)
  {
    $advert = array(
      'title'   => 'Recherche développpeur Symfony2',
      'id'      => $id,
      'author'  => 'Alexandre',
      'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
      'date'    => new \Datetime()
    );

    return $this->render(
      'ItaruPlatformBundle:Advert:single.html.twig',
      array('advert' => $advert)
    );
  }

  public function addAction(Request $request)
  {
    //   // gestion des spams : On récupère le service
    // $antispam = $this->container->get('Itaru_platform.antispam');

    // // Je pars du principe que $text contient le texte d'un message quelconque
    // $text = '...';
    // if ($antispam->isSpam($text)) {
    //   throw new \Exception('Votre message a été détecté comme spam !');
    // }
    
    // Création de l'entité
    $advert = new Advert();
    $advert->setTitle('Recherche développeur Symfony.');
    $advert->setAuthor('Alexandre');
    $advert->setContent('Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…');
    // On peut ne pas définir ni la date ni la publication, //car ces attributs sont définis automatiquement dans le constructeur
    // On récupère l'EntityManager
    $em = $this->getDoctrine()->getManager();

    // Étape 1 : On « persiste » l'entité
    $em->persist($advert);

    // Étape 2 : On « flush » tout ce qui a été persisté avant
    $em->flush();

    //GESTION REQUETE
    // Si la requête est en POST, c'est que le visiteur a soumis le formulaire
    if ($request->isMethod('POST')) {
      // Ici, on s'occupera de la création et de la gestion du formulaire

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

      // Puis on redirige vers la page de visualisation de cettte annonce
      return $this->redirectToRoute('Itaru_platform_single', array('id' => $advert -> getId())) ;
    }

    // Si on n'est pas en POST, alors on affiche le formulaire
    return $this->render('ItaruPlatformBundle:Advert:add.html.twig',  array('advert' => $advert));
  }

  public function editAction($id, Request $request)
  {
    // Ici, on récupérera l'annonce correspondante à $id Même mécanisme que pour l'ajout
    if ($request->isMethod('POST')) {
      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

      return $this->redirectToRoute('Itaru_platform_single', array('id' => 5));
    }

    //en attendant la data base, def en dur de $advert
     $advert = array(
      'title'   => 'Recherche développpeur Symfony',
      'id'      => $id,
      'author'  => 'Alexandre',
      'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
      'date'    => new \Datetime()
    );

    return $this->render(
      'ItaruPlatformBundle:Advert:edit.html.twig',
       array('advert' => $advert)
    );
  }

  public function deleteAction($id)
  {
    // Ici, on récupérera l'annonce correspondant à $id

    // Ici, on gérera la suppression de l'annonce en question

    return $this->render('ItaruPlatformBundle:Advert:delete.html.twig');
  }


  //action de test pour les exemples du cours
  public function testAction($id, Request $request) //récupération de l'objet request généré par le router
  {
      //gestion réponse via template twig
//        $tag = $request -> query -> get('tag');
//        return $this 
//            -> render(
//                        'ItaruPlatformBundle:Advert:test.html.twig',
//                        array(  'id'=>$id,
//                                'tag'=>$tag)
//            );
      
      // gestion reponse "à la main"
//        $response = new Response();
//        $response -> setContent("affichage de l'erreur 404");
//        $response -> setStatusCode(Response::HTTP_NOT_FOUND);
//        return $response;
      
      //exemple de paramètres hors route : le tag
//        $tag = $request -> query -> get('tag');
//        return new Response("Affichage de l'annonce d'id : ".$id.' / avec le tag '.$tag);
      //redirection version longue
//        $url = $this    -> get('router')
//                        -> generate('Itaru_platform_home');
//        return new RedirectResponse($url);
      
      //redirection version raccourcie via méthode du contrôleur
//        return $this -> redirectToRoute('Itaru_platform_home');
      
      //Modifier le content-type
//        $tag = $request -> query -> get('tag');
//        $response = new Response(json_encode(array('id'=>$id, 'tag' => $tag )));
//        $response -> headers -> set('Content-Type', 'application/json');
//        return $response;
      
      //Gestionnaire de session
      // $session = $request->getSession();
      // $session -> set('user_id', 91);
      // $userId = $session -> get('user_id');
      
      // return new Response('user identifiant : '.$userId);

      //twig test depuis un contrôleur
      $tag = $request -> query -> get('tag'); 
    return $this->render('ItaruPlatformBundle:Advert:test.html.twig', 
                          array('id'=> $id, 'tag' => $tag));
  }

  public function menuAction() {
    // On fixe en dur une liste ici, bien entendu par la suite
    // on la récupérera depuis la BDD !
    $listAdverts = array(
      array('id' => 2, 'title' => 'Recherche développeur Symfony'),
      array('id' => 5, 'title' => 'Mission de webmaster'),
      array('id' => 9, 'title' => 'Offre de stage webdesigner')
    );

    return $this->render('ItaruPlatformBundle:Advert:menu.html.twig',
                          array( 'listAdverts' => $listAdverts)
      // Tout l'intérêt est ici : le contrôleur passe les variables nécessaires au template !
                      );
  }


}

