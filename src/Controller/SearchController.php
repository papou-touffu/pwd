<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Category;
use App\Entity\Critique;
use App\Repository\CritiqueRepository;
class SearchController extends AbstractController
{
    //page de recherches qui génère une recherche de résultat via l'entity Category
    /**
     * @Route("/search", name="search")
     */
    public function search(CritiqueRepository $repo)
    {
        $formBuilder = $this->createFormBuilder(null);
        //création du formulaire
        $formBuilder->setAction($this->generateUrl('search_result'))
            //appel d'une barre avec un menu déroulant contenant les différentes catégory relié au recettes.
            ->add('query', EntityType::class , [
                'label' => 'Format de tome  recherché',
                'class' => Category::class, 'choice_label' => 'title', 'multiple' => false])
            ->add('rechercher', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary']
            ]);
        $form = $formBuilder->getForm();
        return $this->render('search/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    //gère l'affichage des résultat sur une page annexe et applique le filtre pour n'afficher que les recettes associer au bon tag.
    /**
     * @Route("/search/result", name="search_result")
     */
    public function handleSearch(Request $request, CritiqueRepository $repo) {
        $query = $request->request->get('form')['query'];
        //Affiche uniquement les recettes correspondant à la catégorie choisie sur une page dédié
        if($query) {
            $critique = $repo->findBy(
                array('category' => $query)
            );
        }
        return $this->render('search/result.html.twig', [
            'critiques' => $critique,
            'query' => $query
        ]);
    }
}