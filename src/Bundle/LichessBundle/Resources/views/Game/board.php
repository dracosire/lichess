<?php

$board = $player->getGame()->getBoard();
$squares = $board->getSquares();
$isGameStarted = $player->getGame()->getIsStarted();
$turnPlayer = $player->getGame()->getTurnPlayer();

if ($player->isBlack())
{
    $squares = array_reverse($squares, true);
}

$x = $y = 1;

print '<div class="lichess_board">';

foreach($squares as $squareKey => $square)
{
    printf('<div class="lcs %s%s" id="%s" style="top:%dpx;left:%dpx;">',
        $square->getColor(), $checkSquareKey === $squareKey ? ' check' : '', $squareKey, 64*(8-$x), 64*($y-1)
    );

    print '<div class="lcsi"></div>';

    if($piece = $board->getPieceByKey($squareKey)) {
        if($isGameStarted || $piece->getPlayer() === $player) {
            printf('<div class="lichess_piece %s %s"></div>',
                strtolower($piece->getClass()), $piece->getColor()
            );
        }
    }

    print '</div>';

    if (++$x === 9)
    {
        $x = 1; ++$y;
    }
}

print '</div>';
