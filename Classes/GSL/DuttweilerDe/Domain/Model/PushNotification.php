<?php
namespace GSL\DuttweilerDe\Domain\Model;

/*
 * This file is part of the GSL.DuttweilerDe package.
 */

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * Data Model for a push notification to be sent to the DuttweilerApp via GCM
 *
 * @Flow\Entity
 */
class PushNotification
{

    /**
     * @var integer
	 * @Flow\Identity
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
	 * @Flow\Validate(type="NotEmpty")
	 * @Flow\Validate(type="Label")
     * @var string
     */
    protected $headline;

    /**
	 * @Flow\Validate(type="NotEmpty")
	 * @Flow\Validate(type="Label")
     * @var string
     */
    protected $subheadline;

    /**
	 * @Flow\Validate(type="NotEmpty")
	 * @Flow\Validate(type="String")
     * @var string
     */
    protected $nodeName;

	/**
	 * @Flow\Validate(type="NotEmpty")
	 * @Flow\Validate(type="DateTime")
	 * @ORM\Column(type="datetime")
	 * @var \DateTime
	 */
	protected $issueDate;

	/**
	 * @param string $headline
	 * @param string $subheadline
	 * @param string $nodeName
	 */
	public function __construct($headline, $subheadline, $nodeName) {
		$this->headline = $headline;
		$this->subheadline = $subheadline;
		$this->nodeName = $nodeName;
		$this->issueDate = new \Neos\Flow\Utility\Now;
	}

	/**
	 * @return \DateTime
	 */
	public function getIssueDate() {
		return $this->issueDate;
	}

	/**
	 * @param \DateTime $date
	 * @return void
	 */
	public function setIssueDate($date) {
		$this->issueDate = $date;
	}


    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     * @return void
     */
    /*public function setId($id)
    {
        $this->id = $id;
    }*/

    /**
     * @return string
     */
    public function getHeadline()
    {
        return $this->headline;
    }

    /**
     * @param string $headline
     * @return void
     */
    public function setHeadline($headline)
    {
        $this->headline = $headline;
    }

    /**
     * @return string
     */
    public function getSubheadline()
    {
        return $this->subheadline;
    }

    /**
     * @param string $subheadline
     * @return void
     */
    public function setSubheadline($subheadline)
    {
        $this->subheadline = $subheadline;
    }

    /**
     * @return string
     */
    public function getNodeName()
    {
        return $this->nodeName;
    }

    /**
     * @param string $nodeName
     * @return void
     */
    public function setNodeName($nodeName)
    {
        $this->nodeName = $nodeName;
    }

}
