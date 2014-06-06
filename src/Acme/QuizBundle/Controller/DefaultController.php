<?php

namespace Acme\QuizBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\QuizBundle\Model\Answers\AnswersGetterWrapper;
use Acme\QuizBundle\Model\Answers\AnswersGetter;
use Acme\QuizBundle\Model\Questions\QuestionsQueryGetter;

/**
 * @author Andrea Fiori
 * @since  04 June 2014
 */
class DefaultController extends Controller
{
    private $em;
    
    public function indexAction()
    {
        $this->em = $this->get('doctrine.orm.entity_manager');
        
        $questionsQueryGetter =  new QuestionsQueryGetter($this->em);
        $query = $questionsQueryGetter->getQuery();

        $pagination = $this->getPagination($query);
        $answers    = $this->getAnswers($pagination);
        
        return $this->render('AcmeQuizBundle:Default:index.html.twig', array(
            'pagination' => $pagination,
            'qa'         => $answers
        ));
    }

        private function getPagination($query)
        {
            $paginator  = $this->get('knp_paginator'); 
            $pagination = $paginator->paginate(
                $query,
                $this->get('request')->query->get('page', 1), // page number,
                5 // limit per page
            );

            return $pagination;
        }
        
        /**
         * 
         * @param type $pagination
         * @return type
         */
        private function getAnswers($pagination)
        {
            if (!$pagination) {
                return false;
            }
            
            $arrayAnswers = array();
            foreach($pagination as $paging) {

                $answersGetterWrapper = new AnswersGetterWrapper( new AnswersGetter($this->em) );
                $answersGetterWrapper->setInput( array( "questionId" => $paging->getId() ) );
                $answersGetterWrapper->setupQueryBuilder();

                $paging->answers = $answersGetterWrapper->getRecords();

                $arrayAnswers[] = $paging;
            }

            return $arrayAnswers;
        }
}