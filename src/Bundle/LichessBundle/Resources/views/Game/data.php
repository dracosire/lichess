<?php
$game = $player->getGame();
$gameHash = $game->getHash();
$color = $player->getColor();
$opponent = $player->getOpponent();
$playerFullHash = $player->getFullHash();
$data = array(
    'game' => array(
        'hash' => $game->getHash(),
        'started' => $game->getIsStarted(),
        'finished' => $game->getIsFinished(),
    ),
    'player' => array(
        'color' => $player->getColor(),
        'version' => $player->getStack()->getVersion(),
        'spectator' => false
    ),
    'opponent' => array(
        'color' => $opponent->getColor(),
        'ai' => $opponent->getIsAi(),
        'connected' => $isOpponentConnected
    ),
    'sync_delay' => $parameters['lichess.synchronizer.delay'] * 1000,
    'animation_delay' => 500,
    'url' => array(
        'sync' => $view->router->generate('lichess_sync', array('hash' => $gameHash, 'color' => $color, 'version' => 9999999, 'playerFullHash' => $playerFullHash)),
        'table' => $view->router->generate('lichess_table', array('hash' => $gameHash, 'color' => $color, 'playerFullHash' => $playerFullHash)),
        'opponent' => $view->router->generate('lichess_opponent', array('hash' => $gameHash, 'color' => $color, 'playerFullHash' => $playerFullHash)),
        'move' => $view->router->generate('lichess_move', array('hash' => $playerFullHash, 'version' => 9999999)),
        'say' => $view->router->generate('lichess_say', array('hash' => $playerFullHash, 'version' => 9999999)),
        'ai_level' => $opponent->getIsAi() ? $view->router->generate('lichess_ai_level', array('hash' => $playerFullHash)) : null
    ),
    'i18n' => array(
        'Game Over' => 'Game Over',
        'Waiting for opponent' => 'Waiting for opponent',
        'Your turn' => 'Your turn'
    ),
    'possible_moves' => $possibleMoves
);
?>
<script type="text/javascript">var lichess_data = <?php echo json_encode($data) ?>;</script>
