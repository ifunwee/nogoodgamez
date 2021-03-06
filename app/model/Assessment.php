<?php
namespace Documents;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
/**
 * @ODM\Document
 */

class Assessment
{
	/** @ODM\Id */
    private $id;

    /** @ODM\ReferenceOne(targetDocument="Documents\Game", nullable=true) */
    private $game;

    /** @ODM\ReferenceOne(targetDocument="Documents\User", nullable=true) */
    private $user;

    /** @ODM\Boolean */
    private $like;

    /** @ODM\Date */
    private $happened;

    public function setLike($like)
    {
        $this->like = $like;
    }

    public function setHappened($happened)
    {
        $this->happened = $happened;
    }



    public function setGame(Game $game) { $this->game = $game; }

    public function setUser (User $user) { $this->user = $user; }


    public function getId()
    {
        return $this->id;
    }

    public function getLike()
    {
        return $this->like;
    }

    public function getHappened()
    {
        return $this->happened;
    }




    

}

?>