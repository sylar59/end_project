<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    /**
     * Requête pour recup un produit en fonction de la recherche de l'utilisateur
     * @return Product[]
     */
    public function findWithSearch(Search $search)
    {
        $query =$this
            ->createQueryBuilder('p') //p pour product
            ->select('c','p')//c pour category - p pour product
            ->join('p.category','c'); //p.category --> category de product et c category
        if(!empty($search->categories)){ // Si des catégories ont étaient cochées
            $query=$query
                ->andWhere('c.id IN (:categories)') //On ajoute un where
                ->setParameter('categories',$search->categories);  // pour spécifier ce qu'est le paramètre appelé ci-dessus
        }

        if(!empty($search->string)){
            $query = $query
                ->andWhere('p.name LIKE :string')
                ->setParameter('string',"%{$search->string}%");

        }

        return $query->getQuery()->getResult(); // Retourne les résultats de la query
    }

}
