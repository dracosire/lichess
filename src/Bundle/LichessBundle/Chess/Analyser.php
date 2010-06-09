<?php

namespace Bundle\LichessBundle\Chess;

use Bundle\LichessBundle\Chess\Board;
use Bundle\LichessBundle\Entities\Player;
use Bundle\LichessBundle\Entities\Piece;
use Bundle\LichessBundle\Entities\Piece\King;

class Analyser
{
    /**
     * The board to analyse
     *
     * @var Board
     */
    protected $board = null;

    /**
     * Get board
     * @return Board
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * Set board
     * @param  Board
     * @return null
     */
    public function setBoard($board)
    {
        $this->board = $board;
    }

    public function __construct(Board $board)
    {
        $this->board = $board;
        $this->game = $board->getGame();
    }

    public function isKingAttacked(Player $player)
    {
        return in_array($player->getKing()->getSquareKey(), $this->getPlayerControlledKeys($player->getOpponent()));
    }

    /**
     * @return array flat array of keys
     */
    public function getPlayerControlledKeys(Player $player, $includeKing = true)
    {
        $controlledKeys = array();
        foreach(PieceFilter::filterAlive($player->getPieces()) as $piece)
        {
            if(!$includeKing && !$piece instanceof Piece\King) {
                $controlledKeys = array_merge($controlledKeys, $this->getPieceControlledKeys($piece));
            }
        }
        return $controlledKeys;
    }

    /**
     * @return array key => array keys
     */
    public function getPlayerPossibleMoves(Player $player)
    {
        $possibleMoves = array();
        $isKingAttacked = $this->isKingAttacked($player);
        $allOpponentPieces = PieceFilter::filterNotClass(PieceFilter::filterAlive($player->getOpponent()->getPieces()), 'King');
        $projectionOpponentPieces = PieceFilter::filterProjection($allOpponentPieces);
        $king = $player->getKing();
        $kingSquareKey = $king->getSquareKey();
        foreach(PieceFilter::filterAlive($player->getPieces()) as $piece)
        {
            $pieceOriginalX = $piece->getX();
            $pieceOriginalY = $piece->getY();
            //if we are not moving the king, and the king is not attacked, don't check pawns nor knights
            if (!$piece instanceof King && !$isKingAttacked) {
                $opponentPieces = $projectionOpponentPieces;
            }
            else {
                $opponentPieces = $allOpponentPieces;
            }

            $squares = $this->board->cleanSquares($piece->getBasicTargetSquares());
            if($piece instanceOf King && !$isKingAttacked && !$piece->hasMoved()) {
                $squares = $this->addCastlingSquares($piece, $squares);
            }
            foreach($squares as $it => $square)
            {
                // kings move to its target so we update its position
                if ($piece instanceof King)
                {
                    $kingSquareKey = $square->getKey();
                }

                // kill opponent piece
                if ($killedPiece = $square->getPiece())
                {
                    $killedPiece->setIsDead(true);
                }

                $this->board->move($piece, $square->getX(), $square->getY());

                foreach($opponentPieces as $opponentPiece)
                {
                    //if($opponentPiece instanceof King) {
                    //continue;
                    //}
                    if (null !== $killedPiece && $opponentPiece->getIsDead())
                    {
                        continue;
                    }

                    // if our king gets attacked
                    if (in_array($kingSquareKey, $this->getPieceControlledKeys($opponentPiece, $piece instanceof King)))
                    {
                        // can't go here
                        unset($squares[$it]);
                        break;
                    }
                }

                $this->board->move($piece, $pieceOriginalX, $pieceOriginalY);

                // if a virtual piece has been killed, bring it back to life
                if ($killedPiece)
                {
                    $killedPiece->setIsDead(false);
                    $this->board->add($killedPiece);
                }
            }
            if(!empty($squares)) {
                $possibleMoves[$piece->getSquareKey()] = $this->board->squaresToKeys($squares);
            }
        }
        return $possibleMoves;
    }

    /**
     * @return array flat array of keys
     */
    public function getPieceControlledKeys(Piece $piece)
    {
        return $this->board->squaresToKeys($this->board->cleanSquares($piece->getBasicTargetSquares()));
    }

    /**
     * Add castling moves if available
     *
     * @return array the squares where the king can go
     **/
    protected function addCastlingSquares(King $piece, array $squares)
    {
        $player = $piece->getPlayer();
        $rooks = PieceFilter::filterNotMoved(PieceFilter::filterClass(PieceFilter::filterAlive($player->getPieces()), 'Rook'));
        if(empty($rooks)) {
            return $squares;
        }
        $opponent = $player->getOpponent();
        $opponentControlledKeys = $this->getPlayerControlledKeys($opponent, true);

        foreach($rooks as $rook)
        {
            $canCastle = true;
            $rookSquare = $rook->getSquare();
            if(in_array($rookSquare, $opponentControlledKeys)) {
                continue;
            }
            $kingSquare = $piece->getSquare();
            $squaresToRook = array();
            $dx = $kingSquare->getX() > $rookSquare->getX() ? -1 : 1;
            $square = $kingSquare;
            while(($square = $square->getSquareByRelativePos($dx, 0)) && !$square->is($rookSquare))
            {
                $squaresToRook[] = $square;
            }
            foreach($squaresToRook as $square)
            {
                if (!$square->isEmpty() || in_array($square->getKey(), $opponentControlledKeys))
                {
                    $canCastle = false;
                    break;
                }
            }
            if ($canCastle)
            {
                $squares[] = $squaresToRook[1];
            }
        }
        return $squares;
    }
}