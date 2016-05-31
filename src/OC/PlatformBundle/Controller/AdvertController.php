<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

// N'oubliez pas ce use :
use OC\PlatformBundle\Entity\Advert;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller {

    public function menuAction() {

        // On fixe en dur une liste ici, bien entendu par la suite
        // on la récupérera depuis la BDD !
        $listAdverts = array(
            array('id' => 2, 'title' => 'Recherche développeur Symfony2'),
            array('id' => 5, 'title' => 'Mission de webmaster'),
            array('id' => 9, 'title' => 'Offre de stage webdesigner')
        );
        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
                    // Tout l'intérêt est ici : le contrôleur passe
                    // les variables nécessaires au template !

                    'listAdverts' => $listAdverts
        ));
    }

    //public function viewAction($id) {
    //    return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
    //                'id' => $id
    //    ));
    //}

    public function addAction(Request $request) {
        // Création de l'entité
        $advert = new Advert();
        $advert->setTitle('Recherche développeur Symfony2.');
        $advert->setAuthor('Alexandre');
        $advert->setContent("Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…");
        // On peut ne pas définir ni la date ni la publication,
        // car ces attributs sont définis automatiquement dans le constructeur
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();
        // Étape 1 : On « persiste » l'entité
        $em->persist($advert);
        // Étape 2 : On « flush » tout ce qui a été persisté avant
        $em->flush();
        // On récupère le service
        $antispam = $this->container->get('oc_platform.antispam');
        // Je pars du principe que $text contient le texte d'un message quelconque
        //$text = '...';
        //if ($antispam->isSpam($text)) {
        //    throw new \Exception('Votre message a été détecté comme spam !');
        //}
        if ($request->isMethod('POST')) {
            // Le « flashBag » est ce qui contient les messages flash dans la session
            // Il peut bien sûr contenir plusieurs messages :
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
            return $this->redirect($this->generateUrl('oc_platform_view', array('id' => $advert->getId())));
        }
        // Puis on redirige vers la page de visualisation de cette annonce
        return $this->render('OCPlatformBundle:Advert:add.html.twig');
    }

    // La route fait appel à OCPlatformBundle:Advert:view,
    // on doit donc définir la méthode viewAction.
    // On donne à cette méthode l'argument $id, pour
    // correspondre au paramètre {id} de la route
    //public function viewAction($id, Request $request) {
    // On récupère notre paramètre tag
    //$tag = $request->query->get('tag');
    // $id vaut 5 si l'on a appelé l'URL /platform/advert/5
    // Ici, on récupèrera depuis la base de données
    // l'annonce correspondant à l'id $id.
    // Puis on passera l'annonce à la vue pour
    // qu'elle puisse l'afficher
    //return new Response("Affichage de l'annonce d'id : " . $id . ", avec le tag : " . $tag);
    //}

    public function viewAction($id) {

        /* $advert = array(
          'title' => 'Recherche développpeur Symfony2',
          'id' => $id,
          'author' => 'Alexandre',
          'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
          'date' => new \Datetime()
          );


          return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
          'advert' => $advert
          )); */
        // On récupère le repository
        $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('OCPlatformBundle:Advert')
        ;
        // On récupère l'entité correspondante à l'id $id
        $advert = $repository->find($id);
        // $advert est donc une instance de OC\PlatformBundle\Entity\Advert
        // ou null si l'id $id  n'existe pas, d'où ce if :
        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }
        // Le render ne change pas, on passait avant un tableau, maintenant un objet
        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
                    'advert' => $advert
        ));
    }

    // ... et la méthode indexAction que nous avons déjà créée
    public function indexbisAction() {
        // On ne sait pas combien de pages il y a
        // Mais on sait qu'une page doit être supérieure ou égale à 1
        //if ($page < 1) {
        // On déclenche une exception NotFoundHttpException, cela va afficher
        // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
        //throw new NotFoundHttpException('Page "' . $page . '" inexistante.');
//}
// Ici, on récupérera la liste des annonces, puis on la passera au template
// Mais pour l'instant, on ne fait qu'appeler le template
        return $this->render('OCPlatformBundle:Advert:indexbis.html.twig');
    }

// On récupère tous les paramètres en arguments de la méthode
    public function viewSlugAction($slug, $year, $format) {
        return new Response(
                "On pourrait afficher l'annonce correspondant au
            slug '" . $slug . "', créée en " . $year . " et au format " . $format . "."
        );
    }

    //public function editAction($id, Request $request) {
// Ici, on récupérera l'annonce correspondante à $id
// Même mécanisme que pour l'ajout
    //if ($request->isMethod('POST')) {
    //  $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');
    //return $this->redirectToRoute('oc_platform_view', array('id' => 5));
    //}
    //return $this->render('OCPlatformBundle:Advert:edit.html.twig');
    //}

    public function editAction($id, Request $request) {

        $advert = array(
            'title' => 'Recherche développpeur Symfony2',
            'id' => $id,
            'author' => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
            'date' => new \Datetime()
        );


        return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
                    'advert' => $advert
        ));
    }

    public function deleteAction($id) {
// Ici, on récupérera l'annonce correspondant à $id
// Ici, on gérera la suppression de l'annonce en question
        return $this->render('OCPlatformBundle:Advert:delete.html.twig');
    }

    public function indexAction($page) {

// ...
// Notre liste d'annonce en dur

        $listAdverts = array(
            array(
                'title' => 'Recherche développpeur Symfony2',
                'id' => 1,
                'author' => 'Alexandre',
                'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
                'date' => new \Datetime()),
            array(
                'title' => 'Mission de webmaster',
                'id' => 2,
                'author' => 'Hugo',
                'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
                'date' => new \Datetime()),
            array(
                'title' => 'Offre de stage webdesigner',
                'id' => 3,
                'author' => 'Mathieu',
                'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
                'date' => new \Datetime())
        );


// Et modifiez le 2nd argument pour injecter notre liste

        return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
                    'listAdverts' => $listAdverts
        ));
    }

}
