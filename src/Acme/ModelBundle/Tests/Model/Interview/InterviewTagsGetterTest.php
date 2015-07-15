<?php

namespace Acme\ModelBundle\Tests\Model\Interview;

use Acme\ModelBundle\Tests\Model\TestSuite;;
use Acme\ModelBundle\Model\Interview\InterviewTagsGetter;

/**
 * @author Andrea Fiori
 * @since  05 June 2014
 */
class InterviewTagsGetterTest extends TestSuite
{
    /**
     * @var InterviewTagsGetter
     */
    private $objectGetter;
    
    protected function setUp()
    {
        parent::setUp();
        
        $this->objectGetter = new InterviewTagsGetter($this->getEntityManager());
    }

    public function testSetMainQuery()
    {
        $this->assertInstanceOf('\Doctrine\ORM\QueryBuilder', $this->objectGetter->setMainQuery());
    }

    public function testSetQuestionId()
    {
        $this->objectGetter->setQuestionId(11);

        $this->assertNotEmpty( $this->objectGetter->getQueryBuilder()->getParameter('questionId') );
    }
}